from selenium.webdriver.common.by import By

def test_tc66(driver):
    #Thêm sản phẩm
    driver.get("http://localhost/ktpm_nhom_12/shop/sanpham.php")

    driver.find_element(
    By.XPATH,
    "(//button[contains(text(),'Mua ngay')])[1]"
    ).click()

    # Mở giỏ hàng
    driver.get("http://localhost/ktpm_nhom_12/shop/view_cart.php")

    rows = driver.find_elements(By.XPATH, "//tbody/tr")

    # Phải có ít nhất 1 sản phẩm + 1 dòng tổng tiền
    assert len(rows) >= 2, "Giỏ hàng không có sản phẩm"

    product_rows = rows[:-1]

    expected_total = 0

    for row in product_rows:
        cols = row.find_elements(By.TAG_NAME, "td")

        # Cột Thành tiền (cột thứ 6)
        thanh_tien = cols[5].text

        thanh_tien = int(
            ''.join(filter(str.isdigit, thanh_tien))
        )

        expected_total += thanh_tien

    # Dòng cuối là Tổng tiền
    total_text = rows[-1].find_elements(By.TAG_NAME, "td")[1].text

    actual_total = int(
        ''.join(filter(str.isdigit, total_text))
    )

    assert actual_total == expected_total