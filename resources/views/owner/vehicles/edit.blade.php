<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Motor</title>
</head>
<body>

    <h1>Edit Motor: {{ $vehicle->plate_number }}</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('owner.vehicles.update', $vehicle) }}">
        @csrf
        @method('PUT')

        <label for="vehicle_name">Nama motor:</label>
        <input type="text" id="vehicle_name" name="vehicle_name" value="{{ old('vehicle_name', $vehicle->vehicle_name) }}" required maxlength="100">

        <br>

        <label for="plate_number">KB motor:</label>
        <input type="text" id="plate_number" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" required maxlength="20">

        <br>

        <label for="plate_password">Password baru (kosongkan kalau tidak diganti):</label>
        <input type="text" id="plate_password" name="plate_password" minlength="4" maxlength="50" autocomplete="off">

        <br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $vehicle->status) === $value)>{{ $label }}</option>
            @endforeach
        </select>

        <br>

        <button type="submit">Perbarui</button>
        <a href="{{ route('owner.vehicles.index') }}">Batal</a>
    </form>

</body>
</html>
