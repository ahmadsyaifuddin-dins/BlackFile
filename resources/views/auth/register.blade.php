<form method="POST" action="/register">
    @csrf
    <input type="text" name="name" placeholder="Nama" required>
    <input type="text" name="codename" placeholder="Codename" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>

    <select name="role_id" required>
        @foreach(\App\Models\Role::all() as $role)
            <option value="{{ $role->id }}">{{ $role->alias }}</option>
        @endforeach
    </select>

    <button type="submit">Daftar</button>
</form>
