<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catat Motor Baru</title>
</head>
<body>

    <h1>Catat Motor Baru (offline)</h1>
    <p>Catat KB + tentukan password, lalu berikan password ke customer.</p>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('owner.vehicles.store') }}">
        @csrf

        <label for="vehicle_name">Nama motor:</label>
        <input type="text" id="vehicle_name" name="vehicle_name" value="{{ old('vehicle_name') }}" required maxlength="100">

        <br>

        <label for="plate_number">KB motor:</label>
        <input type="text" id="plate_number" name="plate_number" value="{{ old('plate_number') }}" placeholder="cth: KB 1234 AB" required maxlength="20">

        <br>

        <label for="plate_password">Password buat KB:</label>
        <input type="text" id="plate_password" name="plate_password" required minlength="4" maxlength="50">

        <br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" @selected(old('status') === $value)>{{ $label }}</option>
            @endforeach
        </select>

        <br>

        <button type="submit">Simpan</button>
        <a href="{{ route('owner.vehicles.index') }}">Batal</a>
    </form>

</body>
</html>
