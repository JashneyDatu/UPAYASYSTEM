<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Upâyâ Café | Inventory</title>
  <link rel="stylesheet" href="inventory.css" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600&family=Poppins:wght@400;500&display=swap" rel="stylesheet" />
</head>
<body>

<div class="pos-container">

  <!-- Sidebar -->
  <div class="sidebar">
    <a href="admin.php" class="icon active">🏠</a>
    <a href="orderhistory.php" class="icon active">📝</a>
    <a href="inventory.php" class="icon active">📦</a>
    <a href="salesreport.php" class="icon active">📊</a>
    <a href="settings.php" class="icon active">⚙️</a>
    <a href="logout.php" class="icon active">⬅️</a>
  </div>

  <!-- Main content -->
  <div class="main-content">
    <div class="logo">
      <h1>Upâyâ</h1>
      <p>Café</p>
    </div>

    <h1 class="title">Inventory System</h1>

    <div class="inventory-box">
      <h2>Inventory</h2>

      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Product</th>
              <th>Ingredients</th>
              <th>Stock</th>
              <th>Price</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Americano</td>
              <td>Espresso Shot, Hot Water</td>
              <td>--</td>
              <td>110</td>
            </tr>
            <tr>
              <td>Cafe Latte</td>
              <td>Espresso Shot, Steamed Milk, Milk Foam</td>
              <td>--</td>
              <td>120</td>
            </tr>
            <tr>
              <td>Caramel Macchiato</td>
              <td>Espresso Shot, Steamed Milk, Caramel Syrup, Milk Foam, Caramel Drizzle</td>
              <td>--</td>
              <td>135</td>
            </tr>
            <tr>
              <td>Iced Mocha</td>
              <td>Espresso Shot, Milk, Chocolate Sauce, Ice, Whipped Cream</td>
              <td>--</td>
              <td>125</td>
            </tr>
            <tr>
              <td>White Chocolate Mocha</td>
              <td>Espresso Shot, Milk, White Chocolate Sauce, Whipped Cream</td>
              <td>--</td>
              <td>135</td>
            </tr>
            <tr>
              <td>Salted Caramel Latte</td>
              <td>Espresso Shot, Steamed Milk, Salted Caramel Syrup, Caramel Drizzle</td>
              <td>--</td>
              <td>135</td>
            </tr>
            <tr>
              <td>Spanish Latte</td>
              <td>Espresso Shot, Fresh Milk, Condensed Milk</td>
              <td>--</td>
              <td>130</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

</body>
</html>