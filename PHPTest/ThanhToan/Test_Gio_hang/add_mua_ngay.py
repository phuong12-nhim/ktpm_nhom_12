from selenium.webdriver.common.by import By

def test_tc61(driver):

    driver.get("http://localhost/ktpm_nhom_12/shop/sanpham.php")

    driver.find_element(
        By.XPATH,
        "(//button[contains(text(),'Mua ngay')])[1]"
    ).click()

    assert "cart.php" in driver.current_url
    assert "Giỏ Hàng" in driver.page_source