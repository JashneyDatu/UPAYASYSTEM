<?php
session_start();

// Handle checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
  $paymentType = $_POST['paymentType'];
  $order = $_SESSION['order'] ?? [];

  $total = array_sum(array_column($order, 'price'));

  file_put_contents('orders.json', json_encode([
    'order' => $order,
    'total' => $total,
    'payment' => $paymentType,
    'timestamp' => date('Y-m-d H:i:s')
  ], JSON_PRETTY_PRINT));

  unset($_SESSION['order']);
  $checkoutMessage = "Order paid. Total: ₱{$total}";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upâyâ Café | POS System</title>
  <link rel="stylesheet" href="pos.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>

<script>
document.querySelectorAll('.item').forEach(item => {
  item.addEventListener('click', () => {
    const [name, price] = item.textContent.split(' - ');
    fetch('api/add-to-order.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: `item=${name}&price=${price}`
    }).then(res => res.json()).then(data => {
      console.log('Item added:', data);
    });
  });
});

document.querySelector('.checkout').addEventListener('click', () => {
  fetch('api/process-payment.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'paymentType=cash'
  }).then(res => res.json()).then(data => {
    alert(`Order paid. Total: ₱${data.total}`);
  });
});
</script>

  <div class="logo">
    <h1>Upâyâ</h1>
    <p>Café</p>
  </div>

  <div class="pos-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <a href="pos.php" class="icon active">🏠</a>
    
    </div>

    <!-- Main Menu Section -->
    <div class="menu-section">
      <div class="search-bar">
        <input type="text" placeholder="SEARCH FOR PRODUCT">
      </div>

      <div class="category-tabs">
        <button><a href="pos.php"><h3>COFFEE</h3></a></button>
        <button><a href="pos1.php">PREMIUM MATCHA SERIES</a></button>
        <button><a href="pos2.php">NON-COFFEE DRINKS</a></button>
        <button><a href="pos3.php">FRAPPE</a></button>
        <button><a href="pos4.php">FRUIT SODA</a></button>
        <button><a href="pos5.php">PREMIUM TEA SERIES</a></button>
        <button><a href="pos6.php">ADD-ONS</a></button>
        <button><a href="pos7.php">COOKIES & MUFFINS</a></button>
        <button><a href="pos8.php">WAFFLES</a></button>
        <button><a href="pos9.php">FLAVORED FRIES</a></button>
        <button><a href="pos10.php">PASTA</a></button>
      </div>

      <div class="product-grid">
        <h3>ESPRESSO</h3>
        <div class="items">
          <div class="item">Americano - 110</div>
          <div class="item">Cafe Latte - 120</div>
          <div class="item">Caramel Macchiato - 135</div>
          <div class="item">Iced Mocha - 125</div>
          <div class="item">White Chocolate Mocha - 135</div>
          <div class="item">Salted Caramel Latte - 135</div>
          <div class="item">Spanish Latte - 130</div>
          <div class="item">Hazelnut Latte - 130</div>
          <div class="item">French Vanilla Latte - 110</div>
          <div class="item">English Toffee Latte - 120</div>
          <div class="item">Short Bread Cookie Latte - 130</div>
        </div>

        <h3>MUST-TRY COFFEE FLAVORS</h3>
        <div class="items">
          <div class="item">Roasted Almond Latte - 130</div>
          <div class="item">Macadamia Nut Latte - 130</div>
          <div class="item">Toasted Marshmallow Latte - 135</div>
          <div class="item">Butterscotch Latte - 135</div>
        </div>

        <h3>SPECIAL COFFEE FLAVORS</h3>
        <div class="items">
          <div class="item">Sea Salt Latte - 140</div>
          <div class="item">Pumpkin Spice Latte - 140</div>
          <div class="item">Choco Mint Latte - 145</div>
          <div class="item">Biscoff Latte - 145</div>
        </div>
      </div>
    </div>

   <!-- Order Summary -->
   <div class="order-summary">
  <h3>Order Summary</h3>
  <div class="summary-box" id="order-summary-box">
    <p> </p>
  </div>  
  
    <div class="checkout-row">
      <button class="clear">Clear</button>
      <button class="void">Void</button>
      <form action="receipt.php" method="POST">
      <button class="checkout">CHECKOUT ORDER</button>
      </form>
    </div>  
      </div>
    </div>
  </div>
</body>
</php>