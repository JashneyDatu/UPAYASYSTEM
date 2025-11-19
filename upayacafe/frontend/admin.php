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
    unset($_SESSION['order']);

    // Redirect to orders page
    header("Location: orders.php");
    exit();
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
        <input type="text" placeholder="SEARCH FOR PRODUCT" id="search-box" />
      </div>

      <div class="category-tabs">
        <button><a href="admin.php"><h3>COFFEE</h3></a></button>
        <button><a href="admin1.php">PREMIUM MATCHA SERIES</a></button>
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
          <div class="item" data-nname="Macadamia Nut Latte" data-price="130">Macadamia Nut Latte - 130</div>
          <div class="item" data-name="Toasted Marshmallow Latte" data-price="135">Toasted Marshmallow Latte - 135</div>
        </div>

        <h3>SPECIAL COFFEE FLAVORS</h3>
        <div class="items">
          <div class="item" data-name="Sea Salt Latte" data-price="140">Sea Salt Latte - 140</div>
          <div class="item" data-name="Pumpkin Spice Latte" data-price="140">Pumpkin Spice Latte - 140</div>
          <div class="item" data-name="Choco Mint Latte" data-price="145">Choc*Nut Latte - 145</div>
          <div class="item" data-name="Biscoff Latte" data-price="145">Biscoff Latte - 165</div>
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


<style>
/* POPUP BACKGROUND */
.modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.6);
  display: flex;
  justify-content: center;
  align-items: center;
}

/* POPUP BOX */
.modal-content {
  background: white;
  width: 350px;
  padding: 20px;
  border-radius: 12px;
  text-align: center;
  font-family: 'Poppins', sans-serif;
}

.modal-content input {
  width: 90%;
  padding: 10px;
  margin-top: 15px;
  border-radius: 6px;
  border: 1px solid #bca58c;
}

.modal-buttons {
  margin-top: 20px;
  display: flex;
  justify-content: space-between;
}

.modal-buttons button {
  padding: 10px 18px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
}

#cancelVoid {
  background: #777;
  color: white;
}

#confirmVoid {
  background: #a83232;
  color: white;
}
</style>

<script>
// Open popup when clicking VOID button
document.querySelector(".void").addEventListener("click", function(e) {
    e.preventDefault();
    document.getElementById("voidModal").style.display = "flex";
});

// Close popup
document.getElementById("cancelVoid").addEventListener("click", function() {
    document.getElementById("voidModal").style.display = "none";
});

// Confirm password
document.getElementById("confirmVoid").addEventListener("click", function() {
    const enteredPass = document.getElementById("voidPassword").value;

    // ADMIN PASSWORD (can later be verified server-side)
    const correctPass = "admin123"; 

    if (enteredPass === correctPass) {
        document.getElementById("voidModal").style.display = "none";

        // CLEAR ORDER SUMMARY
        const orderSummary = document.querySelector(".order-summary .summary-box");
        orderSummary.innerHTML = "<p>Order has been voided.</p>";

        // OPTIONAL: Send void request to server to clear session/order
        fetch("void_order.php", { 
            method: "POST", 
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: "pin=" + encodeURIComponent(enteredPass)
        })
        .then(response => response.text())
        .then(data => console.log(data))
        .catch(err => console.error(err));

    } else {
        alert("Incorrect password!");
    }
});

// Close modal if user clicks outside the modal content
window.addEventListener("click", function(e) {
    const modal = document.getElementById("voidModal");
    if (e.target === modal) {
        modal.style.display = "none";
    }
});
</script>
</html>
