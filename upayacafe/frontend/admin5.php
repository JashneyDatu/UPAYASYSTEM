<!DOCTYPE php>
<php lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upâyâ Café | POS System</title>
  <link rel="stylesheet" href="pos.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600&family=Poppins:wght@400;500&display=swap" rel="stylesheet">

</head>
<body>
  
  <!-- <div class="top-bar">
    <span>12:00 AM</span>
    <span>Thu Sept 25</span>
    <div class="right">
      <span>100%</span>
      <span>📶</span>
      <span>🔋</span>
    </div> -->
  </div>

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
        <button><a href="admin1.php">PREMIUM MATCHA SERIES</a></button>
        <button><a href="admin2.php">NON-COFFEE DRINKS</a></button>
        <button><a href="admin3.php">FRAPPE</a></button>
        <button><a href="admin4.php">FRUIT SODA</a></button>
        <button><a href="admin5.php"><h3>PREMIUM TEA SERIES</h3></a></button>
        <button><a href="admin6.php">ADD-ONS</a></button>
        <button><a href="admin7.php">COOKIES & MUFFINS</a></button>
        <button><a href="admin8.php">WAFFLES</a></button>
        <button><a href="admin9.php">FLAVORED FRIES</a></button>
        <button><a href="admin10.php">PASTA</a></button>
      </div>

      <div class="product-grid">
        <h3>PREMIUM TEA SERIES</h3>
        <div class="items">
          <div class="item">Thai Milk Tea - 150</div>
          <div class="item">Hibiscus Lychee - 125</div>
          <div class="item">Peach Butterfly Pea - 135</div>
          
          
          
          
          
          
          
          
          
          
          
          
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

    </div>
  </div>
</body>
</php>