from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import re
import time

BASE_URL = "http://localhost:3000/shop/sanpham.php"   # sửa đúng URL

# (Giá thấp nhất, Giá cao nhất)
TEST_CASES = [
    (0, 100000),
    (100000, 200000),
    (200000, 500000),
    ("100000.5", "300000.8"),
    (500000, 1000000)
]

driver = webdriver.Chrome()
driver.maximize_window()

wait = WebDriverWait(driver, 10)

try:

    for min_price, max_price in TEST_CASES:

        print("=" * 60)
        print(f"Kiểm thử khoảng giá: {min_price} - {max_price}")
        print("=" * 60)

        driver.get(BASE_URL)

        txtMin = wait.until(
            EC.presence_of_element_located((By.ID, "txtPriceMin"))
        )

        txtMax = driver.find_element(By.ID, "txtPriceMax")

        txtMin.clear()
        txtMax.clear()

        txtMin.send_keys(str(min_price))
        txtMax.send_keys(str(max_price))

        txtMax.send_keys(Keys.ENTER)

        time.sleep(2)

        products = driver.find_elements(By.CLASS_NAME, "fe")

        if len(products) == 0:
            print("Không có sản phẩm trong khoảng giá này.")
            continue

        passed = True

        for product in products:

            spans = product.find_elements(By.TAG_NAME, "span")

            if len(spans) < 2:
                continue

            name = spans[0].text
            price_text = spans[1].text

            # Chuyển "150.000đ" -> 150000
            price = int(re.sub(r"[^\d]", "", price_text))

            print(f"{name} : {price:,}")

            if not (min_price <= price <= max_price):
                passed = False
                print("   Sai khoảng giá!")

        if passed:
            print("PASS")
        else:
            print("FAIL")

except Exception as e:
    print(e)

finally:
    input("\nNhấn Enter để đóng...")
    driver.quit()