from selenium.webdriver.common.by import By

def test_tc63(driver):

    driver.get("http://localhost/ktpm_nhom_12/shop/cart.php")

    driver.find_element(By.LINK_TEXT,"Xóa").click()

    assert "Xóa sản phẩm khỏi giỏ hàng thành công!" in driver.page_source