<?php
require_once 'dieuhuong.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm</title>
    <link rel="stylesheet" href="main.css">

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
            font-weight: bold;
            font-size: 1.5em;
            overflow: hidden;
            margin-bottom: 10px;
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
            <div class="block-left" style="height: 715px;">
                <?php require_once 'catelog.php'; ?>
            </div>

            <div class="block-right">
                <?php
                require_once 'ketnoi.php';

                // phân trang 
$page = isset($_GET["page"]) ? $_GET["page"] : 1;

// Set up the base SQL query
$sql = "SELECT * FROM `sanpham`";

// Append WHERE clause for catalog ID if provided
if (isset($_GET['catelogid'])) {
    $catelogid = $_GET['catelogid'];
    $sql .= " WHERE catelogid = '$catelogid'";
}

// Append WHERE clause for search keyword if provided
if (isset($_GET["search"])) {
    $search = $_GET["search"];
    $sql .= (strpos($sql, 'WHERE') === false) ? ' WHERE' : ' AND';
    $sql .= " `tensanpham` LIKE '%$search%'";
}

// Append WHERE clause for price filtering if provided
if (isset($_GET["txtPriceMin"]) && is_numeric($_GET["txtPriceMin"])) {
    $min = $_GET["txtPriceMin"];
    $sql .= (strpos($sql, 'WHERE') === false) ? ' WHERE' : ' AND';
    $sql .= " `giadaura` >= $min";
}

if (isset($_GET["txtPriceMax"]) && is_numeric($_GET["txtPriceMax"])) {
    $max = $_GET["txtPriceMax"];
    $sql .= (strpos($sql, 'WHERE') === false) ? ' WHERE' : ' AND';
    $sql .= " `giadaura` <= $max";
}

// Append ORDER BY clause for sorting if provided
if (isset($_GET["sort"])) {
    $sort = strtoupper($_GET["sort"]);
    if ($sort === "ASC" || $sort === "DESC") {
        $sql .= " ORDER BY `giadaura` $sort";
    }
}
// Count the total number of rows
$result = mysqli_query($conn, $sql);
$totalRows = mysqli_num_rows($result);  
$totalpage = ceil($totalRows / 10); // Assuming 10 products per page

// Calculate OFFSET based on current page
$offset = ($page > 1) ? ($page - 1) * 10 : 0;

// Update the SQL query to include LIMIT and OFFSET
$sql .= " LIMIT $offset, 10";

// Fetch products based on the updated SQL query
$result = mysqli_query($conn, $sql);


                if (mysqli_num_rows($result) > 0) {
                    foreach ($result as $row) {
                ?>
                        <div class="fe" style="width: 220px; height: 290px; text-align: center; padding: 5px; margin-left: 10px; margin-top: 15px; box-sizing: border-box;">
                            <div>
                                <a href="hienthi.php?prdid=<?php echo $row["idsanpham"] ?>">
                                    <img style="width: auto; height: 160px; " src="uploads/<?php echo $row["imgae"] ?>" alt="<?php echo $row["tensanpham"] ?>">
                                </a>
                            </div>
                            <br>
                            <div>
                                <span style="font-size: 14px; font-weight: bold;"><?php echo $row["tensanpham"] ?></span> <br>
                            </div>
                            <div>
                                <span style="font-size: 18px; font-weight: bold; color: black; "><?php echo formatCurrency($row["giadaura"]) ?></span>
                            </div>
                            <div>
                                <a href="cart.php?id=<?php echo $row['idsanpham'] ?>">
                                    <button type="submit" class="btn btn-outline-primary" style="padding-bottom:10px; border-radius: 8px; margin-top: 5px">Mua ngay</button>
                                </a>
                            </div>
                            <br>
                        </div>
                <?php
                    }
                ?>
                <br></br>
                    <!-- Phân trang -->
                    <div class="phantrang">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination" style="margin-left:150px; margin-top: 20px ">
                                <?php if ($page > 1) : ?>
                                    <li class="page-item"><a class="page-link" href="sanpham.php?page=<?php echo $page - 1 ?>">Trước</a></li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $totalpage; $i++) : ?>
                                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>"><a class="page-link" href="sanpham.php?page=<?php echo $i ?>"><?php echo $i ?></a></li>
                                <?php endfor; ?>

                                <?php if ($page < $totalpage) : ?>
                                    <li class="page-item"><a class="page-link" href="sanpham.php?page=<?php echo $page + 1 ?>">Sau</a></li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                <?php
                } else {
                    echo "<p>Không có sản phẩm nào.</p>";
                }
                ?>
            </div>
        </div>
        <div class="duoi">
            <?php require_once 'footer.php' ?>
        </div>
    </div>
</body>

</html>
