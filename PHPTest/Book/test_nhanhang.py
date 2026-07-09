from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
import time

BASE = "http://localhost/ktpm_nhom_12-main"

LOGIN_URL = f"{BASE}/shop/backend/login.php"
NHANHANG_URL = f"{BASE}/shop/backend/nhan_hang.php"

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
    time.sleep(1)


def open_nhanhang(driver):
    driver.get(NHANHANG_URL)
    time.sleep(1)


def clear_input(element):
    element.click()
    time.sleep(0.2)
    element.send_keys(Keys.CONTROL, "a")
    time.sleep(0.2)
    element.send_keys(Keys.DELETE)
    time.sleep(0.2)


def set_catelog_name(driver, value):
    inp = driver.find_element(By.NAME, "catelogname")
    clear_input(inp)
    if value != "":
        inp.send_keys(value)
    time.sleep(0.3)


def get_table_rows(driver):
    return driver.find_elements(By.XPATH, "//table/tbody/tr")


def get_table_names(driver):
    rows = get_table_rows(driver)
    names = []
    for row in rows:
        cols = row.find_elements(By.TAG_NAME, "td")
        if len(cols) >= 2:
            names.append(cols[1].text.strip())
    return names


def normalize_project_url(href: str):
    """
    Chuyển link kiểu /shop/backend/... thành
    http://localhost/ktpm_nhom_12-main/shop/backend/...
    """
    if href.startswith("http"):
        # nếu nó là http://localhost/shop/backend/... thì sửa về đúng project
        href = href.replace("http://localhost/shop/backend", f"{BASE}/shop/backend")
        return href

    if href.startswith("/shop/backend"):
        return BASE + href

    if href.startswith("shop/backend"):
        return BASE + "/" + href

    return href


# =========================
# TC1: Thêm nhãn hàng hợp lệ
# =========================
def test_add_valid():
    driver = setup_driver()
    try:
        login(driver)
        open_nhanhang(driver)

        brand_name = "NhanHangTest_123"

        set_catelog_name(driver, brand_name)
        driver.find_element(By.XPATH, "//button[contains(text(),'Thêm')]").click()
        time.sleep(1)

        try:
            alert = driver.switch_to.alert
            alert.accept()
            time.sleep(1)
        except:
            pass

        driver.get(NHANHANG_URL)
        time.sleep(1)

        names = get_table_names(driver)
        if brand_name in names:
            print("TC1 PASS - Thêm nhãn hàng hợp lệ")
        else:
            print("TC1 FAIL - Thêm nhãn hàng hợp lệ")
            print("Danh sách hiện tại:", names)

    except Exception as e:
        print("TC1 FAIL - Thêm nhãn hàng hợp lệ")
        print("Lỗi:", e)

    finally:
        driver.quit()


# =========================
# TC2: Để trống tên nhãn hàng
# =========================
def test_add_empty():
    driver = setup_driver()
    try:
        login(driver)
        open_nhanhang(driver)

        before_names = get_table_names(driver)

        set_catelog_name(driver, "")
        driver.find_element(By.XPATH, "//button[contains(text(),'Thêm')]").click()
        time.sleep(1)

        try:
            alert = driver.switch_to.alert
            alert.accept()
            time.sleep(1)
        except:
            pass

        driver.get(NHANHANG_URL)
        time.sleep(1)

        after_names = get_table_names(driver)

        if before_names == after_names:
            print("TC2 PASS - Tên nhãn hàng rỗng không thêm")
        else:
            print("TC2 FAIL - Tên nhãn hàng rỗng")
            print("Trước:", before_names)
            print("Sau  :", after_names)

    except Exception as e:
        print("TC2 FAIL - Tên nhãn hàng rỗng")
        print("Lỗi:", e)

    finally:
        driver.quit()


# =========================
# TC3: Mở trang sửa nhãn hàng
# =========================
def test_open_edit():
    driver = setup_driver()
    try:
        login(driver)
        open_nhanhang(driver)

        rows = get_table_rows(driver)
        if not rows:
            print("TC3 FAIL - Không có dòng nhãn hàng nào để sửa")
            return

        first_row = rows[0]
        edit_btn = first_row.find_element(By.LINK_TEXT, "Sửa")
        href = edit_btn.get_attribute("href")

        fixed_href = normalize_project_url(href)
        driver.get(fixed_href)
        time.sleep(1)

        current_url = driver.current_url

        if "sua_nhan_hang.php" in current_url:
            print("TC3 PASS - Mở trang sửa nhãn hàng")
        else:
            print("TC3 FAIL - Mở trang sửa nhãn hàng")
            print("Href gốc   :", href)
            print("Href sửa   :", fixed_href)
            print("URL hiện tại:", current_url)

    except Exception as e:
        print("TC3 FAIL - Mở trang sửa nhãn hàng")
        print("Lỗi:", e)

    finally:
        driver.quit()


# =========================
# TC4: Xóa nhãn hàng vừa thêm
# =========================
def test_delete_brand():
    driver = setup_driver()
    try:
        login(driver)
        open_nhanhang(driver)

        brand_name = f"NhanHang_Xoa_Test_{int(time.time())}"

        # Thêm nhãn hàng
        set_catelog_name(driver, brand_name)
        driver.find_element(By.XPATH, "//button[contains(text(),'Thêm')]").click()

        try:
            driver.switch_to.alert.accept()
        except:
            pass

        time.sleep(2)
        driver.get(NHANHANG_URL)
        time.sleep(2)

        rows = get_table_rows(driver)

        catelog_id = None

        for row in rows:
            cols = row.find_elements(By.TAG_NAME, "td")
            if len(cols) >= 4:
                if cols[1].text.strip() == brand_name:
                    catelog_id = cols[0].text.strip()
                    break

        if catelog_id is None:
            print("TC4 FAIL - Không tìm thấy nhãn hàng vừa thêm")
            return

        delete_url = f"{BASE}/shop/backend/xoa_nhan_hang.php?Catid={catelog_id}"
        print("Delete URL:", delete_url)

        driver.get(delete_url)
        time.sleep(2)

        driver.get(NHANHANG_URL)
        time.sleep(2)

        names = get_table_names(driver)

        if brand_name not in names:
            print("TC4 PASS - Xóa nhãn hàng")
        else:
            print("TC4 FAIL - Xóa nhãn hàng")
            print("Danh sách sau khi xóa:", names)

    except Exception as e:
        print("TC4 FAIL - Xóa nhãn hàng")
        print(e)

    finally:
        driver.quit()

if __name__ == "__main__":
    print("===== BẮT ĐẦU TEST NHÃN HÀNG =====")
    test_add_valid()
    test_add_empty()
    test_open_edit()
    test_delete_brand()
    print("===== KẾT THÚC TEST NHÃN HÀNG =====")