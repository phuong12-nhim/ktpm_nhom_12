from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

def test_user_register():
    driver = webdriver.Chrome()
    driver.get("http://localhost/ktpm_nhom_12/shop/register.php")
    wait = WebDriverWait(driver, 10)

    name     = wait.until(EC.presence_of_element_located((By.ID, "name")))
    phone    = driver.find_element(By.ID, "phone")
    address  = driver.find_element(By.ID, "address")
    email    = driver.find_element(By.ID, "email")
    username = driver.find_element(By.ID, "username")
    password = driver.find_element(By.ID, "password")
    submit   = driver.find_element(By.NAME, "dangky")

    name.send_keys("Nguyen Van A")
    phone.send_keys("0123456789")
    address.send_keys("Ha Noi")
    email.send_keys("new_user@example.com")
    username.send_keys("new_user")
    password.send_keys("123456")
    submit.click()

    alert = wait.until(EC.alert_is_present())
    assert "Đăng ký thành công" in alert.text
    alert.accept()
    driver.quit()
