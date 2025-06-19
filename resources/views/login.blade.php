@extends('layouts.app')

@section('title', 'Login')

@section('content')
<h2>Login</h2>

<form id="loginForm">
    <input type="text" name="email" placeholder="email" required><br><br>
    <input type="password" name="password" placeholder="password" required><br><br>
    <select id="roleSelect">
    <option value="customer">Customer</option>
    <option value="admin">Admin</option>
    </select>
    <button type="submit">Login</button>
</form>

<script>
document.getElementById('loginForm').onsubmit = async (e) => {
    e.preventDefault();

    const role = document.getElementById('roleSelect').value;
    const form = Object.fromEntries(new FormData(e.target));
    
    // Adjust field if admin login
    if (role === 'admin') {
        form.username = form.email;
        delete form.email;
    }

    const response = await fetch(`/api/${role}/login`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(form)
    });

    const data = await response.json();
    
    if (response.ok) {
        localStorage.setItem('token', data.token);
        localStorage.setItem('role', role);
        alert('Logged in!');
        window.location.href = role === 'admin' ? '/admin/orders' : '/menu';
    } else {
        alert(data.message || 'Login failed.');
    }
};
</script>
@endsection
