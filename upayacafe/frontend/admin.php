<?php
session_start();

// Handle checkout form submission
$checkoutMessage = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $paymentType = $_POST['paymentType'] ?? 'cash';
    $order = $_SESSION['order'] ?? [];

    $total = 0.0;
    foreach ($order as $item) {
        $total += $item['price'] * $item['qty'];
    }

    // Load existing orders
    $existingOrders = [];
    if (file_exists('orders.json')) {
        $existingOrders = json_decode(file_get_contents('orders.json'), true);
        if (!is_array($existingOrders)) {
            $existingOrders = [];
        }
    }

    // Add new order to list
    $existingOrders[] = [
        'order' => $order,
        'total' => $total,
        'payment' => $paymentType,
        'timestamp' => date('Y-m-d H:i:s')
    ];

    // Save back to file
    file_put_contents('orders.json', json_encode($existingOrders, JSON_PRETTY_PRINT));

    // Clear session
    // unset($_SESSION['order']);

    // Redirect to orders page
    header("Location: receipt.php");
    exit();
}
// Handle AJAX addItem requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addItem'])) {
    $name = $_POST['name'];
    $price = (float)$_POST['price'];
    $qty = 1;

    if (!isset($_SESSION['order'])) {
        $_SESSION['order'] = [];
    }

    $found = false;
    foreach ($_SESSION['order'] as &$item) {
        if ($item['name'] === $name) {
            $item['qty'] += $qty;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $_SESSION['order'][] = ['name' => $name, 'price' => $price, 'qty' => $qty];
    }

    header('Content-Type: application/json');
    echo json_encode($_SESSION['order']);
    exit();
}

// Handle Clear order button AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clearOrder'])) {
    unset($_SESSION['order']);
    header('Content-Type: application/json');
    echo json_encode([]);
    exit();
}

// Prepare order summary HTML for page load (without total)
$order = $_SESSION['order'] ?? [];
$orderItemsHtml = '';
if (!empty($order)) {
    foreach ($order as $item) {
        $subtotal = $item['price'] * $item['qty'];
        $orderItemsHtml .= "<div class='order-item'>".htmlspecialchars($item['name'])." - ₱{$subtotal}  x {$item['qty']}</div>";
    }
} else {
    $orderItemsHtml = "<p>No items added yet.</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Upâyâ Café | POS System</title>
  <link rel="stylesheet" href="menu.css" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600&family=Poppins:wght@400;500&display=swap" rel="stylesheet" />
</head>
<body>


  <div class="logo">
    <h1>Upâyâ</h1>
    <p>Café</p>
    
  </div>

    <div class="pos-container">
    <!-- Sidebar -->
    <div class="sidebar">
  <!-- Home Button -->
    <a href="admin.php" class="icon active">🏠</a>
    <a href="orderhistory.php" class="icon active">📝</a>
    <a href="inventory.php" class="icon active">📦</a>
    <a href="salesreport.php" class="icon active">📊</a>
    <a href="settings.php" class="icon active">⚙️</a>
    <a href="logout.php" class="icon active">⬅️</a>
    </div>
    <!-- Main Menu Section -->
    <div class="menu-section">
      <div class="search-bar">
        <input type="text" placeholder="SEARCH FOR PRODUCT" id="search-box" />
      </div>

      <div class="category-tabs">
        <button><a href="admin.php"><h3>COFFEE</h3></a></button>
        <button><a href="admin1.php">PREMIUM MATCHA SERIES</a></button>
        <button><a href="admin2.php">NON-COFFEE DRINKS</a></button>
        <button><a href="admin3.php">FRAPPE</a></button>
        <button><a href="admin4.php">FRUIT SODA</a></button>
        <button><a href="admin5.php">PREMIUM TEA SERIES</a></button>
        <button><a href="admin6.php">COOKIES & MUFFINS</a></button>
        <button><a href="admin7.php">WAFFLES</a></button>
        <button><a href="admin8.php">FLAVORED FRIES</a></button>
        <button><a href="admin9.php">PASTA</a></button>
        <button><a href="admin10.php">ADD-ONS</a></button>
      </div>

      <div class="product-grid" id="product-grid">
        <h3>ESPRESSO</h3>
        <div class="items">
          <div class="item" data-name="Americano" data-price="110">Americano - 110</div>
          <div class="item" data-name="Cafe Latte" data-price="120">Cafe Latte - 120</div>
          <div class="item" data-name="Caramel Macchiato" data-price="135">Caramel Macchiato - 135</div>
          <div class="item" data-name="Iced Mocha" data-price="125">Iced Mocha - 125</div>
          <div class="item" data-name="White Chocolate Mocha" data-price="135">White Chocolate Mocha - 135</div>
          <div class="item" data-name="Salted Caramel Latte" data-price="135">Salted Caramel Latte - 135</div>
          <div class="item" data-name="Spanish Latte" data-price="130">Spanish Latte - 130</div>
          <div class="item" data-name="Hazelnut Latte" data-price="130">Hazelnut Latte - 130</div>
          <div class="item" data-name="French Vanilla Latte" data-price="110">French Vanilla Latte - 110</div>
          <div class="item" data-name="English Toffee Latte" data-price="120">English Toffee Latte - 120</div>
          <div class="item" data-name="Short Bread Cookie Latte" data-price="130">Short Bread Cookie Latte - 130</div>
        </div>

        <h3>MUST-TRY COFFEE FLAVORS</h3>
        <div class="items">
          <div class="item" data-name="Butterscotch Latte" data-price="135">Butterscotch Latte - 135</div>
          <div class="item" data-name="Pumpkin Spice Latte" data-price="130">Roasted Almond Latte - 130</div>
          <div class="item" data-name="Macadamia Nut Latte" data-price="130">Macadamia Nut Latte - 130</div>
          <div class="item" data-name="Toasted Marshmallow Latte" data-price="135">Toasted Marshmallow Latte - 135</div>
        </div>

        <h3>SPECIAL COFFEE FLAVORS</h3>
        <div class="items">
          <div class="item" data-name="Sea Salt Latte" data-price="140">Sea Salt Latte - 140</div>
          <div class="item" data-name="Pumpkin Spice Latte" data-price="140">Pumpkin Spice Latte - 140</div>
          <div class="item" data-name="Choco Mint Latte" data-price="145">Choc*Nut Latte - 145</div>
          <div class="item" data-name="Biscoff Latte" data-price="145">Biscoff Latte - 145</div>
        </div>
      </div>
    </div>

    <!-- Order Summary -->
    <div class="order-summary">
      
      <h3>Order Summary</h3>
      <div class="summary-box" id="order-summary-box"><?= $orderItemsHtml ?></div>

       <div class="checkout-row">
      <button class="clear">Clear</button>
      <button class="void">Void</button>
      <a href="checkout.php" class="checkout" style="display:inline-block;text-decoration:none;">
  <button type="button" class="checkout">CHECKOUT ORDER</button>
</a>

    </div>  
  </div>

<script src="pos.js"></script>
<!-- VOID PASSWORD POPUP -->
<div id="voidModal" class="modal">
  <div class="modal-content">
      <h3>Admin Authorization Required</h3>
      <p>Enter admin password to continue:</p>

      <input type="password" id="voidPassword" placeholder="Enter password">

      <div class="modal-buttons">
          <button id="cancelVoid">Cancel</button>
          <button id="confirmVoid">Confirm</button>
      </div>
  </div>
</div>

</body>
</html>
