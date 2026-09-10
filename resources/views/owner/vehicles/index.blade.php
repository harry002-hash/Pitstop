<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Bengkel</title>
</head>
<body>

    <h1>Dashboard Bengkel (Ajung)</h1>
    <p>Login sebagai: {{ auth()->user()->username }}</p>

    <nav>
        <a href="{{ route('owner.vehicles.create') }}">+ Catat Motor Baru</a>
        <a href="{{ route('chat') }}">Chat Customer</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </nav>

    <hr>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Nama Motor</th>
                <th>KB Motor</th>
                <th>Pemilik (akun)</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($vehicles as $vehicle)
                <tr>
                    <td>{{ $vehicle->vehicle_name }}</td>
                    <td>{{ $vehicle->plate_number }}</td>
                    <td>{{ $vehicle->owner?->username ?? '— (belum diklaim)' }}</td>
                    <td>{{ $vehicle->statusLabel() }}</td>
                    <td>
                        <a href="{{ route('owner.vehicles.edit', $vehicle) }}">Edit / Status / Password</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Belum ada motor. Klik "Catat Motor Baru".</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
