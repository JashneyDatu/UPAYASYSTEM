<?php
session_start();
include "void_auth.php"; // contains $manager_code

$orders = [];
if (file_exists('orders.json')) {
    $orders = json_decode(file_get_contents('orders.json'), true);
}

$order_id = $_POST['order_id'] ?? '';
$auth_code = $_POST['auth_code'] ?? '';

if ($auth_code !== $manager_code) {
    $_SESSION['error'] = "Invalid manager code. Order not voided.";
    header("Location: orders.php");
    exit();
}

if ($order_id) {
    foreach ($orders as &$order) {
        if ($order['id'] == $order_id) {
            $order['status'] = 'Voided';
            break;
        }
    }
    file_put_contents('orders.json', json_encode($orders, JSON_PRETTY_PRINT));
}

header("Location: orders.php");
exit();
