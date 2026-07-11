from selenium.webdriver.common.by import By

def test_tc65(driver):

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