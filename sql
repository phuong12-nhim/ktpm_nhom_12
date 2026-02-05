

CREATE DATABASE IF NOT EXISTS `webbansach` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `webbansach`;

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sdt` int(11) NOT NULL,
  `password` varchar(250) NOT NULL,
  `level` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`id`, `username`, `fullname`, `email`, `sdt`, `password`, `level`) VALUES
(1, 'dung', 'dangvietdung','dangvietdung2903@gmail.com','12345', '8478e2bdb758f8467225ae87ed3750c2', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `catelog`
--

CREATE TABLE `catelog` (
  `catelogid` int(11) NOT NULL,
  `catelogname` varchar(255) NOT NULL,
  `status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `catelog`
--

INSERT INTO `catelog` (`catelogid`, `catelogname`, `status`) VALUES
(1, 'Sách Tiếng Việt', 1),
(2, 'Sách Tiếng Anh', 1),
(3, 'Sách Toán', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdonhang`
--

CREATE TABLE `chitietdonhang` (
  `idhoadon` int(11)  NOT NULL,
  `idsanpham` int(11) DEFAULT NULL,
  `dongia` float NOT NULL,
  `soluong` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`idhoadon`, `idsanpham`, `dongia`, `soluong`) VALUES
(1, 3, 42600000, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietsanpham`
--

CREATE TABLE `chitietsanpham` (
  `idsanpham` int(11) DEFAULT NULL,
  `sizeid` int(11) DEFAULT NULL,
  `soluong` int(11) NOT NULL,
  `status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietsanpham`
--

INSERT INTO `chitietsanpham` (`idsanpham`, `soluong`, `status`) VALUES
(1, 10000, 1),
(2, 4300, 1),
(3, 60000, 1),
(4, 48000, 1),
(5, 3000, 1),
(6, 5000, 1),
(7, 2000, 1);


-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoadon`
--

CREATE TABLE `hoadon` (
  `idhoadon` int(11) NOT NULL,
  `idkhachhang` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `Tong_tien` float DEFAULT NULL,
  `Ngay_tao` datetime DEFAULT NULL,
  `soluong` int(11) DEFAULT NULL, 
  `status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hoadon`
--

INSERT INTO `hoadon` (`idhoadon`, `idkhachhang`, `name`, `address`, `phone`, `email`, `Tong_tien`, `Ngay_tao`, `soluong`, `status`) VALUES
(1, 10, 'thanh', 'hà nội', 1234677, 'thanh@gmail.com', NULL, NULL, NULL, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khachang`
--

CREATE TABLE `khachang` (
  `idkhachhang` int(11) NOT NULL,
  `tenkhachhang` varchar(255) NOT NULL,
  `phone` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `matKhau` varchar(255) NOT NULL,
  `status` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `khachang`
--

INSERT INTO `khachang` (`idkhachhang`, `tenkhachhang`, `phone`, `address`, `email`, `username`, `matKhau`, `status`) VALUES
(10, 'thanh', 123467, 'hà nội', '@gmail.com', 'thanh', 'e10adc3949ba59abbe56e057f20f883e', 0),
(11, 'than', 21474836, 'Biển đông', 'than@gmail.com', 'than', 'e10adc3949ba59abbe56e057f20f883e', 0);

-- --------------------------------------------------------


CREATE TABLE `tacgia` (
  `id_author` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id_author`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tacgia` (`name`) VALUES
('Bộ Giáo dục và Đào tạo'),
('Nguyễn Nhật Ánh');

--
-- Cấu trúc bảng cho bảng `sanpham`
--

CREATE TABLE `sanpham` (
  `idsanpham` int(11) NOT NULL,
  `catelogid` int(11) NOT NULL,
  `id_author` int(11) NOt NULL,
  `tensanpham` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `noidung` varchar(1000) DEFAULT NULL,
  `noidungchitiet` varchar(255) DEFAULT NULL,
  `giadauvao` float NOT NULL,
  `giadaura` float NOT NULL,
  `luotxem` int(11) DEFAULT NULL,
  `mua` varchar(50) NOT NULL DEFAULT 'Mua Hàng',
  `muahang` tinyint(1) DEFAULT 0,
  `status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sanpham`
--

INSERT INTO `sanpham` (`idsanpham`, `catelogid`, `id_author`, `tensanpham`, `image`, `noidung`, `noidungchitiet`, `giadauvao`, `giadaura`, `luotxem`, `mua`, `muahang`, `status`) VALUES
(1, 1, 1, 'Sách giáo khoa tiếng việt lớp 1', 'Sách_giáo_khoa_tiếng_việt_lớp_1.jpg', 'Sách giáo khoa tiếng việt lớp 1', 'Sách giáo khoa tiếng việt lớp 1', 25000, 30000, NULL, 'Mua Hàng', 0, 1),
(2, 1, 1, 'Sách giáo khoa tiếng việt lớp 2', 'Sách_giáo_khoa_tiếng_việt_lớp_2.jpg', 'Sách giáo khoa tiếng việt lớp 2', 'Sách giáo khoa tiếng việt lớp 2', 25000, 30000, NULL, 'Mua Hàng', 0, 1),
(3, 1, 1, 'Sách giáo khoa tiếng việt 3', 'Sách_giáo_khoa_tiếng_việt_3.jpg', 'Sách giáo khoa tiếng việt 3', 'Sách giáo khoa tiếng việt 3', 25000, 30000, NULL, 'Mua Hàng', 0, 1),
(4, 2, 1, 'Sách giáo khoa tiếng anh 1', 'Sách_giáo_khoa_tiếng_anh_1.jpg', 'Sách giáo khoa tiếng anh 1', 'Sách giáo khoa tiếng anh 1', 25000, 30000, NULL, 'Mua Hàng', 0, 1),
(5, 2, 1, 'Sách giáo khoa tiếng anh 2', 'Sách_giáo_khoa_tiếng_anh_2.jpg', 'Sách giáo khoa tiếng anh 2', 'Sách giáo khoa tiếng anh 2', 25000, 30000, NULL, 'Mua Hàng', 0, 1),
(6, 2, 1, 'Sách giáo khoa tiếng anh 3', 'Sách_giáo_khoa_tiếng_anh_3.jpg', 'Sách giáo khoa tiếng anh 3', 'Sách giáo khoa tiếng anh 3', 25000, 25000, NULL, 'Mua Hàng', 0, 1),
(7, 3, 1, 'Sách giáo khoa toán 1', 'Sách_giáo_khoa_toán_1.jpg', 'Sách giáo khoa toán 1', 'Sách giáo khoa toán 1', 25000, 30000, NULL, 'Mua Hàng', 0, 1),
(8, 2, 1, 'Sách giáo khoa toán 2','Sách_giáo_khoa_toán_2.jpg', 'Sách giáo khoa toán 2', 'Sách giáo khoa toán 2', 25000, 30000, NULL, 'Mua Hàng', 0, 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `catelog`
--
ALTER TABLE `catelog`
  ADD PRIMARY KEY (`catelogid`);

--
-- Chỉ mục cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD KEY `FK_ctdh` (`idhoadon`),
  ADD KEY `FK_ctdhsp` (`idsanpham`);

--
-- Chỉ mục cho bảng `chitietsanpham`
--
ALTER TABLE `chitietsanpham`
  ADD KEY `FK_ctsp` (`idsanpham`);

--
-- Chỉ mục cho bảng `hoadon`
--
ALTER TABLE `hoadon`
  ADD PRIMARY KEY (`idhoadon`),
  ADD KEY `FK_idkh` (`idkhachhang`);

--
-- Chỉ mục cho bảng `khachang`
--
ALTER TABLE `khachang`
  ADD PRIMARY KEY (`idkhachhang`);

--
-- Chỉ mục cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`idsanpham`),
  ADD KEY `FK_idcate` (`catelogid`),
  ADD KEY `FK_idauthor` (`id_author`);

--

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `catelog`
--
ALTER TABLE `catelog`
  MODIFY `catelogid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `hoadon`
--
ALTER TABLE `hoadon`
  MODIFY `idhoadon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `khachang`
--
ALTER TABLE `khachang`
  MODIFY `idkhachhang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `idsanpham` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD CONSTRAINT `FK_ctdh` FOREIGN KEY (`idhoadon`) REFERENCES `hoadon` (`idhoadon`);

--
-- Các ràng buộc cho bảng `chitietsanpham`
--
ALTER TABLE `chitietsanpham`
  ADD CONSTRAINT `FK_ctsp` FOREIGN KEY (`idsanpham`) REFERENCES `sanpham` (`idsanpham`);
--
-- Các ràng buộc cho bảng `hoadon`
--
ALTER TABLE `hoadon`
  ADD CONSTRAINT `FK_idkh` FOREIGN KEY (`idkhachhang`) REFERENCES `khachang` (`idkhachhang`);

--
-- Các ràng buộc cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `FK_idcate` FOREIGN KEY (`catelogid`) REFERENCES `catelog` (`catelogid`),
  ADD CONSTRAINT `FK_id_author` FOREIGN KEY (`id_author`) REFERENCES `tacgia` (`id_author`);






