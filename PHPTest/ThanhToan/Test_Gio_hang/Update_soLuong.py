from selenium.webdriver.common.by import By

def test_tc64(driver):

    driver.get("http://localhost/ktpm_nhom_12/shop/cart.php")

    qty = driver.find_element(By.NAME,"quantity")

    qty.clear()
    qty.send_keys("3")

    driver.find_element(By.NAME,"update").click()

    assert "Cập nhật giỏ hàng thành công!" in driver.page_source