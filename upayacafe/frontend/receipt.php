<?php
// Example: Sample order data (in practice, fetch from database)
$order = [
    'order_number' => '000123',
    'cashier' => 'Maria S.',
    'items' => [
        ['name' => 'Caramel Latte', 'qty' => 1, 'price' => 150.00],
        ['name' => 'Blueberry Muffin', 'qty' => 2, 'price' => 95.00],
        ['name' => 'Espresso Shot', 'qty' => 1, 'price' => 60.00]
    ],
    'discount' => 0.10, // 10%
    'tax' => 0.12 // 12%
];

// Calculate totals
$subtotal = 0;
foreach ($order['items'] as $item) {
    $subtotal += $item['qty'] * $item['price'];
}
$discount_amount = $subtotal * $order['discount'];
$tax_amount = ($subtotal - $discount_amount) * $order['tax'];
$total = $subtotal - $discount_amount + $tax_amount;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Upâyâ Café | Receipt</title>
<link rel="stylesheet" href="receipt.css">
</head>
<body>

<div class="receipt-container">
    <div class="header">
        <h2>☕ Upâyâ Café</h2>
        <p>loc : Bahay ni Jashney</p>
        <p><small>Tel: CALL ME WHEN U NEED ME</small></p>
        <div class="divider"></div>
    </div>

    <div class="info">
        <p><strong>Date:</strong> <span><?php echo date('Y-m-d H:i:s'); ?></span></p>
        <p><strong>Cashier:</strong> <span><?php echo $order['cashier']; ?></span></p>
        <p><strong>Order #:</strong> <span><?php echo $order['order_number']; ?></span></p>
    </div>

    <div class="divider dotted"></div>

    <div class="items">
        <?php foreach ($order['items'] as $item): ?>
            <div class="item">
                <span><?php echo $item['qty'] . " x " . $item['name']; ?></span>
                <span>₱<?php echo number_format($item['qty'] * $item['price'], 2); ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="divider dotted"></div>

    <div class="summary">
        <div><span>Subtotal:</span><span>₱<?php echo number_format($subtotal, 2); ?></span></div>
        <div><span>Discount (<?php echo $order['discount']*100; ?>%):</span><span>-₱<?php echo number_format($discount_amount, 2); ?></span></div>
        <div><span>Tax (<?php echo $order['tax']*100; ?>%):</span><span>₱<?php echo number_format($tax_amount, 2); ?></span></div>
        <div class="total"><strong>Total:</strong><strong>₱<?php echo number_format($total, 2); ?></strong></div>
    </div>

    <div class="divider"></div>

    <div class="footer">
        <p><em>Thank you for visiting Upâyâ Café!</em></p>
        <p><small>Follow us @UpayaCafePH</small></p>
        <button onclick="window.print()">🖨 Print Receipt</button>
    </div>
</div>

</body>
</html>
