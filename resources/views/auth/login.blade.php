<form method="POST" action="/login">
    @csrf
    <input type="text" name="codename" placeholder="Codename" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
