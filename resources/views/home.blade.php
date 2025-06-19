@extends('layouts.app')
@section('title', 'Welcome')

@section('content') 
            <div style="text-align: center; padding: 40px;">
        <h1>🍴 Welcome to FoodSite</h1>
        <p style="font-size: 18px; margin-top: 10px;">
            Order your favorite meals from our restaurant – fast, fresh, and easy!
        </p>

            <a href="/menu">
                <button style="margin-top: 20px; padding: 10px 25px; font-size: 16px;">
                    Go to Menu
                </button>
            </a>
            <div style="margin-top: 30px;">
                <a href="/login">
                    <button style="padding: 10px 25px; margin-right: 10px;">Login</button>
                </a>
                <a href="/register">
                    <button style="padding: 10px 25px;">Sign Up</button>
                </a>
            </div>


        <hr style="margin: 50px auto; width: 60%;">

        <div>
            <h3>🔥 Why choose us?</h3>
            <ul style="list-style: none; padding: 0; font-size: 16px; margin-top: 20px;">
                <li>✅ Fresh & delicious meals</li>
                <li>🚚 Fast delivery</li>
                <li>💳 Easy online ordering</li>
                <li>📱 Mobile-friendly experience</li>
            </ul>
        </div>
    </div>
@endsection
<script>
    async function checkLogin() {
        const token = localStorage.getItem('token');
        if (!token) return false;
        else return true;
    }
</script>
