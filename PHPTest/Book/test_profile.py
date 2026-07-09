from selenium import webdriver
from selenium.webdriver.common.by import By
import time

LOGIN_URL = "http://localhost/ktpm_nhom_12-main/shop/backend/login.php"
PROFILE_URL = "http://localhost/ktpm_nhom_12-main/shop/backend/profile.php"

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
    time.sleep(1)


def open_profile(driver):
    driver.get(PROFILE_URL)
    time.sleep(1)


def get_input_value(driver, name):
    return driver.find_element(By.NAME, name).get_attribute("value").strip()


def clear_and_type(driver, name, value):
    element = driver.find_element(By.NAME, name)
    element.clear()
    element.send_keys(value)


# ===== TEST 1: cập nhật hợp lệ =====
def test_update_valid():
    driver = setup_driver()
    try:
        login(driver)
        open_profile(driver)

        clear_and_type(driver, "fullname", "Nguyen Van A")
        clear_and_type(driver, "email", "vana@gmail.com")
        clear_and_type(driver, "sdt", "0912345678")

        driver.find_element(By.NAME, "update").click()
        time.sleep(1)

        fullname = get_input_value(driver, "fullname")
        email = get_input_value(driver, "email")
        sdt = get_input_value(driver, "sdt")

        if fullname == "Nguyen Van A" and email == "vana@gmail.com" and sdt == "0912345678":
            print("TC1 PASS - Cập nhật hợp lệ")
        else:
            print("TC1 FAIL - Cập nhật hợp lệ")
            print("Giá trị sau cập nhật:", fullname, email, sdt)

    except Exception as e:
        print("TC1 FAIL - Cập nhật hợp lệ")
        print("Lỗi:", e)

    finally:
        driver.quit()


# ===== TEST 2: fullname rỗng => không được cập nhật =====
def test_empty_fullname():
    driver = setup_driver()
    try:
        login(driver)
        open_profile(driver)

        old_fullname = get_input_value(driver, "fullname")
        old_email = get_input_value(driver, "email")
        old_sdt = get_input_value(driver, "sdt")

        clear_and_type(driver, "fullname", "")
        clear_and_type(driver, "email", "abc@gmail.com")
        clear_and_type(driver, "sdt", "0912345678")

        driver.find_element(By.NAME, "update").click()
        time.sleep(1)

        new_fullname = get_input_value(driver, "fullname")
        new_email = get_input_value(driver, "email")
        new_sdt = get_input_value(driver, "sdt")

        # fullname rỗng thì DB không được cập nhật
        if new_fullname == old_fullname and new_email == old_email and new_sdt == old_sdt:
            print("TC2 PASS - Họ tên rỗng không cập nhật")
        else:
            print("TC2 FAIL - Họ tên rỗng")
            print("Trước:", old_fullname, old_email, old_sdt)
            print("Sau  :", new_fullname, new_email, new_sdt)

    except Exception as e:
        print("TC2 FAIL - Họ tên rỗng")
        print("Lỗi:", e)

    finally:
        driver.quit()


# ===== TEST 3: email sai => không được cập nhật =====
def test_invalid_email():
    driver = setup_driver()
    try:
        login(driver)
        open_profile(driver)

        old_fullname = get_input_value(driver, "fullname")
        old_email = get_input_value(driver, "email")
        old_sdt = get_input_value(driver, "sdt")

        clear_and_type(driver, "fullname", "Nguyen Van A")
        clear_and_type(driver, "email", "abc123")
        clear_and_type(driver, "sdt", "0912345678")

        driver.find_element(By.NAME, "update").click()
        time.sleep(1)

        new_fullname = get_input_value(driver, "fullname")
        new_email = get_input_value(driver, "email")
        new_sdt = get_input_value(driver, "sdt")

        if new_fullname == old_fullname and new_email == old_email and new_sdt == old_sdt:
            print("TC3 PASS - Email sai không cập nhật")
        else:
            print("TC3 FAIL - Email sai định dạng")
            print("Trước:", old_fullname, old_email, old_sdt)
            print("Sau  :", new_fullname, new_email, new_sdt)

    except Exception as e:
        print("TC3 FAIL - Email sai định dạng")
        print("Lỗi:", e)

    finally:
        driver.quit()


# ===== TEST 4: email rỗng => vẫn cập nhật được =====
def test_empty_email():
    driver = setup_driver()
    try:
        login(driver)
        open_profile(driver)

        clear_and_type(driver, "fullname", "Nguyen Van B")
        clear_and_type(driver, "email", "")
        clear_and_type(driver, "sdt", "0988888888")

        driver.find_element(By.NAME, "update").click()
        time.sleep(1)

        fullname = get_input_value(driver, "fullname")
        email = get_input_value(driver, "email")
        sdt = get_input_value(driver, "sdt")

        if fullname == "Nguyen Van B" and email == "" and sdt == "0988888888":
            print("TC4 PASS - Email rỗng vẫn cập nhật")
        else:
            print("TC4 FAIL - Email rỗng")
            print("Giá trị sau cập nhật:", fullname, email, sdt)

    except Exception as e:
        print("TC4 FAIL - Email rỗng")
        print("Lỗi:", e)

    finally:
        driver.quit()


if __name__ == "__main__":
    print("===== BẮT ĐẦU TEST PROFILE =====")
    test_update_valid()
    test_empty_fullname()
    test_invalid_email()
    test_empty_email()
    print("===== KẾT THÚC TEST PROFILE =====")