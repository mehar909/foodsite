@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<h2>🍽️ My Orders</h2>

<div id="ordersContainer">
    <div id="currentOrder"></div>
    <h3>Previous Orders</h3>
    <div id="previousOrders"></div>
</div>

<script>
    const api = '/api';
    const token = localStorage.getItem('token');
    document.getElementById('ordersBtn').style.display = 'inline';
    async function loadOrders() {
        const res = await fetch(`${api}/orders`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        const orders = await res.json();
        let currentOrderHtml = '';
        let previousOrdersHtml = '';

        const currentOrders = orders.filter(order => order.status === 'pending' || order.status === 'confirmed');
        const previousOrders = orders.filter(order => order.status === 'delivered' || order.status === 'cancelled');

        //Render Current Orders
        if (currentOrders.length > 0) {
            currentOrderHtml += `<h3>Current Order(s)</h3>`;
            currentOrders.forEach(order => {
                const orderTableRows = order.items.map(item => {
                    const itemName = item.menu_item?.name || 'Unknown';
                    const itemId = item.menu_item?.id || 'N/A';
                    const qty = item.quantity;
                    return `<tr><td>${itemId}</td><td>${itemName}</td><td>${qty}</td></tr>`;
                }).join('');

                currentOrderHtml += `
                    <div style="border:1px solid #00aaff; padding:10px; margin-bottom:10px; background:#e6f7ff;">
                        <strong>Order #${order.id}</strong> <br><br>
                        <span>Status: ${order.status}</span><br>
                        <table border="1" cellpadding="5" cellspacing="0" style="margin-top:10px; width:100%">
                            <thead><tr><th>Item ID</th><th>Name</th><th>Quantity</th></tr></thead>
                            <tbody>${orderTableRows}</tbody>
                        </table>
                        <small>Placed at: ${order.created_at}</small><br><br>
                        <button onclick="cancelOrder(${order.id})" style="border-radius:4px; solid #000000; background:#ff474c;">Cancel Order</button>
                    </div>
                `;
                
            });
        } else {
            currentOrderHtml = `<p>No current orders.</p>`;
        }

        //Render Previous Orders
        if (previousOrders.length > 0) {
            previousOrders.forEach(order => {
                const orderTableRows = order.items.map(item => {
                    const itemName = item.menu_item?.name || 'Unknown';
                    const itemId = item.menu_item?.id || 'N/A';
                    const qty = item.quantity;
                    return `<tr><td>${itemId}</td><td>${itemName}</td><td>${qty}</td></tr>`;
                }).join('');

                previousOrdersHtml += `
                    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px; background:${order.status === 'cancelled'? 'lightcoral' : 'delivered'? 'lightgreen': 'transparent'};">
                        <strong>Order #${order.id}</strong><br><br>
                        <span>Status: ${order.status}</span><br>
                        <table border="1" cellpadding="5" cellspacing="0" style="margin-top:10px; width:100%">
                            <thead><tr><th>Item ID</th><th>Name</th><th>Quantity</th></tr></thead>
                            <tbody>${orderTableRows}</tbody>
                        </table>
                        <small>Placed at: ${order.created_at}</small>
                    </div>
                `;
            });
        } else {
            previousOrdersHtml = `<p>No previous orders.</p>`;
        }

        document.getElementById('currentOrder').innerHTML = currentOrderHtml;
        document.getElementById('previousOrders').innerHTML = previousOrdersHtml;
    }

    loadOrders();

    async function cancelOrder(orderId) {
    if (!confirm('Are you sure you want to cancel this order?')) return;

    const res = await fetch(`/api/orders/${orderId}`, {
        method: 'PUT',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ status: 'cancelled' })
    });

    const data = await res.json();
    alert(data.message || 'Cancelled');
    loadOrders(); // Reload orders
}

</script>
@endsection
