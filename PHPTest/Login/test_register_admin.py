from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time, random

def test_admin_register():
    driver = webdriver.Chrome()
    driver.get("http://localhost/ktpm_nhom_12/shop/backend/register.php")
    wait = WebDriverWait(driver, 15)

    # Sinh username ngẫu nhiên để tránh trùng
    rand_suffix = str(random.randint(1000, 9999))
    new_username = "admin_test_" + rand_suffix

    username = wait.until(EC.presence_of_element_located((By.NAME, "username")))
    password = driver.find_element(By.NAME, "password")
    level    = driver.find_element(By.NAME, "level")
    submit   = driver.find_element(By.NAME, "dangky")

    username.send_keys(new_username)
    password.send_keys("123456")
    level.send_keys("1")
    submit.click()

    try:
        # Nếu có alert thì xử lý
        alert = wait.until(EC.alert_is_present())
        assert "Đăng ký tài khoản thành công" in alert.text
        alert.accept()
    except Exception:
        # Nếu không có alert thì bỏ qua
        pass

    # Chờ một chút để redirect
    time.sleep(2)

    # Sau đó kiểm tra redirect về login.php
    assert "login.php" in driver.current_url

    driver.quit()
