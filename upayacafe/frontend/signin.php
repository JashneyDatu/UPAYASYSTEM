<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upaya Café Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- <div class="top-bar">
    <span>12:00 AM</span>
    <span>Thu Sept 25</span>
    <div class="right">
      <span>100%</span>
      <i class="wifi">📶</i>
      <i class="battery">🔋</i>
    </div> -->
  </div>

  <div class="container">
    <div class="logo">
      <h1>Upâyâ</h1>
      <p>Café</p>
    </div>

    <div class="login-box">
      <div class="user-icon">
        <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="user">
      </div>

    <form action="pos.php" method="POST">
     <label for="username">USERNAME:</label>
     <input type="text" id="username" name="username" required>

     <label for="password">PASSWORD:</label>
     <input type="password" id="password" name="password" required>

     <button type="submit">SIGN IN</button><br>
    
    </form>
    <form action="add.php" method="POST">
       <button type="submit">ADD USER</button>

        
    </form>
    </div>
  </div>
</body>
</html>
