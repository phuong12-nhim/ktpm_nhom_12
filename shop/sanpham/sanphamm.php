<?php
require_once 'dieuhuong.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sản phẩm</title>
    <link rel="stylesheet" href="main.css">
    <!-- sắp xếp -->
    <script>
        function sortByPrice() {
            let objOption = document.getElementById("optPrice");
            // alert("Sắp xếp: " + objOption.value);
            window.location = "/shop/sanpham.php?sort=" + objOption.value;
        }
    </script>
    <style>
        .block {
            display: flex;
            flex-direction: column;
        }

        .phantrang {
            width: 100%;
            margin-left: 30%;
        }

        .phan-trang {
            width: 100%;
            text-align: center;
            list-style: none;
            list-style: none;
            font-weight: bold;
            font-size: 1.5em;
            overflow: hidden;
            margin-bottom: 10px;
            /* display: flex; */
        }

        .phan-trang li {
            display: inline;
        }

        .phan-trang a {
            padding: 10px;
            border: 1px solid #ebebeb;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="block">
        <div>
            <div class="block-left" style="height: 700px;">
                <?php require_once 'catelog.php'; ?>
            </div>

            <div class="block-right">
                <?php
                require_once 'ketnoi.php';
                //
                $sql = "SELECT * FROM `sanpham`";
                if (isset($_GET['catelogid'])) {
                    $catelogid = $_GET['catelogid'];
                    $sql .= "WHERE catelogid = '$catelogid'";
                }

                // Tìm kiếm
                if (isset($_GET["search"])) {
                    $search = $_GET["search"];
                    $sql .= " WHERE `tensanpham` LIKE '%$search%'";
                }

                // Sắp xếp
                if (isset($_GET["sort"])) {
                    $sort = $_GET["sort"];
                    $sql .= " ORDER BY `giadaura` $sort";
                }

                // Lọc
                if (isset($_GET["txtPriceMin"]) && isset($_GET["txtPriceMax"])) {
                    $min = $_GET["txtPriceMin"];
                    $max = $_GET["txtPriceMax"];
                    $sql .= "WHERE `giadaura` >= $min AND `giadaura` <= $max";
                }

                //

                $page = 0;
                if (isset($_GET["page"])) {
                    // echo $_GET["page"];
                    $page = $_GET["page"] - 1;
                }

                // Lấy tổng số trang
                $sql = "SELECT CEIL((SELECT COUNT(*) FROM `sanpham`) / 8) AS 'totalpage'"; // Mỗi page 8 items >>> có thể thay đổi theo tham số
                $result = mysqli_query($conn, $sql);
                $totalpage = 0;
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $totalpage = $row["totalpage"];
                    }
                }
                // Lấy OFFSET hiện tại
                $sql = "SELECT " . $page . " * (SELECT (SELECT COUNT(*) FROM `sanpham`) / (SELECT CEIL((SELECT COUNT(*) FROM `sanpham`) / 8))) AS 'offset'";
                $result = mysqli_query($conn, $sql);
                $offset = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $offset = (int) $row["offset"];
                }

                // Lấy items trong trang
                $sql = "SELECT * FROM `sanpham` LIMIT " . $offset . ", 8";
                // echo $sql;

                $result = mysqli_query($conn, $sql); // Truy vấn
                // $result = mysqli_multi_query($conn, $sql); // Truy vấn

                // Duyệt hiển thị dữ liệu
                if (mysqli_num_rows($result) > 0) {
                    // Code bảng dữ liệu hiển thị

                    // $result = mysqli_query($conn, $sql);
                    // foreach ($result as $row) {
                ?>
                    <!-- Phân trang -->
                    <!-- <ul class="phan-trang">
                    <li>
                        < ? php
                        for ($i = 1; $i <= $totalpage; $i++) {
                            echo "<li><a href='?page=" . $i . "'>" . $i . "</a></li>";
                        }
                        ?>
                    </li>
                </ul> -->
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <div class="fe" style="
                                        width: 220px;
                                        height: 300px;
                                        text-align: center;
                                        padding: 5px;
                                        margin-left: 10px;
                                        margin-top: 5px;
                                        box-sizing: border-box;">
                            <div>
                                <a href="hienthi.php?prdid=<?php echo $row["idsanpham"] ?>">
                                    <img style="width: auto; height: 160px; " src="uploads/<?php echo $row["imgae"] ?>" alt="<?php echo $row["tensanpham"] ?>">
                                </a>
                            </div>

                            <br>
                            <div>
                                <span style="font-size: 15px; font-weight: bold;"><?php echo $row["tensanpham"] ?></span> <br>
                            </div>

                            <div>
                                <span style="font-size: 25px; font-weight: bold; color: black; "><?php echo formatCurrency($row["giadaura"]) ?></span>
                            </div>
                            <div>
                            <a href="cart.php?id=<?php echo $row['idsanpham'] ?>">
                                
                                <button type="submit" class="btn btn-outline-primary">
                                    mua ngay
                                </button>
                
                            </a>
                            </div>
                            <br>
                        </div>
                <?php
                    }
                }
                ?>
                <!-- < ? php echo $page ?> -->
                <!-- Phân trang -->
                <div class="phantrang">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">

                            <?php $i = $page;
                            if ($i < $totalpage) { ?>
                                <?php if ($i <= 0) { ?>
                                <?php } else { ?>
                                    <li class="page-item"><a class="page-link" href="sanpham.php?page=<?php echo $i ?>">trước</a></li>
                                <?php } ?>
                            <?php } elseif ($i <= 0) {
                                header("location: sanpham.php");
                            } elseif ($i > $totalpage) {
                                header("location: sanpham.php");
                            } ?>

                            <?php for ($i = 1; $i < $page; $i++) { ?>
                                <li class="page-item"><a class="page-link" href="sanpham.php?page=<?php echo $i ?>"><?php echo $i ?></a></li>
                            <?php } ?>

                            <?php $i = $page +2;
                            if ($i < $totalpage) { ?>
                                <?php if ($i > $totalpage) { ?>

                                <?php } else { ?>
                                    <li class="page-item"><a class="page-link" href="sanpham.php?page=<?php echo $i ?>">sau</a></li>
                                <?php } ?>
                            <?php } elseif ($i > $totalpage) {
                                header("location: sanpham.php");
                            } ?>

                        </ul>
                    </nav>

                </div>
            </div>
        </div>
        <div class="duoi">
            <?php require_once 'footer.php' ?>
        </div>
    </div>
</body>

</html>