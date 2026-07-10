from selenium import webdriver
import time

def test_user_logout():
    driver = webdriver.Chrome()
    driver.get("http://localhost/ktpm_nhom_12/shop/logout.php")

    time.sleep(2)

    # Kiểm tra đã redirect về index.php hoặc sanpham.php
    current_url = driver.current_url
    assert "index.php" in current_url or "sanpham.php" in current_url

    driver.quit()
