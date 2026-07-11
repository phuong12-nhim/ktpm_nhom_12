import pytest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time
import random


@pytest.fixture
def driver():
    options = webdriver.ChromeOptions()
    d = webdriver.Chrome(options=options)
    yield d
    d.quit()


def wait_for_url_contains(driver, substring, timeout=10):
    """
    Tự poll driver.current_url thay vì dùng EC.url_contains().
    Một số bản Chrome/Chromedriver mới đôi khi trả current_url = None
    ngay giữa lúc trang đang chuyển hướng (redirect), khiến
    EC.url_contains() bị lỗi TypeError. Hàm này bắt lỗi đó và
    tiếp tục chờ thay vì crash.
    """
    end_time = time.time() + timeout
    last_seen_url = None
    while time.time() < end_time:
        try:
            current = driver.current_url
        except Exception:
            current = None
        if current:
            last_seen_url = current
            if substring in current:
                return True
        time.sleep(0.3)
    raise TimeoutError(
        f"Đợi URL chứa '{substring}' quá {timeout}s. URL cuối cùng thấy được: {last_seen_url}"
    )


def fill_common_fields(driver, name, phone, address, email, username, password):
    """Điền các field chung của form đăng ký user."""
    driver.find_element(By.NAME, "name").send_keys(name)
    driver.find_element(By.NAME, "phone").send_keys(phone)
    driver.find_element(By.NAME, "address").send_keys(address)
    driver.find_element(By.NAME, "email").send_keys(email)
    driver.find_element(By.NAME, "username").send_keys(username)
    driver.find_element(By.NAME, "password").send_keys(password)

def test_user_email_invalid(driver):
    driver.get("http://localhost/ktpm_nhom_12/shop/register.php")

    rand = random.randint(1000, 9999)
    fill_common_fields(
        driver,
        name="Nguyen Van A",
        phone="0987654321",
        address="Ha Noi",
        email=f"user_{rand}@yahoo.com",
        username=f"user_{rand}",
        password="123456"
    )
    driver.find_element(By.NAME, "dangky").click()
    time.sleep(1.5)

    alert_div = driver.find_element(By.CLASS_NAME, "alert-danger")
    assert "Email phải có định dạng @gmail.com" in alert_div.text


# 2. Test case: Mật khẩu quá ngắn (< 4 ký tự)
def test_user_password_too_short(driver):
    driver.get("http://localhost/ktpm_nhom_12/shop/register.php")

    rand = random.randint(1000, 9999)
    fill_common_fields(
        driver,
        name="Nguyen Van B",
        phone="0987654322",
        address="Ha Noi",
        email=f"user_{rand}@gmail.com",
        username=f"user_{rand}",
        password="123"
    )
    driver.find_element(By.NAME, "dangky").click()
    time.sleep(1.5)

    alert_div = driver.find_element(By.CLASS_NAME, "alert-danger")
    assert "Mật khẩu phải từ 4 đến 16 ký tự" in alert_div.text


# 3. Test case: Mật khẩu quá dài (> 16 ký tự)
def test_user_password_too_long(driver):
    driver.get("http://localhost/ktpm_nhom_12/shop/register.php")

    rand = random.randint(1000, 9999)
    fill_common_fields(
        driver,
        name="Nguyen Van C",
        phone="0987654323",
        address="Ha Noi",
        email=f"user_{rand}@gmail.com",
        username=f"user_{rand}",
        password="12345678901234567"  # 17 ký tự
    )
    driver.find_element(By.NAME, "dangky").click()
    time.sleep(1.5)

    alert_div = driver.find_element(By.CLASS_NAME, "alert-danger")
    assert "Mật khẩu phải từ 4 đến 16 ký tự" in alert_div.text


# 4. Test case: Đăng ký thành công
def test_user_register_success(driver):
    driver.get("http://localhost/ktpm_nhom_12/shop/register.php")

    rand = random.randint(1000, 9999)
    fill_common_fields(
        driver,
        name="Nguyen Van D",
        phone="0987654324",
        address="Ha Noi",
        email=f"user_test_{rand}@gmail.com",
        username=f"user_test_{rand}",
        password="password123"
    )
    driver.find_element(By.NAME, "dangky").click()

    wait_for_url_contains(driver, "login.php", timeout=10)
    assert "login.php" in driver.current_url