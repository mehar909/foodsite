<!-- resources/views/menu.blade.php -->
@extends('layouts.app')

@section('title', 'Menu')

@section('content')
    <h2>📋 Menu</h2>
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Price (Rs)</th>
                <th>Description</th>
                <th id="qtyHeader" style="display:none;">Quantity</th>
                <th id="adminHeader" style="display:none;">Add/Delete</th>
            </tr>
        </thead>
        <tbody id="menuTable">
            <!-- Menu items will be rendered here -->
        </tbody>
    </table>

    <hr>

    <div id="orderSection" style="display:none;">
        <!-- <h3>Place Order</h3> -->
        <button id="placeOrderBtn" style="float-right">Place Order</button>
    </div>

    <script>
        const api = '/api';
        const token = localStorage.getItem('token');
        const role = localStorage.getItem('role');
        async function loadMenu() {
            const res = await fetch(`${api}/menu`);
            const data = await res.json();
            const table = document.getElementById('menuTable');
            table.innerHTML = '';

            // If menu is empty
            if (data.length === 0) {
                const row = document.createElement('tr');
                if (role === 'admin' && token) {
                    // Empty table but admin logged in
                    row.innerHTML = `
                    <td colspan="5" style="text-align:center; font-style:italic;">
                        No menu items yet. Add a new item below.
                    </td>`;
                } else {
                    // Empty table for customers/public
                    row.innerHTML = `
                    <td colspan="5" style="text-align:center; font-style:italic;">
                        Menu is being updated, will be available soon.
                    </td>`;
                }
                table.appendChild(row);
            }

            // Populate table if data exists
            data.forEach(item => {
                if (role !== 'admin' && !item.is_available) return; // Skip unavailable items
                const row = document.createElement('tr');
                row.innerHTML = `
                <td>${item.name}</td>
                <td>${item.price}</td>
                <td>${item.description}</td>
            `;

                if (role === 'customer' && token) {
                    const qtyCell = document.createElement('td');
                    qtyCell.innerHTML = `
                    <button onclick="decreaseQty(${item.id})">-</button>
                    <input type="number" id="qty-${item.id}" value="0" min="0" style="width: 40px; text-align: center;" readonly>
                    <button onclick="increaseQty(${item.id})">+</button>
                `;
                    row.appendChild(qtyCell);
                } else if (role === 'admin' && token) {
                    const actionCell = document.createElement('td');
                    actionCell.innerHTML = `
                    <button onclick="toggleAvailability(${item.id}, ${item.is_available})" style="color:${item.is_available ? 'green' : 'red'}">Toggle Availability</button>
                    <button onclick="deleteItem(${item.id})" style="color:red;">Delete</button>
                    `;
                    row.appendChild(actionCell);
                }

                table.appendChild(row);
            });

            if (role === 'admin' && token) {
                const addRow = document.createElement('tr');
                addRow.innerHTML = `
                <td><input type="text" id="new-name" placeholder="Item name"></td>
                <td><input type="number" id="new-price" placeholder="Price"></td>
                <td><input type="text" id="new-desc" placeholder="Description"></td>
                <td colspan="2"><button onclick="addItem()">Add</button></td>
            `;
                table.appendChild(addRow);
                document.getElementById('adminHeader').style.display = 'table-cell';
            }

            if (role === 'customer' && token) {
                document.getElementById('qtyHeader').style.display = 'table-cell';
                document.getElementById('orderSection').style.display = 'block';
            }
        }


        function increaseQty(id) {
            const input = document.getElementById(`qty-${id}`);
            input.value = parseInt(input.value) + 1;
        }

        function decreaseQty(id) {
            const input = document.getElementById(`qty-${id}`);
            const value = parseInt(input.value);
            if (value > 0) input.value = value - 1;
        }

        document.getElementById('placeOrderBtn').onclick = async () => {
            const rows = document.querySelectorAll('#menuTable tr');
            const items = [];

            rows.forEach(row => {
                const idAttr = row.querySelector('input')?.id;
                if (idAttr) {
                    const id = parseInt(idAttr.split('-')[1]);
                    const qty = parseInt(document.getElementById(`qty-${id}`).value);
                    if (qty > 0) items.push({ menu_item_id: id, quantity: qty });
                }
            });

            if (items.length === 0) return alert('Please select at least one item.');

            const res = await fetch(`${api}/orders`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ items })
            });

            const result = await res.json();
            if (res.ok) {
                alert('Order Placed Successfully!');
                loadMenu();
            } else {
                alert(result.message || 'Failed to place order.');
            }
        };

        async function toggleAvailability(id, isAvailable) {
             if (!confirm('Mark this item as ' + (isAvailable ? 'Unavailable' : 'Available') + '?')) return;
            const res = await fetch(`${api}/menu/${id}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });

            const data = await res.json();
            alert(data.message);
            loadMenu(); // reload menu
        }

        async function deleteItem(id) {
            if (!confirm('Delete this item?')) return;
            const res = await fetch(`${api}/menu/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });
            const result = await res.json();
            alert(result.message || 'Deleted');
            loadMenu();
        }

        async function addItem() {
            const name = document.getElementById('new-name').value.trim();
            const price = parseFloat(document.getElementById('new-price').value);
            const description = document.getElementById('new-desc').value.trim();

            if (!name || isNaN(price) || !description) {
                alert('Please fill all fields correctly.');
                return;
            }

            const res = await fetch(`${api}/menu`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ name, price, description })
            });

            const result = await res.json();
            alert(result.message || 'Added');
            loadMenu();
        }

        loadMenu();
    </script>
@endsection