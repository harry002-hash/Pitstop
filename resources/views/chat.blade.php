<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat | PitStop</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-w-[320px] bg-white font-sans text-[#111111]">
    <main class="flex min-h-screen flex-col">
        <section class="mx-auto w-[calc(100%-24px)] max-w-[960px] flex-1 py-6 sm:w-[calc(100%-40px)] sm:py-[34px] sm:pb-[62px]" aria-labelledby="vehicle-title">
            <h1 id="vehicle-title" class="text-[clamp(2.25rem,5vw,3.25rem)] font-extrabold leading-[.95] tracking-[-1.5px]">KB 8123 XG</h1>
            <p class="mb-[22px] mt-[14px] text-[clamp(1rem,2.2vw,1.32rem)] font-bold sm:mb-8">Budi Hermanto, Vario 125 Gen 1, Motor</p>

            <section class="relative min-h-[500px] overflow-hidden rounded-b-xl bg-white px-[65px] pb-[84px] pt-[18px] shadow-[0_3px_4px_rgb(0_0_0/23%)] sm:min-h-[485px] sm:px-[93px] sm:pb-[102px] sm:pt-6" aria-label="Percakapan dengan bengkel">
                <div class="absolute inset-y-0 left-0 w-[14px] bg-[#ff0000] sm:w-[21px]"></div>
                <div class="absolute inset-y-0 left-[14px] w-[37px] bg-[linear-gradient(90deg,#b30b0b_0_50%,#880b0b_50%)] sm:left-[21px] sm:w-[42px]"></div>

                <div class="relative h-[380px] overflow-hidden rounded-[5px] bg-[#f6f6f6] shadow-[0_2px_3px_rgb(0_0_0/28%)] sm:h-[361px]">
                    <div class="h-[30px] bg-[#ed2429] px-[11px] py-2 text-xs font-bold">Bengkel Aju</div>
                    <div class="absolute right-0 top-[176px] h-8 w-1.5 rounded-l bg-[#ed2429]"></div>

                    <div id="messages" class="flex h-[calc(100%-30px)] flex-col gap-1 overflow-hidden px-[14px] py-[15px] sm:px-[41px]">
                        <div class="flex items-start gap-3">
                            <span class="grid h-[45px] w-[45px] shrink-0 place-items-center bg-[#ec2429] text-xl font-bold" aria-hidden="true">A</span>
                            <p class="m-0 max-w-[208px] rounded-b-[10px] bg-white px-[5px] py-[14px] text-xs leading-[1.15] shadow-[0_2px_3px_rgb(0_0_0/30%)]">Pagi bang, shockbreaker lu bengkok bang</p>
                        </div>

                        <div class="mt-[35px] flex flex-row-reverse items-center gap-3 self-end sm:mt-[45px]">
                            <span class="grid h-[45px] w-[45px] shrink-0 place-items-center bg-[#5729e6] text-xl font-bold" aria-hidden="true">B</span>
                            <p class="m-0 rounded-[10px_0_10px_10px] bg-white px-[9px] py-[17px] text-xs leading-[1.15] shadow-[0_2px_3px_rgb(0_0_0/30%)]">Ha ?, mana mungkin</p>
                        </div>

                        <div class="mt-[64px] flex items-start gap-3">
                            <span class="grid h-[45px] w-[45px] shrink-0 place-items-center bg-[#ec2429] text-xl font-bold" aria-hidden="true">A</span>
                            <div class="h-[180px] w-[106px] shrink-0 overflow-hidden rounded-b-[10px] bg-white p-1 shadow-[0_2px_3px_rgb(0_0_0/30%)]" role="img" aria-label="Foto shockbreaker motor">
                                <div class="grid h-full w-full place-items-center bg-[linear-gradient(145deg,#d2d7da,#83898b_48%,#373a3c)] text-center text-[10px] text-white">Foto shockbreaker</div>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="chat-form" class="absolute bottom-[14px] right-[18px] flex h-[31px] w-[calc(100%-83px)] overflow-hidden rounded-[13px] bg-white shadow-[0_2px_3px_rgb(0_0_0/25%)] sm:right-[60px] sm:w-[min(392px,calc(100%-130px))]" action="{{ route('chat.send') }}" method="POST">
                    @csrf
                    <input type="hidden" id="receiver_id" name="receiver_id" value="1">
                    <button class="w-[31px] shrink-0 border-r border-[#a8a8a8] bg-transparent text-[#777777]" type="button" aria-label="Tambahkan lampiran">+</button>
                    <input class="min-w-0 flex-1 border-0 bg-transparent px-3 text-[10px] outline-0 placeholder:text-[#999999]" id="message" name="message" type="text" placeholder="Ketik Pesan..." autocomplete="off" required>
                </form>
            </section>

            <div class="mt-[37px] flex justify-stretch gap-2 sm:justify-end">
                <button class="h-[33px] flex-1 rounded-[5px] bg-[#ed2429] text-base font-bold text-white shadow-[0_2px_3px_rgb(0_0_0/25%)] sm:w-[139px] sm:flex-none" type="button">Batal</button>
                <button class="h-[33px] flex-1 rounded-[5px] bg-[#20e33a] text-base font-bold text-white shadow-[0_2px_3px_rgb(0_0_0/25%)] sm:w-[139px] sm:flex-none" type="submit" form="chat-form">Kirim</button>
            </div>
        </section>

        <footer class="min-h-[63px] bg-[#8d0000] px-5 pb-[10px] pt-[21px] text-center text-white">
            <p class="m-0 text-sm font-bold">© &nbsp;PitStop. All Rights Reserved.</p>
            <p class="m-0 mt-[7px] text-right text-[9px] text-[#d9a0a0]">V1.0 | Bantuan | Kebijakan Privasi</p>
        </footer>
    </main>
</body>
</html>