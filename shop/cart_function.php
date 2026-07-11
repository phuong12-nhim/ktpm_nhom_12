<?php
require_once 'ketnoi.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function total_price($cart) {
    $total_price = 0;
    foreach ($cart as $key => $value) {
        $total_price += $value['price'] * $value['quantity'];
    }
    return $total_price;
}

function total_item($cart) {
    $total = 0;

    foreach ($cart as $key => $value) {
        $total += $value['quantity'];
    }
    return $total;
}

?>