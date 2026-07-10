from selenium import webdriver
from selenium.webdriver.common.by import By
import time

BASE = "http://localhost/ktpm_nhom_12-main"

LOGIN_URL = f"{BASE}/shop/backend/login.php"
ORDER_URL = f"{BASE}/shop/backend/order.php"

USERNAME = "dunggg"
PASSWORD = "123456"


def setup_driver():
    driver = webdriver.Chrome()
    driver.maximize_window()
    driver.implicitly_wait(5)
    return driver


def login(driver):
    driver.get(LOGIN_URL)

    driver.find_element(By.NAME, "username").clear()
    driver.find_element(By.NAME, "username").send_keys(USERNAME)

    driver.find_element(By.NAME, "password").clear()
    driver.find_element(By.NAME, "password").send_keys(PASSWORD)

    driver.find_element(By.NAME, "dangnhap").click()
    time.sleep(2)


def open_orders(driver):
    driver.get(ORDER_URL)
    time.sleep(2)


def get_rows(driver):
    return driver.find_elements(By.XPATH, "//table/tbody/tr")


# =========================
# TC1
# =========================
def test_open_orders():

    driver = setup_driver()

    try:
        login(driver)
        open_orders(driver)

        if "order.php" in driver.current_url:
            print("TC1 PASS - Mở trang đơn hàng")
        else:
            print("TC1 FAIL - Không mở được trang đơn hàng")

    except Exception as e:
        print("TC1 FAIL")
        print(e)

    finally:
        driver.quit()


# =========================
# TC2
# =========================
def test_has_orders():

    driver = setup_driver()

    try:
        login(driver)
        open_orders(driver)

        rows = get_rows(driver)

        if len(rows) > 0:
            print("TC2 PASS - Có dữ liệu đơn hàng")
        else:
            print("TC2 FAIL - Không có dữ liệu")

    except Exception as e:
        print("TC2 FAIL")
        print(e)

    finally:
        driver.quit()


# =========================
# TC3: Kiểm tra nút Duyệt/Hủy
# =========================
def test_has_buttons():

    driver = setup_driver()

    try:
        login(driver)
        open_orders(driver)

        approve = driver.find_elements(By.LINK_TEXT, "Duyệt")
        cancel = driver.find_elements(By.LINK_TEXT, "Xóa")

        if len(approve) == 0 or len(cancel) == 0:
            print("TC3 FAIL - Không tìm thấy nút Xóa")
        else:
            print("TC3 PASS - Có nút Duyệt/Hủy")

    finally:
        driver.quit()

# =========================
# TC4: Duyệt đơn hàng
# =========================
def test_approve_order():

    driver = setup_driver()

    try:
        login(driver)
        open_orders(driver)

        approve = driver.find_elements(By.LINK_TEXT, "Duyệt")

        if len(approve) == 0:
            print("TC4 FAIL - Không tìm thấy nút Duyệt")
            return

        first_btn = approve[0]
        href = first_btn.get_attribute("href")

        print("Approve URL:", href)

        driver.get(href)
        time.sleep(2)

        print("TC4 PASS - Duyệt đơn hàng")

    finally:
        driver.quit()

if __name__ == "__main__":

    print("===== BẮT ĐẦU TEST ĐƠN HÀNG =====")

    test_open_orders()
    test_has_orders()
    test_has_buttons()
    test_approve_order()
    

    print("===== KẾT THÚC TEST ĐƠN HÀNG =====")