{{-- <form method="POST" action="{{ route('login.attempt') }}">
    @csrf
    @if($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <input type="name" name="name" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Masuk</button>
</form> --}}