from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time

BASE_URL = "http://localhost:3000/shop/sanpham.php"     

SEARCH_CASES = [
    "Harry Potter",
    "Python",
    "sách giáo khoa",
    "SÁCH",
    " sách "
]

driver = webdriver.Chrome()
driver.maximize_window()

try:
    driver.get(BASE_URL)

    wait = WebDriverWait(driver, 10)

    for keyword in SEARCH_CASES:

        print("\n========================================")
        print("Đang test:", keyword)
        print("========================================")

        search = wait.until(
            EC.presence_of_element_located((By.ID, "search"))
        )

        search.clear()

        search.send_keys(keyword)

        search.send_keys(Keys.ENTER)

        time.sleep(2)

        products = driver.find_elements(By.CLASS_NAME, "fe")

        if len(products) == 0:
            print("Không có sản phẩm nào.")
            driver.back()
            continue

        found = False

        print("Danh sách tìm được:")

        for product in products:

            spans = product.find_elements(By.TAG_NAME, "span")

            if len(spans) >= 2:

                name = spans[0].text
                price = spans[1].text

                print("-", name, "|", price)

                if keyword.lower() in name.lower():
                    found = True

        if found:
            print("PASS")
        else:
            print("FAIL")

        driver.back()

        wait.until(
            EC.presence_of_element_located((By.ID, "search"))
        )

except Exception as e:
    print(e)

finally:
    input("\nNhấn Enter để đóng...")
    driver.quit()