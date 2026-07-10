from selenium import webdriver
from selenium.webdriver.common.by import By
import time

BASE = "http://localhost/ktpm_nhom_12-main"

LOGIN_URL = f"{BASE}/shop/backend/login.php"
USERS_URL = f"{BASE}/shop/backend/users.php"

USERNAME = "dunggg"
PASSWORD = "123456"


def setup_driver():
    driver = webdriver.Chrome()
    driver.maximize_window()
    driver.implicitly_wait(5)
    return driver


def login(driver):
    driver.get(LOGIN_URL)

    driver.find_element(By.NAME, "username").send_keys(USERNAME)
    driver.find_element(By.NAME, "password").send_keys(PASSWORD)
    driver.find_element(By.NAME, "dangnhap").click()

    time.sleep(2)


def open_users(driver):
    driver.get(USERS_URL)
    time.sleep(2)


def get_rows(driver):
    return driver.find_elements(By.XPATH, "//table/tbody/tr")


# =========================
# TC1
# =========================
def test_open_users():
    driver = setup_driver()

    try:
        login(driver)
        open_users(driver)

        if "users.php" in driver.current_url:
            print("TC1 PASS - Mở trang người dùng")
        else:
            print("TC1 FAIL")

    finally:
        driver.quit()


# =========================
# TC2
# =========================
def test_has_users():
    driver = setup_driver()

    try:
        login(driver)
        open_users(driver)

        rows = get_rows(driver)

        if len(rows) > 0:
            print("TC2 PASS - Có dữ liệu người dùng")
        else:
            print("TC2 FAIL")

    finally:
        driver.quit()


# =========================
# TC3
# =========================
def test_delete_button():
    driver = setup_driver()

    try:
        login(driver)
        open_users(driver)

        rows = get_rows(driver)

        ok = True

        for row in rows:
            btn = row.find_element(By.LINK_TEXT, "Xóa")
            if btn.text != "Xóa":
                ok = False

        if ok:
            print("TC3 PASS - Có nút Xóa")
        else:
            print("TC3 FAIL")

    finally:
        driver.quit()


# =========================
# TC4
# =========================
def test_delete_user():

    driver = setup_driver()

    try:
        login(driver)
        open_users(driver)

        rows = get_rows(driver)

        if len(rows) == 0:
            print("TC4 FAIL - Không có người dùng")
            return

        first_row = rows[-1]

        user_name = first_row.find_elements(By.TAG_NAME, "td")[1].text

        delete_btn = first_row.find_element(By.LINK_TEXT, "Xóa")

        href = delete_btn.get_attribute("href")

        print("Delete URL:", href)

        driver.get(href)

        try:
            alert = driver.switch_to.alert
            alert.accept()
        except:
            pass

        time.sleep(2)

        driver.get(USERS_URL)
        time.sleep(2)

        names = []

        rows = get_rows(driver)

        for row in rows:
            cols = row.find_elements(By.TAG_NAME, "td")
            names.append(cols[1].text)

        if user_name not in names:
            print("TC4 PASS - Xóa người dùng")
        else:
            print("TC4 FAIL - Xóa người dùng")
            print(names)

    finally:
        driver.quit()


if __name__ == "__main__":
    print("===== BẮT ĐẦU TEST NGƯỜI DÙNG =====")

    test_open_users()
    test_has_users()
    test_delete_button()
    test_delete_user()

    print("===== KẾT THÚC TEST NGƯỜI DÙNG =====")