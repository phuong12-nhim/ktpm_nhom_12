<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>catelog</title>
    <link rel="stylesheet" href="main.css">
</head>

<body>
    <ul class="catelog">
        <?php
        require_once 'ketnoi.php';
        $cal_sql = "SELECT * FROM `catelog`";
        $res = mysqli_query($conn, $cal_sql);
        foreach ($res as $r) {
        ?>
            <li><a style="color: #fefefe;" href="sanpham.php?catelogid=<?php echo $r["catelogid"] ?> "><?php echo $r["catelogname"] ?></a></li>
        <?php
        }
        ?>
    </ul>
</body>

</html>