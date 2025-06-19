@extends('layouts.app')

@section('title', 'Admin - Manage Orders')

@section('content')
<h2>🛎️ Manage All Orders</h2>

<div id="ordersContainer">
    <div id="allOrders"></div>
</div>

<script>
    const api = '/api';
    const token = localStorage.getItem('token');
    const role = localStorage.getItem('role');
    async function loadOrders() {
        const res = await fetch(`${api}/admin/orders`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        const orders = await res.json();
        let allOrdersHtml = '';

        orders.forEach(order => {
            const orderTableRows = order.items.map(item => {
                const itemName = item.menu_item?.name || 'Unknown';
                const itemId = item.menu_item?.id || 'N/A';
                const qty = item.quantity;
                return `<tr><td>${itemId}</td><td>${itemName}</td><td>${qty}</td></tr>`;
            }).join('');

            const statusOptions = ['pending', 'confirmed', 'delivered', 'cancelled']
                .map(status => `<option value="${status}" ${status === order.status ? 'selected' : ''}>${status}</option>`) 
                .join('');

            allOrdersHtml += `
                <div style="border:1px solid #aaa; padding:10px; margin-bottom:15px; background:#f8f8f8;">
                    <strong>Order #${order.id}</strong><br><br>
                    <span>Customer ID: ${order.customer_id}</span><br>
                    <label>Status:</label>
                    <select id="status-${order.id}">
                        ${statusOptions}
                    </select>
                    <button onclick="updateOrderStatus(${order.id})">Update</button>
                    <br><br>
                    <table border="1" cellpadding="5" cellspacing="0" style="margin-top:10px; width:100%">
                        <thead><tr><th>Item ID</th><th>Name</th><th>Quantity</th></tr></thead>
                        <tbody>${orderTableRows}</tbody>
                    </table>
                    <small>Placed at: ${order.created_at}</small>
                </div>
            `;
        });

        document.getElementById('allOrders').innerHTML = allOrdersHtml;
    }

    async function updateOrderStatus(orderId) {
        if (!confirm('Are you sure you want to update this order?')) return;
        const newStatus = document.getElementById(`status-${orderId}`).value;

        const res = await fetch(`/api/admin/orders/${orderId}`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        });

        const data = await res.json();
        alert(data.message || 'Status updated');
        loadOrders();
    }

    loadOrders();
</script>
@endsection
