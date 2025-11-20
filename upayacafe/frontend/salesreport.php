<?php
session_start();

// Load inventory
$inventoryFile = 'inventory.json';
$inventory = [];
if (file_exists($inventoryFile)) {
    $inventory = json_decode(file_get_contents($inventoryFile), true);
}

// Handle add new item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $name = $_POST['name'] ?? '';
    $price = floatval($_POST['price'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $expDate = $_POST['exp_date'] ?? '';

    if ($name !== '' && $price > 0 && $stock >= 0) {
        $inventory[] = [
            'name' => $name,
            'stock' => $stock,
            'price' => $price,
            'exp_date' => $expDate
        ];
        file_put_contents($inventoryFile, json_encode($inventory, JSON_PRETTY_PRINT));
    }
    header("Location: inventory.php");
    exit();
}

// Handle delete item
if (isset($_GET['delete']) && isset($inventory[$_GET['delete']])) {
    unset($inventory[$_GET['delete']]);
    $inventory = array_values($inventory);
    file_put_contents($inventoryFile, json_encode($inventory, JSON_PRETTY_PRINT));
    header("Location: inventory.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Upâyâ Café | Orders</title>
  <link rel="stylesheet" href="menu.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />

  <style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f2f2f2;
        margin: 0;
        padding: 0;
    }

    .orders-container {
        width: calc(100% - 80px);
        margin-left: 80px;
        padding: 25px;
    }

    .orders-title {
        font-size: 28px;
        margin-bottom: 20px;
        font-weight: 600;
        color: #3a2818;
    }

    /* TABLE WRAPPER */
    .orders-table-wrapper {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    thead {
        background: #3a2818;
        color: white;
    }

    th {
        padding: 14px 10px;
        text-align: left;
        font-weight: 500;
        font-size: 14px;
    }

    td {
        padding: 14px 10px;
        border-bottom: 1px solid #e6e6e6;
        font-size: 14px;
        color: #333;
    }

    tr:hover td {
        background: #f7f7f7;
    }

    .status-paid {
        background: #d4edda;
        color: #155724;
        padding: 6px 10px;
        font-size: 12px;
        border-radius: 6px;
        font-weight: 500;
    }

    .view-btn {
        padding: 8px 12px;
        background: #3a2818;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-size: 12px;
        transition: 0.2s;
    }

    .view-btn:hover {
        background: #3a2818;
    }

  </style>
</head>
<body>

<form action="signin.php" method="POST" style="display:inline;">
    <button class="logout-btn" type="submit">LOG OUT</button>
</form>

<div class="logo">
    <h1>Upâyâ</h1>
    <p>Café</p>
</div>

<div class="pos-container">

    <!-- Sidebar same colors -->
    <div class="sidebar">
        <a href="admin.php" class="icon active">🏠</a>
        <a href="orderhistory.php" class="icon active">📝</a>
        <a href="inventory.php" class="icon active">📦</a>
        <a href="salesreport.php" class="icon active">📊</a>
        <a href="settings.php" class="icon active">⚙️</a>
        <a href="logout.php" class="icon active">⬅️</a>
    </div>

    <div class="orders-container">
        <h2 class="orders-title">Orders Summary</h2>

        <div class="orders-table-wrapper">

    <div class="inventory-container">
        <h2 class="inventory-title">Inventory Summary</h2>

        <form class="add-form" action="inventory.php" method="POST">
            <input type="text" name="name" placeholder="Product Name" required>
            <input type="number" name="stock" placeholder="Quantity" min="0" required>
            <input type="number" step="0.01" name="price" placeholder="Price" min="0" required>
            <input type="date" name="exp_date" placeholder="Exp Date" required>
            <button type="submit" name="add_item">Add Item</button>
        </form>

        <div class="inventory-table-wrapper">
        <?php if (!empty($inventory)): ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Exp Date</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($inventory as $index => $item): ?>
                    <tr>
                        <td>#<?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= htmlspecialchars($item['exp_date']) ?></td>
                        <td><?= (int)$item['stock'] ?></td>
                        <td>₱<?= number_format($item['price'], 2) ?></td>
                        <td>
                            <?php if ($item['stock'] > 0): ?>
                                <span class="status-instock">In Stock</span>
                            <?php else: ?>
                                <span class="status-out">Out of Stock</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a class="action-btn" href="inventory.php?delete=<?= $index ?>" onclick="return confirm('Remove this item?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No inventory items found.</p>
        <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
