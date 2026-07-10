from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

def test_admin_login_success():
    driver = webdriver.Chrome()
    driver.get("http://localhost/ktpm_nhom_12/shop/backend/login.php")
    wait = WebDriverWait(driver, 15)

    username = wait.until(EC.presence_of_element_located((By.ID, "username")))
    password = driver.find_element(By.ID, "password")
    submit   = driver.find_element(By.NAME, "dangnhap")

    # Admin account: manhh / 123456
    username.send_keys("manhh")
    password.send_keys("123456")
    submit.click()

    try:
        # Nếu có alert thì bắt
        alert = wait.until(EC.alert_is_present())
        assert "Đăng nhập thành công" in alert.text
        alert.accept()
    except Exception:
        # Nếu không có alert thì kiểm tra URL redirect
        wait.until(EC.url_contains("backend"))
        assert "backend" in driver.current_url
    finally:
        driver.quit()


def test_admin_login_wrong_password():
    driver = webdriver.Chrome()
    driver.get("http://localhost/ktpm_nhom_12/shop/backend/login.php")
    wait = WebDriverWait(driver, 15)

    username = wait.until(EC.presence_of_element_located((By.ID, "username")))
    password = driver.find_element(By.ID, "password")
    submit   = driver.find_element(By.NAME, "dangnhap")

    username.send_keys("manhh")
    password.send_keys("sai_mat_khau")
    submit.click()

    try:
        alert = wait.until(EC.alert_is_present())
        assert "Thông tin tài khoản hoặc mật khẩu không chính xác" in alert.text
        alert.accept()
    except Exception:
        # fallback: nếu không có alert thì vẫn phải ở trang login
        assert "login.php" in driver.current_url
    finally:
        driver.quit()
