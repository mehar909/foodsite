@extends('layouts.app')

@section('title', 'Register')

@section('content')
<h2>Customer SignUp</h2>

<form id="registerForm">
    <input type="text" name="name" placeholder="full name" required><br><br>

    <input type="email" name="email" placeholder="email@example.com" required><br><br>

    <input type="password" name="password" placeholder="password" required><br><br>

    <input type="password" name="password_confirmation" placeholder="confirm password" required><br><br>

    <input type="text" name="phone" placeholder="phone" required><br><br>

    <input type="text" name="address" placeholder="address" required><br>
<br>
    <button type="submit">SignUp</button>
</form>

<script>
document.getElementById('registerForm').onsubmit = async (e) => {
    e.preventDefault();
    const form = Object.fromEntries(new FormData(e.target));

    const response = await fetch('/api/customer/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(form)
    });

    const data = await response.json();

    if (response.ok) {
        localStorage.setItem('token', data.token);
        localStorage.setItem('role', 'customer');
        alert('Registration successful!');
        window.location.href = '/menu';
    } else {
        alert(data.message || 'Registration failed.');
    }
};
</script>
@endsection
