<h1>Halaman Admin</h1>
<p>Halo, {{ auth()->user()->username }}. Ini halaman khusus admin.</p>
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>
