import pytest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

BASE_URL = "http://localhost/ktpm_nhom_12/shop"

@pytest.fixture
def driver():
    driver = webdriver.Chrome()
    driver.maximize_window()
    driver.implicitly_wait(10)

    # Đăng nhập
    driver.get(f"{BASE_URL}/login.php")

    driver.find_element(By.NAME, "username").send_keys("rannie")
    driver.find_element(By.NAME, "password").send_keys("Fun@12")
    driver.find_element(By.NAME, "dangnhap").click()

    try:
        WebDriverWait(driver, 3).until(EC.alert_is_present())
        alert = driver.switch_to.alert
        print(alert.text)
        alert.accept()
    except:
        pass

    return driver