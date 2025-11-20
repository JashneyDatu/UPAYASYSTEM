<?php
session_start();
include "db/db_connect.php";

// Use INVENTORY DB (because product table belongs here)
$conn = $conn_inventory;

// Default SQL
$sql = "SELECT * FROM product";
$search = "";
$filter = "";


// Search handler
if (isset($_GET['search']) && isset($_GET['filter'])) {
    $search = $_GET['search'];
    $filter = $_GET['filter'];

    if (!empty($search) && !empty($filter)) {
        $sql = "SELECT * FROM product WHERE $filter LIKE '%$search%'";
    }
}

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Upâyâ Café | Settings</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="pos.css">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background-color: #3a2818;
    margin: 0;
}

/* PAGE CONTAINER */
.settings-container {
    width: calc(100% - 80px);
    margin-left: 80px;
    padding: 25px;
}

/* TITLE */
.settings-title {
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #f5e9dd;
}

/* WHITE CARD */
.settings-wrapper {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

thead {
    background: #3a2818;
    color: white;
}

th, td {
    padding: 12px;
    border-bottom: 1px solid #d8c2a8;
    font-size: 14px;
}

tr:hover td {
    background: #c7a989;
}

/* BUTTONS */
.btn-add, .btn-search {
    background: #3a2818;
    color: white;
    padding: 10px 14px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
}

.btn-reset {
    background: #777;
    color: white;
    padding: 10px 14px;
    border-radius: 6px;
    border: none;
}

.btn-edit {
    background: #c58c2c;
    color: white;
    padding: 6px 10px;
    border-radius: 6px;
    text-decoration: none;
}

.btn-delete {
    background: #a83232;
    color: white;
    padding: 6px 10px;
    border-radius: 6px;
    text-decoration: none;
}
</style>
</head>
<body>

<!-- LOGOUT BUTTON -->
<form action="signin.php" method="POST" style="display:inline;">
    <button class="logout-btn" type="submit">LOG OUT</button>
</form>

<!-- LOGO -->
<div class="logo">
    <h1>Upâyâ</h1>
    <p>Café</p>
</div>

<div class="pos-container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <a href="admin.php" class="icon active">🏠</a>
        <a href="orderhistory.php" class="icon active">📝</a>
        <a href="inventory.php" class="icon active">📦</a>
        <a href="salesreport.php" class="icon active">📊</a>
        <a href="settings.php" class="icon active">⚙️</a>
        <a href="logout.php" class="icon active">⬅️</a>
    </div>

    <!-- CONTENT -->
    <div class="settings-container">
        <h2 class="settings-title">Product Settings</h2>

        <div class="settings-wrapper">

            <!-- SEARCH + FILTER -->
            <form method="GET" class="row" style="display:flex; gap:10px; margin-bottom:20px;">
                <input 
                    type="text" 
                    name="search" 
                    value="<?= htmlspecialchars($search) ?>" 
                    placeholder="Search product..." 
                    style="flex:1; padding:10px; border-radius:6px; border:1px solid #bca58c;"
                >

                <select name="filter" 
                    style="padding:10px; border-radius:6px; border:1px solid #bca58c;">
                    <option value="">Filter</option>
                    <option value="product_code" <?= ($filter=="product_code")?"selected":"" ?>>Product Code</option>
                    <option value="product_name" <?= ($filter=="product_name")?"selected":"" ?>>Product Name</option>
                    <option value="category" <?= ($filter=="category")?"selected":"" ?>>Category</option>
                    <option value="qty" <?= ($filter=="qty")?"selected":"" ?>>Quantity</option>
                </select>

                <button type="submit" class="btn-search">Search</button>
                <a href="settings.php" class="btn-reset">Reset</a>
            </form>

            <!-- ADD BUTTON -->
            <a href="create.php" class="btn-add">+ Add New Product</a>

            <!-- PRODUCT TABLE -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Qty</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                if ($result === false) {
                    echo "<tr><td colspan='6' style='color:red;'>SQL Error: " . $conn->error . "</td></tr>";
                } else {
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['product_code']}</td>
                                <td>{$row['product_name']}</td>
                                <td>{$row['category']}</td>
                                <td>{$row['qty']}</td>
                                <td>
                                    <a class='btn-edit' href='edit.php?id={$row['id']}'>Edit</a>
                                    <a class='btn-delete' href='delete.php?id={$row['id']}' onclick=\"return confirm('Delete this product?');\">Delete</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align:center; color:#555;'>No products found</td></tr>";
                    }
                }
                ?>
                </tbody>
            </table>

        </div>
    </div>

</div>

</body>
</html>
