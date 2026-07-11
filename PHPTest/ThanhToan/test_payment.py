from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

BASE_URL = "http://localhost/ktpm_nhom_12/shop"


def prepare_payment(driver):
    """
    Chuẩn bị dữ liệu:
    Đăng nhập (đã có trong conftest)
    -> Thêm 1 sản phẩm
    -> Sang trang thanh toán
    """

    driver.get(f"{BASE_URL}/sanpham.php")

    driver.find_element(
        By.XPATH,
        "(//button[contains(text(),'Mua ngay')])[1]"
    ).click()

    driver.get(f"{BASE_URL}/payment.php")

# TC8.1 Kiểm tra hiển thị thông tin thanh toán

def test_payment_information(driver):

    prepare_payment(driver)

    page = driver.page_source

    assert "Khách hàng:" in page
    assert "Tổng tiền:" in page

# TC8.2 Chọn thanh toán COD

def test_choose_cod(driver):

    prepare_payment(driver)

    cod = driver.find_element(
        By.XPATH,
        "//input[@value='COD']"
    )

    cod.click()

    assert cod.is_selected()

# TC8.3 Chọn thanh toán chuyển khoản

def test_choose_bank(driver):

    prepare_payment(driver)

    bank = driver.find_element(
        By.XPATH,
        "//input[@value='BANK']"
    )

    bank.click()

    assert bank.is_selected()

# TC8.4 Kiểm tra nút xác nhận thanh toán

def test_payment_success(driver):

    prepare_payment(driver)

    driver.find_element(
        By.XPATH,
        "//input[@value='COD']"
    ).click()

    driver.find_element(
        By.NAME,
        "payment"
    ).click()
    try:
        WebDriverWait(driver,5).until(EC.alert_is_present())
        alert = driver.switch_to.alert
        print(alert.text)
        alert.accept()
    except:
        pass

    assert "success=1" in driver.current_url

    driver.get(BASE_URL+"/view_cart.php")

    rows = driver.find_elements(
        By.XPATH,
        "//tbody/tr"
    )

    assert len(rows)==1

# TC8.5 Xem chi tiết hóa đơn

def test_download_button(driver):

    prepare_payment(driver)

    driver.find_element(
        By.XPATH,
        "//input[@value='COD']"
    ).click()

    driver.find_element(
        By.NAME,
        "payment"
    ).click()

    # Đóng alert
    try:
        WebDriverWait(driver,5).until(EC.alert_is_present())
        alert = driver.switch_to.alert
        print(alert.text)
        alert.accept()
    except:
        pass

    button = WebDriverWait(driver,10).until(
        EC.visibility_of_element_located(
            (By.LINK_TEXT,"Xem chi tiết đơn hàng")
        )
    )

    assert button.is_displayed()

#TC8.6: kiểm tra file hóa đơn được tạo

def test_invoice_file(driver):

    prepare_payment(driver)

    driver.find_element(
        By.XPATH,
        "//input[@value='COD']"
    ).click()

    driver.find_element(
        By.NAME,
        "payment"
    ).click()

    # Đóng alert
    try:
        WebDriverWait(driver,5).until(EC.alert_is_present())
        alert = driver.switch_to.alert
        print(alert.text)
        alert.accept()
    except:
        pass

    link = WebDriverWait(driver,10).until(
        EC.presence_of_element_located(
            (By.LINK_TEXT,"Xem chi tiết đơn hàng")
        )
    )

    href = link.get_attribute("href")

    assert "orders/order_" in href
    assert href.endswith(".txt")