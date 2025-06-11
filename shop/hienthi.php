<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="main.css">

    <!--  -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!--  -->

    <!-- <script>
        function sortByPrice() {
            let objOption = document.getElementById("optPrice");
            alert("Sắp xếp: " + objOption.value);
            window.location = "/baidk/shop/index.php?sort=" + objOption.value;
        }
    </script> -->

    <style>
        .item {
            display: flex;
            flex-direction: column;
        }
    </style>
</head>

<body>
    <?php require_once 'dieuhuong.php'; ?>
    <div class="item">
        <div>
            <div class="item-left">
                <?php require_once 'catelog.php'; ?>
            </div>
            <!-- item item-left -->


            <div class="item-right">
                <?php
                require_once 'ketnoi.php';
                $sql = "SELECT * FROM `sanpham`";
                if (isset($_GET["prdid"])) {
                    $prdid = $_GET["prdid"];
                    $sql .= " WHERE `idsanpham` = $prdid";
                }
                $result = mysqli_query($conn, $sql);
                $tensanpham = $noidung = $imgae = "";
                $giadauvao = $giadaura = 0;
                foreach ($result as $row) {
                    $tensanpham = $row["tensanpham"];
                    $noidung = $row["noidung"];
                    $imgae = $row['imgae'];
                    $giadauvao = $row['giadauvao'];
                    $giadaura = $row['giadaura'];
                    $sta = $row['status'];
                    // $buy = $row['mua'];
                    break;
                }
                ?>
                <div class="ct">
                    <!-- gửi dữ liệu form cập nhật dư liệu (số lượng) -->
                    <form action="cart.php" method="get">
                        <?php
                        echo "<div      
                    class=\"imgg\"><img style=\"width: 70%; height: auto; margin-top: 30px; margin-left: 30px\"  src=\"uploads/$imgae\" 
                    alt=\"Ảnh minh họa\">
                    </div>";
                        echo "<div class=\"tt\"><h1 style=\"font-size: 45px; 
                                                    background-image: linear-gradient(#1846d1,#fafafa );
                                                    background: linear-gradient(to right, #1846d1 0%, #fafafa 100%);
                                                    background-clip: text;
                                                    color: black;\">$tensanpham</h1>";
                        // echo "<h3>" . number_format($giadauvao) . "đ</h3>";
                        echo "<p style=\"font-size: 40px; font-weight: bold; color: black; \">Giá: " . number_format($giadaura) . "đ </p>";
                        echo "<h1 style=\"text-align: left; font-weight:bold; font-size: 18px\"> Mô tả sản phẩm :</h1>";
                        echo "<p style=\"text-align: left; font-size: 18px\">$noidung</p>";
                        echo "<input style=\"font-size: 20px;\" name=\"quantity\" type=\"number\" value=\"1\">";
                        echo "<input type=\"hidden\" name=\"id\" value=\"".$row['idsanpham']."\">";
                        echo "
                            <a href=\"cart.php?id=".$row['idsanpham']."\">
                                
                                <button type=\"submit\" class=\"btn btn-outline-primary\">
                                    Thêm vào giỏ hàng
                                </button>
                
                            </a>
                     </div>";

                        ?>
                    </form>
                </div>
            </div>
        </div>
        <div>
            <?php require_once 'footer.php'; ?>
        </div>
    </div>

</body>

</html>
<!-- style="display:flex; flex-direction: row;" -->
<!-- style=\"width: auto; height: 50%;\" -->
<!-- background: linear-gradient(to right, #30CFD0 0%, #330867 100%); -->
<!-- cart.php?idsp=" .$row['idsanpham']. " -->
<!-- cách khác -->
<!-- cart-process.php -->
<!-- cart-process.php?id= echo $book['id'];?> -->
<!-- 

            <form action="cart.php?idsp= echo $row['idsanpham'] ?>" method="get">
                            <input type="submit" name="" value="echo $buy ?>">
                    </form>

                     <form action="cart.php?idsp= echo $row['idsanpham']?>">
    
    </form>
 -->

 <!-- &action='add' -->