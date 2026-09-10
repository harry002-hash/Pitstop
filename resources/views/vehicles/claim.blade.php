<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klaim Motor</title>
</head>
<body>

    <h1>Klaim Motor</h1>
    <p>Login sebagai: {{ auth()->user()->username }}</p>
    <p>Masukkan nomor KB + password yang diberikan bengkel.</p>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('vehicle.claim.store') }}">
        @csrf

        <label for="plate_number">Nomor KB:</label>
        <input
            type="text"
            id="plate_number"
            name="plate_number"
            value="{{ old('plate_number') }}"
            placeholder="cth: KB 1234 AB"
            required
            autofocus
        >

        <br>

        <label for="plate_password">Password KB:</label>
        <input
            type="password"
            id="plate_password"
            name="plate_password"
            required
        >

        <br>

        <button type="submit">Tautkan Motor</button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>

</body>
</html>
