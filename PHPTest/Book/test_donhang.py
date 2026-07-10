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
# TC3
# =========================
def test_has_buttons():

    driver = setup_driver()

    try:
        login(driver)
        open_orders(driver)

        approve = driver.find_elements(By.PARTIAL_LINK_TEXT, "Duyệt")
        cancel = driver.find_elements(By.PARTIAL_LINK_TEXT, "Hủy")

        if len(approve) == 0 and len(cancel) == 0:
            print("TC3 FAIL - Không tìm thấy nút Duyệt/Hủy")
        else:
            print("TC3 PASS - Có nút Duyệt/Hủy")

    except Exception as e:
        print("TC3 FAIL")
        print(e)

    finally:
        driver.quit()


# =========================
# TC4
# =========================
def test_approve_order():

    driver = setup_driver()

    try:
        login(driver)
        open_orders(driver)

        rows = get_rows(driver)

        approve_url = None
        order_id = None

        # Tìm đơn đầu tiên còn nút Duyệt
        for row in rows:

            cols = row.find_elements(By.TAG_NAME, "td")

            if len(cols) < 8:
                continue

            action_col = cols[7]

            if "Duyệt" in action_col.text:

                order_id = cols[0].text.strip()

                approve_btn = row.find_element(By.PARTIAL_LINK_TEXT, "Duyệt")

                approve_url = approve_btn.get_attribute("href")

                break

        if approve_url is None:
            print("TC4 FAIL - Không tìm thấy nút Duyệt")
            return

        print("Approve URL:", approve_url)
        print("Order ID:", order_id)

        driver.get(approve_url)

        try:
            alert = driver.switch_to.alert
            alert.accept()
        except:
            pass

        time.sleep(2)

        driver.get(ORDER_URL)
        time.sleep(2)

        rows = get_rows(driver)

        status_ok = False

        for row in rows:

            cols = row.find_elements(By.TAG_NAME, "td")

            if cols[0].text.strip() == order_id:

                trang_thai = cols[7].text.strip()

                print("Trạng thái sau khi duyệt:", trang_thai)

                if "Hoàn thành" in trang_thai:
                    status_ok = True

                break

        if status_ok:
            print("TC4 PASS - Đơn hàng chuyển sang Hoàn thành")
        else:
            print("TC4 FAIL - Sau khi duyệt trạng thái không đổi")

    except Exception as e:

        print("TC4 FAIL")
        print(e)

    finally:
        driver.quit()

# =========================
# TC5: Kiểm tra nút Xóa (Negative Test)
# =========================
def test_delete_button():

    driver = setup_driver()

    try:
        login(driver)
        open_orders(driver)

        delete_btn = driver.find_elements(By.LINK_TEXT, "Xóa")

        if len(delete_btn) == 0:
            print("TC5 FAIL - Không tìm thấy nút Xóa đơn hàng")
        else:
            print("TC5 PASS - Có nút Xóa đơn hàng")

    except Exception as e:
        print("TC5 FAIL")
        print(e)

    finally:
        driver.quit()

if __name__ == "__main__":

    print("===== BẮT ĐẦU TEST ĐƠN HÀNG =====")

    test_open_orders()
    test_has_orders()
    test_has_buttons()
    test_approve_order()
    test_delete_button()

    print("===== KẾT THÚC TEST ĐƠN HÀNG =====")