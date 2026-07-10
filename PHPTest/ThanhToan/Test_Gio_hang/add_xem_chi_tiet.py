from selenium.webdriver.common.by import By

def test_tc62(driver):

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