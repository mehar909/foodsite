<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script>
        window.token = localStorage.getItem('token');
        window.role = localStorage.getItem('role');
    </script>

    <title>@yield('title', 'FoodSite')</title>
</head>

<body style="text-align:center;">
    <header
        style="position:fixed; top:0; width: 98.6%; text-align:center; border:1px solid #00aaff; background:#e6f7ff;">
        <h1>🍔 FoodSite</h1>
        <hr>
        <nav>
            <button onclick="window.location.href='/'">Home</button>
            <button id="menuBtn" onclick="window.location.href='/menu'">Menu</button>
            <button id="loginBtn" onclick="window.location.href='/login'">Login</button>
            <button id="registerBtn" onclick="window.location.href='/register'">Sign Up</button>
            <button id="ordersBtn" onclick="window.location.href='/orders'" style="display:none;">My Orders</button>
            <button id="adminOrdersBtn" onclick="window.location.href='/admin/orders'"
                style="display:none;">Dashboard</button>
            <button id="logoutBtn" style="display:none;">Logout</button>
        </nav>
        <hr>
    </header>

    <main style="padding-top:120px; padding-bottom: 40px;">
        @yield('content')
    </main>

    <script>
        

        // Simple logout
        document.getElementById('logoutBtn').onclick = async function () {
            const token = window.token;
        const role = window.role;
            if (!token || !role) return alert("Not logged in.");

            const res = await fetch(`/api/${role}/logout`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });
            alert('Logged out.');
            localStorage.clear();
            location.href = '/';
        };

        // Show logout if token exists
        if (localStorage.getItem('token') || localStorage.getItem('admin_token')) {
            document.getElementById('logoutBtn').style.display = 'inline';
            document.getElementById('loginBtn').style.display = 'none';
            document.getElementById('registerBtn').style.display = 'none';
        }
        if (role === "admin" && token) {
            document.getElementById('logoutBtn').textContent = 'Logout Admin';
            document.getElementById('adminOrdersBtn').style.display = 'inline';
        }
        else if (role === "customer" && token) {
            document.getElementById('logoutBtn').textContent = 'Logout';
            document.getElementById('ordersBtn').style.display = 'inline';
        }
    </script>
    <footer style="position:fixed ; bottom:0; width:100%; text-align:center; border:1px solid #00aaff; background:#e6f7ff;">
        <hr>
        <p>&copy; 2025 FoodSite. All rights reserved.</p>
    </footer>
</body>

</html>