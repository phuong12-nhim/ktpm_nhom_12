<?php
    session_start();
    ob_start();
    session_destroy();
    // header("location: view_cart.php");
?>