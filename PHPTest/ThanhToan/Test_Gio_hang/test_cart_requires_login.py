import pytest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC


@pytest.fixture
def driverfix():
    driver = webdriver.Chrome()
    driver.maximize_window()
    driver.implicitly_wait(10)

    yield driver

    driver.quit()


def test_cart_requires_login(driverfix):

    driverfix.get("http://localhost/ktpm_nhom_12/shop/dieuhuong.php")

    driverfix.find_element(
        By.XPATH,
        "//a[contains(@href,'view_cart.php')]"
    ).click()

    WebDriverWait(driverfix, 10).until(
        EC.url_contains("login.php")
    )

    assert "login.php" in driverfix.current_url