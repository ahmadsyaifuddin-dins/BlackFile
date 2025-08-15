<form method="POST" action="/register">
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong>Whoops!</strong>
        <span class="block sm:inline">{{ $errors->first() }}</span>
    </div>
    @endif
    @csrf
    <input type="text" name="name" placeholder="Nama" required>
    <input type="text" name="username" placeholder="Username" required>
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