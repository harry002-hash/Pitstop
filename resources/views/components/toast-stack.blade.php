{{-- Toast popup notifikasi + bunyi. Dipakai dashboard admin & customer.
     Props: fetchUrl (endpoint riwayat), channel (private, opsional),
     eventName (nama event broadcast), mode ('alert'|'chat'),
     chatUrl (tujuan klik untuk mode chat), statusChannel (public, opsional). --}}
@props([
    'fetchUrl',
    'channel' => null,
    'eventName' => '.staff.alert',
    'mode' => 'alert',
    'chatUrl' => null,
    'statusChannel' => null,
])

<div id="toast-stack" class="fixed bottom-4 right-4 z-[60] flex w-80 max-w-[85vw] flex-col gap-2"></div>

<style>
    @keyframes toast-in {
        from { opacity: 0; transform: translateX(24px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .toast-enter { animation: toast-in 0.25s ease-out; }
    .toast-leave { opacity: 0; transform: translateX(24px); transition: all 0.25s ease-in; }
</style>

<script>
    (() => {
        const stack = document.getElementById('toast-stack');
        if (!stack || stack.dataset.ready) return;
        stack.dataset.ready = '1';

        const fetchUrl = @json($fetchUrl);
        const channel = @json($channel);
        const eventName = @json($eventName);
        const mode = @json($mode);
        const chatUrl = @json($chatUrl ?? route('chat'));
        const statusChannel = @json($statusChannel);

        const seen = new Set();
        let firstLoad = true;
        let lastStatus = null;
        let audioCtx = null;

        function beep() {
            try {
                audioCtx ??= new (window.AudioContext || window.webkitAudioContext)();
                if (audioCtx.state === 'suspended') void audioCtx.resume();
                [880, 1174].forEach((freq, i) => {
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.connect(gain);
                    gain.connect(audioCtx.destination);
                    osc.type = 'sine';
                    osc.frequency.value = freq;
                    const t = audioCtx.currentTime + i * 0.18;
                    gain.gain.setValueAtTime(0.0001, t);
                    gain.gain.exponentialRampToValueAtTime(0.4, t + 0.02);
                    gain.gain.exponentialRampToValueAtTime(0.0001, t + 0.16);
                    osc.start(t);
                    osc.stop(t + 0.2);
                });
            } catch (e) { /* audio tidak tersedia, abaikan */ }
        }

        function esc(s) {
            return String(s ?? '').replace(/[&<>"']/g, (c) => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
            }[c]));
        }

        function toast(n, accent) {
            const el = document.createElement('div');
            el.className = 'toast-enter cursor-pointer overflow-hidden rounded-xl border border-gray-100 bg-white shadow-xl';
            el.innerHTML =
                `<div class="flex gap-3 px-4 py-3">` +
                `<span class="w-1 shrink-0 rounded-full ${accent}"></span>` +
                `<span class="min-w-0 flex-1">` +
                `<span class="block truncate text-sm font-bold text-gray-900">${esc(n.title)}</span>` +
                `<span class="block truncate text-xs text-gray-600">${esc(n.body)}</span>` +
                `<span class="mt-0.5 block text-[11px] text-gray-400">${esc(n.time ?? '')}</span>` +
                `</span>` +
                `<button type="button" aria-label="Tutup" class="h-fit shrink-0 rounded-full px-1 text-lg leading-none text-gray-400 hover:text-gray-600">&times;</button>` +
                `</div>`;
            el.querySelector('button').addEventListener('click', (e) => {
                e.stopPropagation();
                dismiss(el);
            });
            el.addEventListener('click', () => {
                if (n.url) window.location.href = n.url;
            });
            stack.prepend(el);
            while (stack.children.length > 4) stack.lastChild.remove();
            setTimeout(() => dismiss(el), 7000);
        }

        function dismiss(el) {
            if (!el.isConnected) return;
            el.classList.add('toast-leave');
            setTimeout(() => el.remove(), 250);
        }

        function notify(n, accent) {
            toast(n, accent);
            beep();
        }

        function normalizeAlert(n) {
            return {
                key: n.key ?? [n.type, n.customer_id, n.title, n.time].join('|'),
                title: n.title, body: n.body, url: n.url, time: n.time,
                accent: n.type === 'chat' ? 'bg-blue-500' : 'bg-yellow-500',
            };
        }

        function normalizeChat(n) {
            return {
                key: 'chat:' + (n.id ?? [n.sender_id, n.message, n.image_url].join('|')),
                title: 'Balasan dari ' + (n.sender || 'bengkel'),
                body: n.message || 'Mengirim gambar',
                url: chatUrl,
                time: '',
                accent: 'bg-blue-500',
            };
        }

        function ingest(raw, silent) {
            const n = mode === 'chat' ? normalizeChat(raw) : normalizeAlert(raw);
            if (!n.key || seen.has(n.key)) return;
            seen.add(n.key);
            if (!silent) notify(n, n.accent);
        }

        function checkStatus(vehicle) {
            if (!vehicle) return;
            if (lastStatus === null) {
                lastStatus = vehicle.status;
                return;
            }
            if (vehicle.status !== lastStatus) {
                lastStatus = vehicle.status;
                notify({
                    title: 'Status motor berubah',
                    body: vehicle.status_label ?? vehicle.status,
                    url: null,
                    time: '',
                }, 'bg-green-500');
            }
        }

        async function poll(silent) {
            try {
                const res = await fetch(fetchUrl, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;
                const data = await res.json();
                [...(data.notifications ?? [])].reverse().forEach((n) => ingest(n, silent));
                checkStatus(data.vehicle);
            } catch (e) { /* offline, coba lagi next poll */ }
            firstLoad = false;
        }

        void poll(true);
        setInterval(() => void poll(false), 15000);

        window.addEventListener('load', () => {
            if (!window.Echo) return;
            try {
                if (channel) {
                    window.Echo.private(channel).listen(eventName, (n) => ingest(n, false));
                }
                if (statusChannel) {
                    window.Echo.channel(statusChannel).listen('.status.updated', (s) => {
                        const key = 'status:' + s.id + ':' + s.status;
                        if (seen.has(key)) return;
                        seen.add(key);
                        lastStatus = s.status;
                        notify({
                            title: 'Status motor berubah',
                            body: s.status_label ?? s.status,
                            url: null,
                            time: '',
                        }, 'bg-green-500');
                    });
                }
            } catch (e) { /* polling tetap jalan sebagai fallback */ }
        });
    })();
</script>
