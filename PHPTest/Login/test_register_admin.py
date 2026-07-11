import pytest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time

@pytest.fixture
def driver():
    options = webdriver.ChromeOptions()
    d = webdriver.Chrome(options=options)
    yield d
    d.quit()

# 1. Test case: Email sai định dạng (thiếu @gmail.com)
def test_admin_email_invalid(driver):
    driver.get("http://localhost/ktpm_nhom_12/shop/backend/register.php")
    driver.find_element(By.NAME, "username").send_keys("admin_sai_dinh_dang")
    driver.find_element(By.NAME, "password").send_keys("123456")
    driver.find_element(By.NAME, "dangky").click()
    time.sleep(1.5)
    assert "is-invalid" in driver.find_element(By.NAME, "username").get_attribute("class")

# 2. Test case: Mật khẩu quá ngắn (< 4 ký tự)
def test_admin_password_too_short(driver):
    driver.get("http://localhost/ktpm_nhom_12/shop/backend/register.php")
    driver.find_element(By.NAME, "username").send_keys("test_ngan@gmail.com")
    driver.find_element(By.NAME, "password").send_keys("123")
    driver.find_element(By.NAME, "dangky").click()
    time.sleep(0.5)
    assert "is-invalid" in driver.find_element(By.NAME, "password").get_attribute("class")

# 3. Test case: Mật khẩu quá dài (> 16 ký tự)
def test_admin_password_too_long(driver):
    driver.get("http://localhost/ktpm_nhom_12/shop/backend/register.php")
    driver.find_element(By.NAME, "username").send_keys("test_dai@gmail.com")
    driver.find_element(By.NAME, "password").send_keys("12345678901234567") # 17 ký tự
    driver.find_element(By.NAME, "dangky").click()
    time.sleep(0.5)
    assert "is-invalid" in driver.find_element(By.NAME, "password").get_attribute("class")

# 4. Test case: Đăng ký thành công
def test_admin_register_success(driver):
    driver.get("http://localhost/ktpm_nhom_12/shop/backend/register.php")
    # Sinh username ngẫu nhiên để tránh lỗi "Tên đã tồn tại"
    import random
    rand = str(random.randint(1000, 9999))
    
    driver.find_element(By.NAME, "username").send_keys(f"admin_{rand}@gmail.com")
    driver.find_element(By.NAME, "password").send_keys("password123")
    driver.find_element(By.NAME, "level").send_keys("1")
    driver.find_element(By.NAME, "dangky").click()
    
    # Xử lý Alert thành công nếu có
    try:
        WebDriverWait(driver, 2).until(EC.alert_is_present())
        driver.switch_to.alert.accept()
    except:
        pass
        
    WebDriverWait(driver, 5).until(EC.url_contains("login.php"))
    assert "login.php" in driver.current_url