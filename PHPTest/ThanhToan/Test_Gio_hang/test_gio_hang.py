from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

def test_cart_requires_login(driverfix):

    driverfix.get("http://localhost/ktpm_nhom_12/shop/dieuhuong.php")

    driverfix.find_element(
    By.XPATH,
    "//a[contains(@href,'view_cart.php')]"
    ).click()  

    WebDriverWait(driverfix, 10).until(
        EC.url_contains("login.php")
    )

    assert "login.php" in driverfix.current_url

def test_add_mua_ngay(driver):

    driver.get("http://localhost/ktpm_nhom_12/shop/sanpham.php")

    driver.find_element(
        By.XPATH,
        "(//button[contains(text(),'Mua ngay')])[1]"
    ).click()

    assert "cart.php" in driver.current_url
    assert "Giỏ Hàng" in driver.page_source

def test_xem_chi_tiet(driver):

    driver.get("http://localhost/ktpm_nhom_12/shop/hienthi.php?prdid=1")

    qty = driver.find_element(By.NAME,"quantity")
    qty.clear()
    qty.send_keys("2")

    driver.find_element(
        By.XPATH,
        "//button[contains(text(),'Thêm vào giỏ hàng')]"
    ).click()

    assert "view_cart.php" in driver.current_url

    msg = driver.find_element(By.CLASS_NAME,"alert").text

    assert "Thêm vào giỏ hàng thành công!" in msg

def test_delete(driver):

    driver.get("http://localhost/ktpm_nhom_12/shop/cart.php")

    driver.find_element(By.LINK_TEXT,"Xóa").click()

    assert "Xóa sản phẩm khỏi giỏ hàng thành công!" in driver.page_source

def test_update_so_luong(driver):

    driver.get("http://localhost/ktpm_nhom_12/shop/cart.php")

    qty = driver.find_element(By.NAME,"quantity")

    qty.clear()
    qty.send_keys("3")

    driver.find_element(By.NAME,"update").click()

    assert "Cập nhật giỏ hàng thành công!" in driver.page_source

def test_check_rong(driver):

    driver.get("http://localhost/ktpm_nhom_12/shop/cart.php")

    while True:

        delete_buttons = driver.find_elements(By.LINK_TEXT,"Xóa")

        if len(delete_buttons)==0:
            break

        delete_buttons[0].click()

    button = driver.find_element(
        By.XPATH,
        "//button[contains(text(),'Tiếp tục')]"
    )

    assert not button.is_enabled()

def test_check_tong_tien(driver):
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