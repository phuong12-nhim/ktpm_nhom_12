from selenium.webdriver.common.by import By

BASE_URL = "http://localhost/ktpm_nhom_12/shop"


def add_first_product(driver):
    """
    Thêm sản phẩm đầu tiên vào giỏ hàng bằng nút Mua ngay
    """
    driver.get(f"{BASE_URL}/sanpham.php")

    driver.find_element(
        By.XPATH,
        "(//button[contains(text(),'Mua ngay')])[1]"
    ).click()

# TC7.1 Kiểm tra hiển thị thông tin khách hàng

def test_customer_information(driver):

    add_first_product(driver)

    driver.get(f"{BASE_URL}/check-out.php")

    assert driver.find_element(By.NAME, "username").get_attribute("value") != ""
    assert driver.find_element(By.NAME, "email").get_attribute("value") != ""
    assert driver.find_element(By.NAME, "phone").get_attribute("value") != ""
    assert driver.find_element(By.NAME, "address").get_attribute("value") != ""

# TC7.2 Kiểm tra thông tin đơn hàng

def test_order_information(driver):

    add_first_product(driver)

    driver.get(f"{BASE_URL}/check-out.php")

    rows = driver.find_elements(By.XPATH, "//table/tbody/tr")

    # Có ít nhất một sản phẩm
    assert len(rows) >= 1

# TC7.3 Kiểm tra tổng tiền

def test_total_price(driver):

    add_first_product(driver)

    driver.get(f"{BASE_URL}/check-out.php")

    total = driver.find_element(By.CLASS_NAME, "total").text

    assert "Tổng Tiền" in total
    assert "VNĐ" in total

# TC7.4 Kiểm tra nhập ghi chú

def test_note(driver):

    add_first_product(driver)

    driver.get(f"{BASE_URL}/check-out.php")

    note = driver.find_element(By.ID, "note")

    note.clear()
    note.send_keys("Giao hàng trong giờ hành chính")

    assert note.get_attribute("value") == "Giao hàng trong giờ hành chính"

# TC7.5 Kiểm tra nút Tiếp tục

def test_checkout_button(driver):

    add_first_product(driver)

    driver.get(f"{BASE_URL}/check-out.php")

    button = driver.find_element(By.NAME, "checkout")

    assert button.is_enabled()