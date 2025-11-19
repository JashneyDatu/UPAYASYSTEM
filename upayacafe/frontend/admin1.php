<?php
session_start();

// Handle checkout form submission
$checkoutMessage = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    // Optionally get payment type from POST if exists
    $paymentType = $_POST['paymentType'] ?? 'cash';

    // Order array from session or empty
    $order = $_SESSION['order'] ?? [];

    // Compute total price
    $total = 0.0;
    foreach ($order as $item) {
        $total += $item['price'] * $item['qty'];
    }

    // Save order to file (or you can save to DB)
    file_put_contents('orders.json', json_encode([
        'order' => $order,
        'total' => $total,
        'payment' => $paymentType,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_PRETTY_PRINT));

    // Clear session order after checkout
    unset($_SESSION['order']);

    $checkoutMessage = "Order paid. Total: ₱" . number_format($total, 2);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Upâyâ Café | POS System</title>
  <link rel="stylesheet" href="pos.css" />
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
    <a href="admin.php" 
     class="icon <?= isset($activePage) && $activePage == 'admin.php' ? 'active' : '' ?>">🏠</a>
    <a href="orders.php" 
     class="icon <?= isset($activePage) && $activePage == 'orders.php' ? 'active' : '' ?>">📦</a>
    <a href="inventory.php"
   class="icon <?= isset($activePage) && $activePage == 'inventory.php' ? 'active' : '' ?>">📊</a> 
    <a href="settings.php"
   class="icon <?= isset($activePage) && $activePage == 'settings.php' ? 'active' : '' ?>">⚙️</a> 
   <a href="logout.php"
   class="icon <?= isset($activePage) && $activePage == 'settings.php' ? 'active' : '' ?>">⬅️</a> 

    </div>
       <!-- Main Menu Section -->
    <div class="menu-section">
      <div class="search-bar">
        <input type="text" placeholder="SEARCH FOR PRODUCT">
      </div>

      <div class="category-tabs">
        <button><a href="admin.php">COFFEE</a></button>
        <button><a href="admin1.php"><h3>PREMIUM MATCHA SERIES</h3></a></button>
        <button><a href="admin2.php">NON-COFFEE DRINKS</a></button>
        <button><a href="admin3.php">FRAPPE</a></button>
        <button><a href="admin4.php">FRUIT SODA</a></button>
        <button><a href="admin5.php">PREMIUM TEA SERIES</a></button>
        <button><a href="admin6.php">ADD-ONS</a></button>
        <button><a href="admin7.php">COOKIES & MUFFINS</a></button>
        <button><a href="admin8.php">WAFFLES</a></button>
        <button><a href="admin9.php">FLAVORED FRIES</a></button>
        <button><a href="admin10.php">PASTA</a></button>
      </div>

      <div class="product-grid" id="product-grid">
        <h3>PREMIUM MATCHA SERIES</h3>
        <div class="items">
          <div class="item" data-name="Matcha Latte" data-price="145">Matcha Latte - 145</div>
          <div class="item" data-name="Strawberry Matcha Latte" data-price="160">Strawberry Matcha Latte - 160</div>
          <div class="item" data-name="Blueberry Matcha Latte" data-price="170">Blueberry Matcha Latte - 170</div>
          <div class="item" data-name="Salted Matcha Latte" data-price="160">Salted Matcha Latte - 160</div>
          <div class="item" data-name="Banana Matcha Latte" data-price="160">Banana Matcha Latte - 160</div>
          <div class="item" data-name="Pure Matcha Latte" data-price="160">Pure Matcha Latte - 170</div>
          <div class="item" data-name="Creamy Matcha Latte" data-price="160">Creamy Matcha Latte - 160</div>
          <div class="item" data-name="Peach Matcha Latte" data-price="160">Peach Matcha Latte - 165</div>
          <div class="item" data-name="Dirty Matcha Latte" data-price="160">Dirty Matcha Latte - 130</div>
          <div class="item" data-name="Peach Mango Matcha Latte" data-price="160">Peach Mango Matcha Latte - 155</div>
          <div class="item" data-name="Strawberry Matcha Latte" data-price="160">Mango Matcha Latte - 160</div>
          <div class="item">Biscoff Matcha Latte - 180</div>
          <div class="item">Hazelnut Matcha Latte - 160</div>
          <div class="item">White Choco Matcha Latte - 120</div>
          <div class="item">Vanilla Matcha Latte - 160</div>
        </div>
      </div>
    </div>


    <!-- Order Summary -->
    <div class="order-summary">
      
      <h3>Order Summary</h3>
      <div class="summary-box" id="order-summary-box">
        <p>No items added yet.</p>
      </div>

      <div class="checkout-row">
      <button class="clear">Clear</button>
      <button class="void">Void</button>
      <form action="receipt.php" method="POST">
      <button class="checkout">CHECKOUT ORDER</button>
      </form>
    </div>  
  </div>

<script src="pos.js"></script>


</body>
</html>
