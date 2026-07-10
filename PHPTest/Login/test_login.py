from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

def test_user_login_success():
    driver = webdriver.Chrome()
    driver.get("http://localhost/ktpm_nhom_12/shop/login.php")
    wait = WebDriverWait(driver, 10)

    username = wait.until(EC.presence_of_element_located((By.ID, "username")))
    password = driver.find_element(By.ID, "password")
    submit   = driver.find_element(By.NAME, "dangnhap")

    # User account: manhmanh / 123456
    username.send_keys("manhmanh")
    password.send_keys("123456")
    submit.click()

    # Bắt alert trước
    alert = wait.until(EC.alert_is_present())
    assert "Đăng nhập thành công" in alert.text
    alert.accept()

    # Sau đó kiểm tra URL redirect
    wait.until(EC.url_contains("sanpham.php"))
    assert "sanpham.php" in driver.current_url
    driver.quit()


def test_user_login_wrong_password():
    driver = webdriver.Chrome()
    driver.get("http://localhost/ktpm_nhom_12/shop/login.php")
    wait = WebDriverWait(driver, 15)  # tăng timeout để chắc chắn bắt được alert

    username = wait.until(EC.presence_of_element_located((By.ID, "username")))
    password = driver.find_element(By.ID, "password")
    submit   = driver.find_element(By.NAME, "dangnhap")

    username.send_keys("manhmanh")
    password.send_keys("sai_mat_khau")
    submit.click()

    try:
        alert = wait.until(EC.alert_is_present())
        assert "Thông tin tài khoản hoặc mật khẩu không chính xác" in alert.text
        alert.accept()
    except Exception:
        # Nếu không có alert, kiểm tra xem trang vẫn ở login.php
        assert "login.php" in driver.current_url
    finally:
        driver.quit()
