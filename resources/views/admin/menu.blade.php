@extends('layouts.app')
@section('title', 'Edit Menu')

@section('logout')
<a href="javascript::logout();" style="float:right">Logout Admin</a>
@endsection

@section('content')
<h2>Admin Dashboard</h2>
<hr>
    <h3>Menu Management</h3>

    <div>
        <h3>Add Item</h3>
        <input type="text" id="name" placeholder="Item Name"><br>
        <input type="text" id="price" placeholder="Price"><br>
        <button onclick="addItem()">Add</button>
    </div>

    <hr>

    <div>
        <h3>Current Menu</h3>
        <ul id="menuList"></ul>
    </div>

    <script>
        const token = localStorage.getItem('admin_token');

        async function logout() {
            if (!token) return alert('Not logged in');

            const res = await fetch(`/api/admin/logout`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });
            const result = await res.json();
            alert(result.message);
            adminToken = null;
        }

        async function fetchMenu() {
            const res = await fetch('/api/menu', {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            const items = await res.json();

            const menuList = document.getElementById('menuList');
            menuList.innerHTML = '';
            items.forEach(item => {
                menuList.innerHTML += `
                    <li>
                        ${item.name} - Rs. ${item.price}
                        <button onclick="deleteItem(${item.id})">Delete</button>
                    </li>`;
            });
        }

        async function addItem() {
            const name = document.getElementById('name').value;
            const price = document.getElementById('price').value;

            const res = await fetch('/api/menu', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ name, price })
            });

            if (res.ok) {
                alert('Item added');
                fetchMenu();
            } else {
                alert('Failed to add item');
            }
        }

        async function deleteItem(id) {
            const res = await fetch(`/api/menu/${id}`, {
                method: 'DELETE',
                headers: { 'Authorization': `Bearer ${token}` }
            });

            if (res.ok) {
                alert('Item deleted');
                fetchMenu();
            } else {
                alert('Failed to delete');
            }
        }

        fetchMenu();
    </script>
@endsection
