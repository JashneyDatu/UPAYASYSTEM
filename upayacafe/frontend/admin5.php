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
        $orderItemsHtml .= "<div class='order-item'>".htmlspecialchars($item['name'])." x{$item['qty']} - ₱{$subtotal}</div>";
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
      <button><a href="admin.php">COFFEE</a></button>
      <button><a href="admin1.php">PREMIUM MATCHA SERIES</a></button>
      <button><a href="admin2.php">NON-COFFEE DRINKS</a></button>
      <button><a href="admin3.php">FRAPPE</a></button>
      <button><a href="admin4.php">FRUIT SODA</a></button>
      <button class="active"><a href="admin5.php"><h3>PREMIUM TEA SERIES</h3></a></button>
      <button><a href="admin6.php">COOKIES & MUFFINS</a></button>
      <button><a href="admin7.php">WAFFLES</a></button>
      <button><a href="admin8.php">FLAVORED FRIES</a></button>
      <button><a href="admin9.php">PASTA</a></button>
      <button><a href="admin10.php">ADD-ONS</a></button>
    </div>

    <div class="product-grid" id="product-grid">
      <h3>PREMIUM TEA SERIES</h3>
      <div class="items">
        <div class="item" data-name="Thai Milk Tea" data-price="150">Thai Milk Tea - 150</div>
        <div class="item" data-name="Hibiscus Lychee" data-price="125">Hibiscus Lychee - 125</div>
        <div class="item" data-name="Peach Butterfly Pea" data-price="135">Peach Butterfly Pea - 135</div>
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
// Open popup when clicking VOID button, but only if there's an order
document.querySelector(".void").addEventListener("click", function(e) {
    e.preventDefault();
    const summaryBox = document.getElementById("order-summary-box");
    if (summaryBox.innerHTML.includes("No items added yet.")) {
        alert("No order to void.");
    } else {
        document.getElementById("voidModal").style.display = "flex";
    }
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

document.querySelectorAll('.item').forEach(item => {
  item.addEventListener('click', () => {
    const name = item.getAttribute('data-name');
    const price = item.getAttribute('data-price');
    const data = new FormData();
    data.append('addItem', '1');
    data.append('name', name);
    data.append('price', price);
    fetch('', { method: 'POST', body: data })
      .then(res => res.json())
      .then(order => updateOrderSummary(order));
  });
});

function updateOrderSummary(order) {
  const container = document.getElementById('order-summary-box');
  if (!order || order.length === 0) {
    container.innerHTML = '<p>No items added yet.</p>';
    return;
  }
  let html = '';
  order.forEach(item => {
    const subtotal = item.price * item.qty;
    html += `<div class="order-item">${item.name} x${item.qty} - ₱${subtotal}</div>`;
  });
  container.innerHTML = html;
}

document.querySelector('.clear').addEventListener('click', () => {
  if (!confirm('Clear the entire order?')) return;
  fetch('', { method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: 'clearOrder=1' })
    .then(res => res.json())
    .then(() => {
      updateOrderSummary([]);
    });
});

</script>
</body>
</html>
