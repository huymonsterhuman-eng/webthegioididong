-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 14, 2026 lúc 05:35 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `thegioididong_new`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `action_type` varchar(255) NOT NULL DEFAULT 'system',
  `description` text NOT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `action_type`, `description`, `properties`, `created_at`, `updated_at`, `subject_type`, `subject_id`) VALUES
(1, 2, 'update_receipt', 'system', 'huyluong đã sửa phiếu nhập #8, thay đổi số lượng Samsung Galaxy S23 Ultra từ 3 thành 4', NULL, '2026-03-24 06:59:02', '2026-03-24 06:59:02', NULL, NULL),
(2, 2, 'deduct_stock', 'inventory', 'Trừ 1 sản phẩm khỏi lô nhập #10', '{\"product_id\":9,\"quantity_deducted\":1,\"from_receipt\":10,\"remaining_in_receipt\":9,\"issue_type\":\"manual\"}', '2026-03-27 21:49:05', '2026-03-27 21:49:05', 'App\\Models\\GoodsIssue', 12),
(3, 2, 'deduct_stock', 'inventory', 'Trừ 1 sản phẩm khỏi lô nhập #40', '{\"product_id\":35,\"product_name\":\"Xiaomi Redmi 10\",\"quantity_deducted\":1,\"from_receipt_detail_id\":40,\"parent_receipt_id\":6,\"import_price\":\"4000000.00\",\"remaining_in_batch\":9}', '2026-03-27 23:38:06', '2026-03-27 23:38:06', 'App\\Models\\GoodsIssue', 13),
(4, 2, 'deduct_stock', 'inventory', 'Trừ 1 sản phẩm khỏi lô nhập #10', '{\"product_id\":9,\"product_name\":\"Samsung Galaxy M33\",\"quantity_deducted\":1,\"from_receipt_detail_id\":10,\"parent_receipt_id\":2,\"import_price\":\"4789000.00\",\"remaining_in_batch\":8}', '2026-03-27 23:38:06', '2026-03-27 23:38:06', 'App\\Models\\GoodsIssue', 13),
(5, 2, 'create_manual_issue', 'inventory', 'Đã tạo phiếu xuất kho thủ công #14 với 3 loại sản phẩm.', '{\"total_cogs\":96578000,\"item_count\":3}', '2026-03-27 23:45:39', '2026-03-27 23:45:39', 'App\\Models\\GoodsIssue', 14),
(6, 2, 'create_manual_issue', 'inventory', 'Đã tạo phiếu xuất kho thủ công #15 với 2 loại sản phẩm.', '{\"total_cogs\":31789000,\"item_count\":2,\"detailed_batches\":[{\"product_name\":\"Samsung Galaxy M33\",\"receipt_detail_id\":10,\"parent_receipt_id\":2,\"quantity_taken\":1,\"import_price\":\"4789000.00\"},{\"product_name\":\"Samsung Galaxy Z Fold 4\",\"receipt_detail_id\":3,\"parent_receipt_id\":1,\"quantity_taken\":1,\"import_price\":\"27000000.00\"}]}', '2026-03-28 00:09:40', '2026-03-28 00:09:40', 'App\\Models\\GoodsIssue', 15),
(7, 4, 'user_login', 'system', 'Tài khoản thao2k5 đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 01:42:11', '2026-03-29 01:42:11', 'App\\Models\\User', 4),
(8, 4, 'user_login', 'system', 'Tài khoản thao2k5 đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 01:42:11', '2026-03-29 01:42:11', 'App\\Models\\User', 4),
(9, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 02:24:57', '2026-03-29 02:24:57', 'App\\Models\\User', 2),
(10, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 02:24:57', '2026-03-29 02:24:57', 'App\\Models\\User', 2),
(11, 2, 'create_receipt', 'inventory', 'Đã tạo phiếu nhập kho #13 với tổng giá trị 829.350.000 ₫.', '{\"supplier_id\":3,\"total_amount\":\"829350000.00\"}', '2026-03-29 03:05:45', '2026-03-29 03:05:45', 'App\\Models\\GoodsReceipt', 13),
(12, 2, 'create_receipt', 'inventory', 'Đã tạo phiếu nhập kho #14 với tổng giá trị 484.000.000 ₫.', '{\"supplier_id\":4,\"total_amount\":\"484000000.00\"}', '2026-03-29 03:46:00', '2026-03-29 03:46:00', 'App\\Models\\GoodsReceipt', 14),
(17, 2, 'create_manual_receipt', 'inventory', 'Đã lập phiếu nhập kho #14 với 4 loại sản phẩm.', '{\"total_amount\":484000000,\"supplier_id\":4,\"item_count\":4,\"detailed_items\":[{\"product_id\":58,\"product_name\":\"Huawei Mate 30 Pro\",\"quantity\":\"11\",\"import_price\":15000000,\"receipt_detail_id\":73},{\"product_id\":59,\"product_name\":\"Huawei Nova 8\",\"quantity\":\"11\",\"import_price\":10000000,\"receipt_detail_id\":74},{\"product_id\":60,\"product_name\":\"Huawei Enjoy 20 Pro\",\"quantity\":\"11\",\"import_price\":4000000,\"receipt_detail_id\":75},{\"product_id\":62,\"product_name\":\"Sony Xperia 5 IV\",\"quantity\":\"11\",\"import_price\":15000000,\"receipt_detail_id\":76}]}', '2026-03-29 03:46:00', '2026-03-29 03:46:00', 'App\\Models\\GoodsReceipt', 14),
(18, 2, 'create_manual_receipt', 'inventory', 'Đã lập phiếu nhập kho #15 với 4 loại sản phẩm.', '{\"total_amount\":543000000,\"supplier_id\":3,\"item_count\":4,\"detailed_items\":[{\"product_id\":61,\"product_name\":\"Sony Xperia 1 IV\",\"quantity\":\"12\",\"import_price\":17000000,\"receipt_detail_id\":77},{\"product_id\":63,\"product_name\":\"Sony Xperia 10 IV\",\"quantity\":\"5\",\"import_price\":10000000,\"receipt_detail_id\":78},{\"product_id\":64,\"product_name\":\"Sony Xperia 1 III\",\"quantity\":\"6\",\"import_price\":17000000,\"receipt_detail_id\":79},{\"product_id\":65,\"product_name\":\"Sony Xperia 5 III\",\"quantity\":\"11\",\"import_price\":17000000,\"receipt_detail_id\":80}]}', '2026-03-29 03:55:03', '2026-03-29 03:55:03', 'App\\Models\\GoodsReceipt', 15),
(19, 4, 'user_login', 'system', 'Tài khoản thao2k5 đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 06:15:27', '2026-03-29 06:15:27', 'App\\Models\\User', 4),
(20, 4, 'user_login', 'system', 'Tài khoản thao2k5 đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 06:15:27', '2026-03-29 06:15:27', 'App\\Models\\User', 4),
(21, 2, 'confirmed_order', 'order', 'Admin đã xác nhận đơn hàng #28.', NULL, '2026-03-29 09:00:56', '2026-03-29 09:00:56', 'App\\Models\\Order', 28),
(22, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #45 (Sản phẩm: Xiaomi Redmi 10A) từ 11 thành 10.', '{\"old_remaining\":11,\"new_remaining\":10,\"difference\":-1,\"parent_receipt_id\":6}', '2026-03-29 09:01:40', '2026-03-29 09:01:40', 'App\\Models\\GoodsReceiptDetail', 45),
(23, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #6 (Sản phẩm: Samsung Galaxy A53) từ 9 thành 8.', '{\"old_remaining\":9,\"new_remaining\":8,\"difference\":-1,\"parent_receipt_id\":2}', '2026-03-29 09:01:40', '2026-03-29 09:01:40', 'App\\Models\\GoodsReceiptDetail', 6),
(24, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #18 (Sản phẩm: iPhone 14 Plus) từ 4 thành 3.', '{\"old_remaining\":4,\"new_remaining\":3,\"difference\":-1,\"parent_receipt_id\":3}', '2026-03-29 09:01:40', '2026-03-29 09:01:40', 'App\\Models\\GoodsReceiptDetail', 18),
(25, 2, 'auto_goods_issue', 'inventory', 'Hệ thống tự động tạo phiếu xuất kho #16 cho Đơn hàng #28.', '{\"order_id\":28,\"total_cogs\":26000000,\"detailed_batches\":[{\"product_name\":\"Xiaomi Redmi 10A\",\"receipt_detail_id\":45,\"parent_receipt_id\":6,\"quantity_taken\":1,\"import_price\":\"1000000.00\"},{\"product_name\":\"Samsung Galaxy A53\",\"receipt_detail_id\":6,\"parent_receipt_id\":2,\"quantity_taken\":1,\"import_price\":\"7000000.00\"},{\"product_name\":\"iPhone 14 Plus\",\"receipt_detail_id\":18,\"parent_receipt_id\":3,\"quantity_taken\":1,\"import_price\":\"18000000.00\"}]}', '2026-03-29 09:01:40', '2026-03-29 09:01:40', 'App\\Models\\GoodsIssue', 16),
(26, 2, 'shipping_order', 'order', 'Đơn hàng #28 đã bắt đầu giao qua Vn express', '{\"tracking_number\":\"SHIP-DTXD3W8NBI\"}', '2026-03-29 09:01:40', '2026-03-29 09:01:40', 'App\\Models\\Order', 28),
(27, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 17:23:14', '2026-03-29 17:23:14', 'App\\Models\\User', 2),
(28, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 17:23:14', '2026-03-29 17:23:14', 'App\\Models\\User', 2),
(29, 2, 'delivered_order', 'order', 'Trạng thái đơn hàng #21 thay đổi từ \'Đang giao hàng\' sang \'Đã giao thành công\'.', '{\"old_status\":\"shipping\",\"new_status\":\"delivered\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 17:55:58', '2026-03-29 17:55:58', 'App\\Models\\Order', 21),
(30, 2, 'delivered_order', 'order', 'Trạng thái đơn hàng #13 thay đổi từ \'Đã hủy\' sang \'Đã giao thành công\'.', '{\"old_status\":\"cancelled\",\"new_status\":\"delivered\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 18:26:42', '2026-03-29 18:26:42', 'App\\Models\\Order', 13),
(31, 2, 'delivered_order', 'order', 'Trạng thái đơn hàng #22 thay đổi từ \'Đang giao hàng\' sang \'Đã giao thành công\'.', '{\"old_status\":\"shipping\",\"new_status\":\"delivered\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 18:35:12', '2026-03-29 18:35:12', 'App\\Models\\Order', 22),
(32, 2, 'delivered_order', 'order', 'Trạng thái đơn hàng #23 thay đổi từ \'Đang giao hàng\' sang \'Đã giao thành công\'.', '{\"old_status\":\"shipping\",\"new_status\":\"delivered\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 18:35:46', '2026-03-29 18:35:46', 'App\\Models\\Order', 23),
(33, 2, 'created_order', 'order', 'Đơn hàng mới #30 đã được tạo.', '{\"total\":\"72020000.00\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 19:15:57', '2026-03-29 19:15:57', 'App\\Models\\Order', 30),
(34, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #58 (Sản phẩm: Samsung Galaxy S23 Ultra) từ 19 thành 18.', '{\"old_remaining\":19,\"new_remaining\":18,\"difference\":-1,\"parent_receipt_id\":10,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 19:16:29', '2026-03-29 19:16:29', 'App\\Models\\GoodsReceiptDetail', 58),
(35, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #1 (Sản phẩm: Samsung Galaxy S23+) từ 15 thành 14.', '{\"old_remaining\":15,\"new_remaining\":14,\"difference\":-1,\"parent_receipt_id\":1,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 19:16:29', '2026-03-29 19:16:29', 'App\\Models\\GoodsReceiptDetail', 1),
(36, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #5 (Sản phẩm: Samsung Galaxy S23) từ 10 thành 9.', '{\"old_remaining\":10,\"new_remaining\":9,\"difference\":-1,\"parent_receipt_id\":2,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 19:16:29', '2026-03-29 19:16:29', 'App\\Models\\GoodsReceiptDetail', 5),
(37, 2, 'auto_goods_issue', 'inventory', 'Hệ thống tự động tạo phiếu xuất kho #17 cho Đơn hàng #30.', '{\"order_id\":30,\"total_cogs\":61000000,\"detailed_batches\":[{\"product_name\":\"Samsung Galaxy S23 Ultra\",\"receipt_detail_id\":58,\"parent_receipt_id\":10,\"quantity_taken\":1,\"import_price\":\"23000000.00\"},{\"product_name\":\"Samsung Galaxy S23+\",\"receipt_detail_id\":1,\"parent_receipt_id\":1,\"quantity_taken\":1,\"import_price\":\"20000000.00\"},{\"product_name\":\"Samsung Galaxy S23\",\"receipt_detail_id\":5,\"parent_receipt_id\":2,\"quantity_taken\":1,\"import_price\":\"18000000.00\"}],\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 19:16:29', '2026-03-29 19:16:29', 'App\\Models\\GoodsIssue', 17),
(38, 2, 'shipping_order', 'order', 'Trạng thái đơn hàng #30 thay đổi từ \'Chờ xử lý\' sang \'Đang giao hàng\'.', '{\"old_status\":\"pending\",\"new_status\":\"shipping\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 19:16:29', '2026-03-29 19:16:29', 'App\\Models\\Order', 30),
(39, 2, 'updated_tracking', 'order', 'Cập nhật mã vận đơn cho đơn hàng #30: SHIP-FP09B8GQ6N.', '{\"tracking_number\":\"SHIP-FP09B8GQ6N\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 19:16:29', '2026-03-29 19:16:29', 'App\\Models\\Order', 30),
(40, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #5 (Sản phẩm: Samsung Galaxy S23) từ 9 thành 8.', '{\"old_remaining\":9,\"new_remaining\":8,\"difference\":-1,\"parent_receipt_id\":2,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 19:18:34', '2026-03-29 19:18:34', 'App\\Models\\GoodsReceiptDetail', 5),
(41, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #8 (Sản phẩm: Samsung Galaxy Note 20) từ 8 thành 7.', '{\"old_remaining\":8,\"new_remaining\":7,\"difference\":-1,\"parent_receipt_id\":2,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 19:18:34', '2026-03-29 19:18:34', 'App\\Models\\GoodsReceiptDetail', 8),
(42, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #26 (Sản phẩm: Sony Xperia 5 II) từ 11 thành 10.', '{\"old_remaining\":11,\"new_remaining\":10,\"difference\":-1,\"parent_receipt_id\":4,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 19:18:34', '2026-03-29 19:18:34', 'App\\Models\\GoodsReceiptDetail', 26),
(43, 2, 'auto_goods_issue', 'inventory', 'Hệ thống tự động tạo phiếu xuất kho #18 cho Đơn hàng #29.', '{\"order_id\":29,\"total_cogs\":47750000,\"detailed_batches\":[{\"product_name\":\"Samsung Galaxy S23\",\"receipt_detail_id\":5,\"parent_receipt_id\":2,\"quantity_taken\":1,\"import_price\":\"18000000.00\"},{\"product_name\":\"Samsung Galaxy Note 20\",\"receipt_detail_id\":8,\"parent_receipt_id\":2,\"quantity_taken\":1,\"import_price\":\"15750000.00\"},{\"product_name\":\"Sony Xperia 5 II\",\"receipt_detail_id\":26,\"parent_receipt_id\":4,\"quantity_taken\":1,\"import_price\":\"14000000.00\"}],\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 19:18:34', '2026-03-29 19:18:34', 'App\\Models\\GoodsIssue', 18),
(44, 2, 'shipping_order', 'order', 'Trạng thái đơn hàng #29 thay đổi từ \'Chờ xử lý\' sang \'Đang giao hàng\'.', '{\"old_status\":\"pending\",\"new_status\":\"shipping\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 19:18:34', '2026-03-29 19:18:34', 'App\\Models\\Order', 29),
(45, 2, 'updated_tracking', 'order', 'Cập nhật mã vận đơn cho đơn hàng #29: SHIP-N9SVT7UXXR.', '{\"tracking_number\":\"SHIP-N9SVT7UXXR\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 19:18:34', '2026-03-29 19:18:34', 'App\\Models\\Order', 29),
(46, 2, 'user_profile_updated', 'system', 'Admin huyluong đã cập nhật trường Họ tên của tài khoản huyluong: \'Lương Quốc Huy\' -> \'Lương Quốc Huy\'.', '{\"field\":\"full_name\",\"old\":\"L\\u01b0\\u01a1ng Qu\\u1ed1c Huy\",\"new\":\"L\\u01b0\\u01a1ng Qu\\u1ed1c Huy\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 21:04:32', '2026-03-29 21:04:32', 'App\\Models\\User', 2),
(47, 2, 'user_profile_updated', 'system', 'Admin huyluong đã cập nhật trường Số điện thoại của tài khoản huyluong: \'0867675025\' -> \'0867675025\'.', '{\"field\":\"phone\",\"old\":\"0867675025\",\"new\":\"0867675025\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\"}', '2026-03-29 21:04:32', '2026-03-29 21:04:32', 'App\\Models\\User', 2),
(48, 5, 'user_login', 'system', 'Tài khoản Roll đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-03-30 01:11:34', '2026-03-30 01:11:34', 'App\\Models\\User', 5),
(49, 5, 'user_login', 'system', 'Tài khoản Roll đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-03-30 01:11:34', '2026-03-30 01:11:34', 'App\\Models\\User', 5),
(50, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-03-30 06:46:45', '2026-03-30 06:46:45', 'App\\Models\\User', 2),
(51, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-03-30 06:46:45', '2026-03-30 06:46:45', 'App\\Models\\User', 2),
(52, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-03-31 20:44:59', '2026-03-31 20:44:59', 'App\\Models\\User', 2),
(53, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-03-31 20:44:59', '2026-03-31 20:44:59', 'App\\Models\\User', 2),
(54, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-04-07 20:40:02', '2026-04-07 20:40:02', 'App\\Models\\User', 2),
(55, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/146.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-04-07 20:40:02', '2026-04-07 20:40:02', 'App\\Models\\User', 2),
(56, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/147.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-04-14 21:51:04', '2026-04-14 21:51:04', 'App\\Models\\User', 2),
(57, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/147.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-04-14 21:51:04', '2026-04-14 21:51:04', 'App\\Models\\User', 2),
(58, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/147.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-04-29 08:11:20', '2026-04-29 08:11:20', 'App\\Models\\User', 2),
(59, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/147.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-04-29 08:11:20', '2026-04-29 08:11:20', 'App\\Models\\User', 2),
(60, 2, 'created_order', 'order', 'Đơn hàng mới #31 đã được tạo.', '{\"total\":\"70000000.00\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/147.0.0.0 Safari\\/537.36\"}', '2026-04-29 08:11:35', '2026-04-29 08:11:35', 'App\\Models\\Order', 31),
(61, 2, 'confirmed_order', 'order', 'Trạng thái đơn hàng #31 thay đổi từ \'Chờ xử lý\' sang \'Đã xác nhận\'.', '{\"old_status\":\"pending\",\"new_status\":\"confirmed\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/147.0.0.0 Safari\\/537.36\"}', '2026-04-29 09:03:23', '2026-04-29 09:03:23', 'App\\Models\\Order', 31),
(62, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #21 (Sản phẩm: OPPO Find X5 Pro) từ 14 thành 12.', '{\"old_remaining\":14,\"new_remaining\":12,\"difference\":-2,\"parent_receipt_id\":4,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/147.0.0.0 Safari\\/537.36\"}', '2026-04-29 09:03:49', '2026-04-29 09:03:49', 'App\\Models\\GoodsReceiptDetail', 21),
(63, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #58 (Sản phẩm: Samsung Galaxy S23 Ultra) từ 18 thành 17.', '{\"old_remaining\":18,\"new_remaining\":17,\"difference\":-1,\"parent_receipt_id\":10,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/147.0.0.0 Safari\\/537.36\"}', '2026-04-29 09:03:49', '2026-04-29 09:03:49', 'App\\Models\\GoodsReceiptDetail', 58),
(64, 2, 'auto_goods_issue', 'inventory', 'Hệ thống tự động tạo phiếu xuất kho #19 cho Đơn hàng #31.', '{\"order_id\":31,\"total_cogs\":63000000,\"detailed_batches\":[{\"product_name\":\"OPPO Find X5 Pro\",\"receipt_detail_id\":21,\"parent_receipt_id\":4,\"quantity_taken\":2,\"import_price\":\"20000000.00\"},{\"product_name\":\"Samsung Galaxy S23 Ultra\",\"receipt_detail_id\":58,\"parent_receipt_id\":10,\"quantity_taken\":1,\"import_price\":\"23000000.00\"}],\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/147.0.0.0 Safari\\/537.36\"}', '2026-04-29 09:03:49', '2026-04-29 09:03:49', 'App\\Models\\GoodsIssue', 19),
(65, 2, 'shipping_order', 'order', 'Trạng thái đơn hàng #31 thay đổi từ \'Đã xác nhận\' sang \'Đang giao hàng\'.', '{\"old_status\":\"confirmed\",\"new_status\":\"shipping\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/147.0.0.0 Safari\\/537.36\"}', '2026-04-29 09:03:49', '2026-04-29 09:03:49', 'App\\Models\\Order', 31),
(66, 2, 'updated_tracking', 'order', 'Cập nhật mã vận đơn cho đơn hàng #31: SHIP-TAVS9MYROU.', '{\"tracking_number\":\"SHIP-TAVS9MYROU\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/147.0.0.0 Safari\\/537.36\"}', '2026-04-29 09:03:49', '2026-04-29 09:03:49', 'App\\Models\\Order', 31),
(67, 2, 'delivered_order', 'order', 'Trạng thái đơn hàng #31 thay đổi từ \'Đang giao hàng\' sang \'Đã giao thành công\'.', '{\"old_status\":\"shipping\",\"new_status\":\"delivered\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/147.0.0.0 Safari\\/537.36\"}', '2026-04-29 09:04:09', '2026-04-29 09:04:09', 'App\\Models\\Order', 31),
(68, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/147.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-07 01:57:46', '2026-05-07 01:57:46', 'App\\Models\\User', 2),
(69, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/147.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-07 01:57:46', '2026-05-07 01:57:46', 'App\\Models\\User', 2),
(70, 2, 'user_logout', 'system', 'Tài khoản huyluong đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/147.0.0.0 Safari\\/537.36\"}', '2026-05-07 01:59:28', '2026-05-07 01:59:28', 'App\\Models\\User', 2),
(71, 2, 'user_logout', 'system', 'Tài khoản huyluong đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/147.0.0.0 Safari\\/537.36\"}', '2026-05-07 01:59:28', '2026-05-07 01:59:28', 'App\\Models\\User', 2),
(72, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/147.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-07 02:00:04', '2026-05-07 02:00:04', 'App\\Models\\User', 2),
(73, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/147.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-07 02:00:04', '2026-05-07 02:00:04', 'App\\Models\\User', 2),
(74, 2, 'user_logout', 'system', 'Tài khoản huyluong đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/147.0.0.0 Safari\\/537.36\"}', '2026-05-07 02:41:15', '2026-05-07 02:41:15', 'App\\Models\\User', 2),
(75, 2, 'user_logout', 'system', 'Tài khoản huyluong đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/147.0.0.0 Safari\\/537.36\"}', '2026-05-07 02:41:15', '2026-05-07 02:41:15', 'App\\Models\\User', 2),
(76, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-12 20:12:43', '2026-05-12 20:12:43', 'App\\Models\\User', 2),
(77, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-12 20:12:43', '2026-05-12 20:12:43', 'App\\Models\\User', 2),
(78, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-12 20:15:44', '2026-05-12 20:15:44', 'App\\Models\\User', 2),
(79, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-12 20:15:44', '2026-05-12 20:15:44', 'App\\Models\\User', 2),
(80, 2, 'created_order', 'order', 'Đơn hàng mới #32 đã được tạo.', '{\"total\":\"30539999.00\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-12 21:06:25', '2026-05-12 21:06:25', 'App\\Models\\Order', 32),
(81, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-19 19:25:14', '2026-05-19 19:25:14', 'App\\Models\\User', 2),
(82, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-19 19:25:14', '2026-05-19 19:25:14', 'App\\Models\\User', 2),
(83, 2, 'user_logout', 'system', 'Tài khoản huyluong đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-19 19:25:45', '2026-05-19 19:25:45', 'App\\Models\\User', 2),
(84, 2, 'user_logout', 'system', 'Tài khoản huyluong đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-19 19:25:45', '2026-05-19 19:25:45', 'App\\Models\\User', 2),
(85, 6, 'user_login', 'system', 'Tài khoản Huyền Thương đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-19 19:26:22', '2026-05-19 19:26:22', 'App\\Models\\User', 6),
(86, 6, 'user_login', 'system', 'Tài khoản Huyền Thương đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-19 19:26:22', '2026-05-19 19:26:22', 'App\\Models\\User', 6),
(87, 6, 'user_logout', 'system', 'Tài khoản Huyền Thương đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-19 19:29:31', '2026-05-19 19:29:31', 'App\\Models\\User', 6),
(88, 6, 'user_logout', 'system', 'Tài khoản Huyền Thương đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-19 19:29:31', '2026-05-19 19:29:31', 'App\\Models\\User', 6),
(89, 6, 'user_login', 'system', 'Tài khoản Huyền Thương đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-19 19:30:55', '2026-05-19 19:30:55', 'App\\Models\\User', 6),
(90, 6, 'user_login', 'system', 'Tài khoản Huyền Thương đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-19 19:30:55', '2026-05-19 19:30:55', 'App\\Models\\User', 6),
(91, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-19 21:16:02', '2026-05-19 21:16:02', 'App\\Models\\User', 2),
(92, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-19 21:16:02', '2026-05-19 21:16:02', 'App\\Models\\User', 2),
(93, 6, 'created_order', 'order', 'Đơn hàng mới #33 đã được tạo.', '{\"total\":\"29727000.00\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-19 21:20:30', '2026-05-19 21:20:30', 'App\\Models\\Order', 33),
(94, 2, 'confirmed_order', 'order', 'Trạng thái đơn hàng #33 thay đổi từ \'Chờ xử lý\' sang \'Đã xác nhận\'.', '{\"old_status\":\"pending\",\"new_status\":\"confirmed\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-19 21:21:01', '2026-05-19 21:21:01', 'App\\Models\\Order', 33),
(95, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #22 (Sản phẩm: OPPO Find X5) từ 13 thành 12.', '{\"old_remaining\":13,\"new_remaining\":12,\"difference\":-1,\"parent_receipt_id\":4,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-19 21:21:16', '2026-05-19 21:21:16', 'App\\Models\\GoodsReceiptDetail', 22),
(96, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #32 (Sản phẩm: OPPO F21 Pro) từ 12 thành 11.', '{\"old_remaining\":12,\"new_remaining\":11,\"difference\":-1,\"parent_receipt_id\":5,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-19 21:21:16', '2026-05-19 21:21:16', 'App\\Models\\GoodsReceiptDetail', 32),
(97, 2, 'auto_goods_issue', 'inventory', 'Hệ thống tự động tạo phiếu xuất kho #20 cho Đơn hàng #33.', '{\"order_id\":33,\"total_cogs\":27000000,\"detailed_batches\":[{\"product_name\":\"OPPO Find X5\",\"receipt_detail_id\":22,\"parent_receipt_id\":4,\"quantity_taken\":1,\"import_price\":\"17000000.00\"},{\"product_name\":\"OPPO F21 Pro\",\"receipt_detail_id\":32,\"parent_receipt_id\":5,\"quantity_taken\":1,\"import_price\":\"10000000.00\"}],\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-19 21:21:16', '2026-05-19 21:21:16', 'App\\Models\\GoodsIssue', 20),
(98, 2, 'shipping_order', 'order', 'Trạng thái đơn hàng #33 thay đổi từ \'Đã xác nhận\' sang \'Đang giao hàng\'.', '{\"old_status\":\"confirmed\",\"new_status\":\"shipping\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-19 21:21:16', '2026-05-19 21:21:16', 'App\\Models\\Order', 33),
(99, 2, 'updated_tracking', 'order', 'Cập nhật mã vận đơn cho đơn hàng #33: SHIP-5ZNKFW8HQ6.', '{\"tracking_number\":\"SHIP-5ZNKFW8HQ6\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-19 21:21:16', '2026-05-19 21:21:16', 'App\\Models\\Order', 33),
(100, 2, 'delivered_order', 'order', 'Trạng thái đơn hàng #33 thay đổi từ \'Đang giao hàng\' sang \'Đã giao thành công\'.', '{\"old_status\":\"shipping\",\"new_status\":\"delivered\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-19 21:22:18', '2026-05-19 21:22:18', 'App\\Models\\Order', 33),
(101, 4, 'user_login', 'system', 'Tài khoản thao2k5 đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-19 21:30:39', '2026-05-19 21:30:39', 'App\\Models\\User', 4),
(102, 4, 'user_login', 'system', 'Tài khoản thao2k5 đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-19 21:30:39', '2026-05-19 21:30:39', 'App\\Models\\User', 4),
(103, 4, 'user_logout', 'system', 'Tài khoản thao2k5 đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-19 21:31:54', '2026-05-19 21:31:54', 'App\\Models\\User', 4),
(104, 4, 'user_logout', 'system', 'Tài khoản thao2k5 đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-19 21:31:54', '2026-05-19 21:31:54', 'App\\Models\\User', 4),
(105, 4, 'user_login', 'system', 'Tài khoản thao2k5 đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-19 21:32:10', '2026-05-19 21:32:10', 'App\\Models\\User', 4),
(106, 4, 'user_login', 'system', 'Tài khoản thao2k5 đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-19 21:32:10', '2026-05-19 21:32:10', 'App\\Models\\User', 4),
(107, 4, 'created_order', 'order', 'Đơn hàng mới #34 đã được tạo.', '{\"total\":\"38709000.00\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-19 21:33:54', '2026-05-19 21:33:54', 'App\\Models\\Order', 34),
(108, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #58 (Sản phẩm: Samsung Galaxy S23 Ultra) từ 17 thành 16.', '{\"old_remaining\":17,\"new_remaining\":16,\"difference\":-1,\"parent_receipt_id\":10,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-19 21:34:22', '2026-05-19 21:34:22', 'App\\Models\\GoodsReceiptDetail', 58),
(109, 2, 'shipping_order', 'order', 'Trạng thái đơn hàng #32 thay đổi từ \'Chờ xử lý\' sang \'Đang giao hàng\'.', '{\"old_status\":\"pending\",\"new_status\":\"shipping\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-19 21:34:22', '2026-05-19 21:34:22', 'App\\Models\\Order', 32),
(110, 2, 'updated_tracking', 'order', 'Cập nhật mã vận đơn cho đơn hàng #32: SHIP-KMZNYYS50H.', '{\"tracking_number\":\"SHIP-KMZNYYS50H\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-19 21:34:22', '2026-05-19 21:34:22', 'App\\Models\\Order', 32),
(111, 2, 'confirmed_order', 'order', 'Trạng thái đơn hàng #34 thay đổi từ \'Chờ xử lý\' sang \'Đã xác nhận\'.', '{\"old_status\":\"pending\",\"new_status\":\"confirmed\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-19 21:34:28', '2026-05-19 21:34:28', 'App\\Models\\Order', 34),
(112, 4, 'user_login', 'system', 'Tài khoản thao2k5 đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-20 00:06:08', '2026-05-20 00:06:08', 'App\\Models\\User', 4),
(113, 4, 'user_login', 'system', 'Tài khoản thao2k5 đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-20 00:06:08', '2026-05-20 00:06:08', 'App\\Models\\User', 4),
(114, 4, 'created_order', 'order', 'Đơn hàng mới #35 đã được tạo.', '{\"total\":\"9030000.00\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 00:06:14', '2026-05-20 00:06:14', 'App\\Models\\Order', 35),
(115, 2, 'shipping_order', 'order', 'Trạng thái đơn hàng #35 thay đổi từ \'Chờ xử lý\' sang \'Đang giao hàng\'.', '{\"old_status\":\"pending\",\"new_status\":\"shipping\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 00:06:36', '2026-05-20 00:06:36', 'App\\Models\\Order', 35),
(116, 2, 'updated_tracking', 'order', 'Cập nhật mã vận đơn cho đơn hàng #35: SHIP-1UF1IL0VQZ.', '{\"tracking_number\":\"SHIP-1UF1IL0VQZ\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 00:06:36', '2026-05-20 00:06:36', 'App\\Models\\Order', 35),
(117, 2, 'delivered_order', 'order', 'Trạng thái đơn hàng #35 thay đổi từ \'Đang giao hàng\' sang \'Đã giao thành công\'.', '{\"old_status\":\"shipping\",\"new_status\":\"delivered\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 00:08:26', '2026-05-20 00:08:26', 'App\\Models\\Order', 35),
(118, 4, 'created_order', 'order', 'Đơn hàng mới #36 đã được tạo.', '{\"total\":\"20020000.00\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 00:18:29', '2026-05-20 00:18:29', 'App\\Models\\Order', 36),
(119, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #25 (Sản phẩm: Sony Xperia 1 II) từ 15 thành 14.', '{\"old_remaining\":15,\"new_remaining\":14,\"difference\":-1,\"parent_receipt_id\":4,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 00:18:59', '2026-05-20 00:18:59', 'App\\Models\\GoodsReceiptDetail', 25),
(120, 2, 'auto_goods_issue', 'inventory', 'Hệ thống tự động tạo phiếu xuất kho #23 cho Đơn hàng #36.', '{\"order_id\":36,\"total_cogs\":\"15000000.00\",\"detailed_batches\":[{\"product_name\":\"Sony Xperia 1 II\",\"receipt_detail_id\":25,\"parent_receipt_id\":4,\"quantity_taken\":1,\"import_price\":\"15000000.00\"}],\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 00:18:59', '2026-05-20 00:18:59', 'App\\Models\\GoodsIssue', 23),
(121, 2, 'shipping_order', 'order', 'Trạng thái đơn hàng #36 thay đổi từ \'Chờ xử lý\' sang \'Đang giao hàng\'.', '{\"old_status\":\"pending\",\"new_status\":\"shipping\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 00:18:59', '2026-05-20 00:18:59', 'App\\Models\\Order', 36),
(122, 2, 'updated_tracking', 'order', 'Cập nhật mã vận đơn cho đơn hàng #36: SHIP-ZUH67HOJFM.', '{\"tracking_number\":\"SHIP-ZUH67HOJFM\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 00:18:59', '2026-05-20 00:18:59', 'App\\Models\\Order', 36),
(123, 2, 'create_manual_receipt', 'inventory', 'Đã lập phiếu nhập kho #16 với 4 loại sản phẩm.', '{\"total_amount\":840000000,\"supplier_id\":4,\"item_count\":4,\"detailed_items\":[{\"product_id\":66,\"product_name\":\"Sony Xperia 10 III\",\"quantity\":\"10\",\"import_price\":9000000,\"receipt_detail_id\":81},{\"product_id\":72,\"product_name\":\"ASUS ROG Zephyrus G15\",\"quantity\":\"10\",\"import_price\":25000000,\"receipt_detail_id\":82},{\"product_id\":73,\"product_name\":\"ASUS ROG Strix Scar 15\",\"quantity\":\"10\",\"import_price\":30000000,\"receipt_detail_id\":83},{\"product_id\":74,\"product_name\":\"ASUS TUF Gaming F15\",\"quantity\":\"10\",\"import_price\":20000000,\"receipt_detail_id\":84}],\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 00:40:18', '2026-05-20 00:40:18', 'App\\Models\\GoodsReceipt', 16),
(124, 2, 'confirm_goods_receipt', 'inventory', 'Đã xác nhận nhập kho phiếu #16.', '{\"total_amount\":\"840000000.00\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 00:43:31', '2026-05-20 00:43:31', 'App\\Models\\GoodsReceipt', 16),
(125, 4, 'user_logout', 'system', 'Tài khoản thao2k5 đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 01:57:02', '2026-05-20 01:57:02', 'App\\Models\\User', 4),
(126, 4, 'user_logout', 'system', 'Tài khoản thao2k5 đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 01:57:02', '2026-05-20 01:57:02', 'App\\Models\\User', 4),
(127, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-20 01:57:14', '2026-05-20 01:57:14', 'App\\Models\\User', 2),
(128, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-05-20 01:57:15', '2026-05-20 01:57:15', 'App\\Models\\User', 2),
(129, 2, 'created_order', 'order', 'Đơn hàng mới #37 đã được tạo.', '{\"total\":\"9020000.00\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 01:58:49', '2026-05-20 01:58:49', 'App\\Models\\Order', 37),
(130, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #10 (Sản phẩm: Samsung Galaxy M33) từ 5 thành 4.', '{\"old_remaining\":5,\"new_remaining\":4,\"difference\":-1,\"parent_receipt_id\":2,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 01:59:48', '2026-05-20 01:59:48', 'App\\Models\\GoodsReceiptDetail', 10),
(131, 2, 'auto_goods_issue', 'inventory', 'Hệ thống tự động tạo phiếu xuất kho #24 cho Đơn hàng #37.', '{\"order_id\":37,\"total_cogs\":\"4789000.00\",\"detailed_batches\":[{\"product_name\":\"Samsung Galaxy M33\",\"receipt_detail_id\":10,\"parent_receipt_id\":2,\"quantity_taken\":1,\"import_price\":\"4789000.00\"}],\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 01:59:48', '2026-05-20 01:59:48', 'App\\Models\\GoodsIssue', 24);
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `action_type`, `description`, `properties`, `created_at`, `updated_at`, `subject_type`, `subject_id`) VALUES
(132, 2, 'shipping_order', 'order', 'Trạng thái đơn hàng #37 thay đổi từ \'Chờ xử lý\' sang \'Đang giao hàng\'.', '{\"old_status\":\"pending\",\"new_status\":\"shipping\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 01:59:48', '2026-05-20 01:59:48', 'App\\Models\\Order', 37),
(133, 2, 'updated_tracking', 'order', 'Cập nhật mã vận đơn cho đơn hàng #37: SHIP-TS9I5AT1CJ.', '{\"tracking_number\":\"SHIP-TS9I5AT1CJ\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 01:59:48', '2026-05-20 01:59:48', 'App\\Models\\Order', 37),
(134, 2, 'created_order', 'order', 'Đơn hàng mới #38 đã được tạo.', '{\"total\":\"17020000.00\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 02:11:52', '2026-05-20 02:11:52', 'App\\Models\\Order', 38),
(135, 2, 'confirmed_order', 'order', 'Trạng thái đơn hàng #38 thay đổi từ \'Chờ xử lý\' sang \'Đã xác nhận\'.', '{\"old_status\":\"pending\",\"new_status\":\"confirmed\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 02:12:17', '2026-05-20 02:12:17', 'App\\Models\\Order', 38),
(136, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #19 (Sản phẩm: iPhone SE (3rd gen)) từ 12 thành 11.', '{\"old_remaining\":12,\"new_remaining\":11,\"difference\":-1,\"parent_receipt_id\":4,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 02:13:02', '2026-05-20 02:13:02', 'App\\Models\\GoodsReceiptDetail', 19),
(137, 2, 'auto_goods_issue', 'inventory', 'Hệ thống tự động tạo phiếu xuất kho #25 cho Đơn hàng #38.', '{\"order_id\":38,\"total_cogs\":\"15000000.00\",\"detailed_batches\":[{\"product_name\":\"iPhone SE (3rd gen)\",\"receipt_detail_id\":19,\"parent_receipt_id\":4,\"quantity_taken\":1,\"import_price\":\"15000000.00\"}],\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 02:13:02', '2026-05-20 02:13:02', 'App\\Models\\GoodsIssue', 25),
(138, 2, 'shipping_order', 'order', 'Trạng thái đơn hàng #38 thay đổi từ \'Đã xác nhận\' sang \'Đang giao hàng\'.', '{\"old_status\":\"confirmed\",\"new_status\":\"shipping\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 02:13:02', '2026-05-20 02:13:02', 'App\\Models\\Order', 38),
(139, 2, 'updated_tracking', 'order', 'Cập nhật mã vận đơn cho đơn hàng #38: SHIP-DV6UXUVXMW.', '{\"tracking_number\":\"SHIP-DV6UXUVXMW\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 02:13:02', '2026-05-20 02:13:02', 'App\\Models\\Order', 38),
(140, 2, 'created_order', 'order', 'Đơn hàng mới #39 đã được tạo.', '{\"total\":\"75970000.00\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 03:59:18', '2026-05-20 03:59:18', 'App\\Models\\Order', 39),
(141, 2, 'created_order', 'order', 'Đơn hàng mới #40 đã được tạo.', '{\"total\":\"8020000.00\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 04:08:37', '2026-05-20 04:08:37', 'App\\Models\\Order', 40),
(142, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #1 (Sản phẩm: Samsung Galaxy S23+) từ 14 thành 13.', '{\"old_remaining\":14,\"new_remaining\":13,\"difference\":-1,\"parent_receipt_id\":1,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 04:08:57', '2026-05-20 04:08:57', 'App\\Models\\GoodsReceiptDetail', 1),
(143, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #25 (Sản phẩm: Sony Xperia 1 II) từ 14 thành 13.', '{\"old_remaining\":14,\"new_remaining\":13,\"difference\":-1,\"parent_receipt_id\":4,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 04:08:57', '2026-05-20 04:08:57', 'App\\Models\\GoodsReceiptDetail', 25),
(144, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #69 (Sản phẩm: Huawei Y9a) từ 5 thành 1.', '{\"old_remaining\":5,\"new_remaining\":1,\"difference\":-4,\"parent_receipt_id\":13,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 04:08:57', '2026-05-20 04:08:57', 'App\\Models\\GoodsReceiptDetail', 69),
(145, 2, 'auto_goods_issue', 'inventory', 'Hệ thống tự động tạo phiếu xuất kho #26 cho Đơn hàng #39.', '{\"order_id\":39,\"total_cogs\":\"51000000.00\",\"detailed_batches\":[{\"product_name\":\"Samsung Galaxy S23+\",\"receipt_detail_id\":1,\"parent_receipt_id\":1,\"quantity_taken\":1,\"import_price\":\"20000000.00\"},{\"product_name\":\"Sony Xperia 1 II\",\"receipt_detail_id\":25,\"parent_receipt_id\":4,\"quantity_taken\":1,\"import_price\":\"15000000.00\"},{\"product_name\":\"Huawei Y9a\",\"receipt_detail_id\":69,\"parent_receipt_id\":13,\"quantity_taken\":4,\"import_price\":\"4000000.00\"}],\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 04:08:57', '2026-05-20 04:08:57', 'App\\Models\\GoodsIssue', 26),
(146, 2, 'shipping_order', 'order', 'Trạng thái đơn hàng #39 thay đổi từ \'Chờ xử lý\' sang \'Đang giao hàng\'.', '{\"old_status\":\"pending\",\"new_status\":\"shipping\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 04:08:57', '2026-05-20 04:08:57', 'App\\Models\\Order', 39),
(147, 2, 'updated_tracking', 'order', 'Cập nhật mã vận đơn cho đơn hàng #39: SHIP-LNRLPHPPFS.', '{\"tracking_number\":\"SHIP-LNRLPHPPFS\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 04:08:57', '2026-05-20 04:08:57', 'App\\Models\\Order', 39),
(148, 2, 'confirmed_order', 'order', 'Trạng thái đơn hàng #40 thay đổi từ \'Chờ xử lý\' sang \'Đã xác nhận\'.', '{\"old_status\":\"pending\",\"new_status\":\"confirmed\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 04:09:03', '2026-05-20 04:09:03', 'App\\Models\\Order', 40),
(149, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #67 (Sản phẩm: Huawei P50 Pro) từ 8 thành 7.', '{\"old_remaining\":8,\"new_remaining\":7,\"difference\":-1,\"parent_receipt_id\":13,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 04:09:48', '2026-05-20 04:09:48', 'App\\Models\\GoodsReceiptDetail', 67),
(150, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #21 (Sản phẩm: OPPO Find X5 Pro) từ 12 thành 11.', '{\"old_remaining\":12,\"new_remaining\":11,\"difference\":-1,\"parent_receipt_id\":4,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 04:09:48', '2026-05-20 04:09:48', 'App\\Models\\GoodsReceiptDetail', 21),
(151, 2, 'auto_goods_issue', 'inventory', 'Hệ thống tự động tạo phiếu xuất kho #27 cho Đơn hàng #34.', '{\"order_id\":34,\"total_cogs\":\"35000000.00\",\"detailed_batches\":[{\"product_name\":\"Huawei P50 Pro\",\"receipt_detail_id\":67,\"parent_receipt_id\":13,\"quantity_taken\":1,\"import_price\":\"15000000.00\"},{\"product_name\":\"OPPO Find X5 Pro\",\"receipt_detail_id\":21,\"parent_receipt_id\":4,\"quantity_taken\":1,\"import_price\":\"20000000.00\"}],\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 04:09:48', '2026-05-20 04:09:48', 'App\\Models\\GoodsIssue', 27),
(152, 2, 'shipping_order', 'order', 'Trạng thái đơn hàng #34 thay đổi từ \'Đã xác nhận\' sang \'Đang giao hàng\'.', '{\"old_status\":\"confirmed\",\"new_status\":\"shipping\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 04:09:48', '2026-05-20 04:09:48', 'App\\Models\\Order', 34),
(153, 2, 'updated_tracking', 'order', 'Cập nhật mã vận đơn cho đơn hàng #34: SHIP-7MLNSGYDGD.', '{\"tracking_number\":\"SHIP-7MLNSGYDGD\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 04:09:48', '2026-05-20 04:09:48', 'App\\Models\\Order', 34),
(154, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #69 (Sản phẩm: Huawei Y9a) từ 1 thành 0.', '{\"old_remaining\":1,\"new_remaining\":0,\"difference\":-1,\"parent_receipt_id\":13,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 04:09:58', '2026-05-20 04:09:58', 'App\\Models\\GoodsReceiptDetail', 69),
(155, 2, 'auto_goods_issue', 'inventory', 'Hệ thống tự động tạo phiếu xuất kho #28 cho Đơn hàng #40.', '{\"order_id\":40,\"total_cogs\":\"4000000.00\",\"detailed_batches\":[{\"product_name\":\"Huawei Y9a\",\"receipt_detail_id\":69,\"parent_receipt_id\":13,\"quantity_taken\":1,\"import_price\":\"4000000.00\"}],\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 04:09:58', '2026-05-20 04:09:58', 'App\\Models\\GoodsIssue', 28),
(156, 2, 'shipping_order', 'order', 'Trạng thái đơn hàng #40 thay đổi từ \'Đã xác nhận\' sang \'Đang giao hàng\'.', '{\"old_status\":\"confirmed\",\"new_status\":\"shipping\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 04:09:58', '2026-05-20 04:09:58', 'App\\Models\\Order', 40),
(157, 2, 'updated_tracking', 'order', 'Cập nhật mã vận đơn cho đơn hàng #40: SHIP-XRZMEDUQIU.', '{\"tracking_number\":\"SHIP-XRZMEDUQIU\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 04:09:58', '2026-05-20 04:09:58', 'App\\Models\\Order', 40),
(158, 2, 'create_manual_receipt', 'inventory', 'Đã lập phiếu nhập kho #17 với 6 loại sản phẩm.', '{\"total_amount\":1049999980,\"supplier_id\":3,\"item_count\":6,\"detailed_items\":[{\"product_id\":55,\"product_name\":\"Huawei Y9a\",\"quantity\":\"10\",\"import_price\":4000000,\"receipt_detail_id\":85},{\"product_id\":75,\"product_name\":\"ASUS ZenBook 14 UX435\",\"quantity\":\"12\",\"import_price\":13000000,\"receipt_detail_id\":86},{\"product_id\":76,\"product_name\":\"ASUS VivoBook 15 X1504VA\",\"quantity\":\"11\",\"import_price\":14000000,\"receipt_detail_id\":87},{\"product_id\":77,\"product_name\":\"ASUS ExpertBook B9\",\"quantity\":\"10\",\"import_price\":19999999,\"receipt_detail_id\":88},{\"product_id\":78,\"product_name\":\"ASUS ROG Flow X13\",\"quantity\":\"10\",\"import_price\":19999999,\"receipt_detail_id\":89},{\"product_id\":79,\"product_name\":\"ASUS ROG Strix G17\",\"quantity\":\"10\",\"import_price\":30000000,\"receipt_detail_id\":90}],\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 04:11:18', '2026-05-20 04:11:18', 'App\\Models\\GoodsReceipt', 17),
(159, 2, 'confirm_goods_receipt', 'inventory', 'Đã xác nhận nhập kho phiếu #17.', '{\"total_amount\":\"1049999980.00\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-05-20 04:11:33', '2026-05-20 04:11:33', 'App\\Models\\GoodsReceipt', 17),
(160, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-03 01:41:25', '2026-06-03 01:41:25', 'App\\Models\\User', 2),
(161, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-03 01:41:25', '2026-06-03 01:41:25', 'App\\Models\\User', 2),
(162, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-03 02:09:20', '2026-06-03 02:09:20', 'App\\Models\\User', 2),
(163, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-03 02:09:20', '2026-06-03 02:09:20', 'App\\Models\\User', 2),
(164, 2, 'user_profile_updated', 'system', 'Admin huyluong đã cập nhật trường Họ tên của tài khoản admin: \'Lương Quốc Huy\' -> \'Lương Quốc Huy\'.', '{\"field\":\"full_name\",\"old\":\"L\\u01b0\\u01a1ng Qu\\u1ed1c Huy\",\"new\":\"L\\u01b0\\u01a1ng Qu\\u1ed1c Huy\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-03 02:11:40', '2026-06-03 02:11:40', 'App\\Models\\User', 1),
(165, 2, 'user_profile_updated', 'system', 'Admin huyluong đã cập nhật trường Số điện thoại của tài khoản admin: \'0867675025\' -> \'0867675025\'.', '{\"field\":\"phone\",\"old\":\"0867675025\",\"new\":\"0867675025\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-03 02:11:41', '2026-06-03 02:11:41', 'App\\Models\\User', 1),
(166, 2, 'user_profile_updated', 'system', 'Admin huyluong đã cập nhật trường Giới tính của tài khoản admin: \'male\' -> \'male\'.', '{\"field\":\"gender\",\"old\":\"male\",\"new\":\"male\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-03 02:11:41', '2026-06-03 02:11:41', 'App\\Models\\User', 1),
(167, 2, 'user_profile_updated', 'system', 'Admin huyluong đã cập nhật trường Ngày sinh của tài khoản admin: \'2026-06-23\' -> \'2026-06-23\'.', '{\"field\":\"birthday\",\"old\":\"2026-06-23\",\"new\":\"2026-06-23\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-03 02:11:41', '2026-06-03 02:11:41', 'App\\Models\\User', 1),
(168, 2, 'user_logout', 'system', 'Tài khoản huyluong đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-03 02:23:22', '2026-06-03 02:23:22', 'App\\Models\\User', 2),
(169, 2, 'user_logout', 'system', 'Tài khoản huyluong đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-03 02:23:22', '2026-06-03 02:23:22', 'App\\Models\\User', 2),
(170, 7, 'user_login', 'system', 'Tài khoản huynickphu đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-03 02:24:22', '2026-06-03 02:24:22', 'App\\Models\\User', 7),
(171, 7, 'user_login', 'system', 'Tài khoản huynickphu đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-03 02:24:22', '2026-06-03 02:24:22', 'App\\Models\\User', 7),
(172, 7, 'user_logout', 'system', 'Tài khoản huynickphu đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-03 02:28:41', '2026-06-03 02:28:41', 'App\\Models\\User', 7),
(173, 7, 'user_logout', 'system', 'Tài khoản huynickphu đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-03 02:28:41', '2026-06-03 02:28:41', 'App\\Models\\User', 7),
(174, 7, 'user_login', 'system', 'Tài khoản huynickphu đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-03 02:29:10', '2026-06-03 02:29:10', 'App\\Models\\User', 7),
(175, 7, 'user_login', 'system', 'Tài khoản huynickphu đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-03 02:29:10', '2026-06-03 02:29:10', 'App\\Models\\User', 7),
(176, 2, 'user_profile_updated', 'system', 'Admin huyluong đã cập nhật trường Trạng thái của tài khoản huynickphu: \'banned\' -> \'banned\'.', '{\"field\":\"status\",\"old\":\"banned\",\"new\":\"banned\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-03 02:30:25', '2026-06-03 02:30:25', 'App\\Models\\User', 7),
(177, 7, 'created_order', 'order', 'Đơn hàng mới #41 đã được tạo.', '{\"total\":\"50030000.00\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-03 02:31:12', '2026-06-03 02:31:12', 'App\\Models\\Order', 41),
(178, 7, 'user_logout', 'system', 'Tài khoản huynickphu đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-03 02:32:01', '2026-06-03 02:32:01', 'App\\Models\\User', 7),
(179, 7, 'user_logout', 'system', 'Tài khoản huynickphu đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-03 02:32:01', '2026-06-03 02:32:01', 'App\\Models\\User', 7),
(180, 7, 'user_login', 'system', 'Tài khoản huynickphu đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-03 02:32:15', '2026-06-03 02:32:15', 'App\\Models\\User', 7),
(181, 7, 'user_login', 'system', 'Tài khoản huynickphu đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-03 02:32:15', '2026-06-03 02:32:15', 'App\\Models\\User', 7),
(182, 2, 'user_profile_updated', 'system', 'Admin huyluong đã cập nhật trường Trạng thái của tài khoản huynickphu: \'banned\' -> \'banned\'.', '{\"field\":\"status\",\"old\":\"banned\",\"new\":\"banned\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-03 02:41:00', '2026-06-03 02:41:00', 'App\\Models\\User', 7),
(183, 7, 'user_logout', 'system', 'Tài khoản huynickphu đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-03 02:41:08', '2026-06-03 02:41:08', 'App\\Models\\User', 7),
(184, 7, 'user_logout', 'system', 'Tài khoản huynickphu đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-03 02:41:08', '2026-06-03 02:41:08', 'App\\Models\\User', 7),
(185, 7, 'user_login', 'system', 'Tài khoản huynickphu đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-03 02:41:22', '2026-06-03 02:41:22', 'App\\Models\\User', 7),
(186, 7, 'user_login', 'system', 'Tài khoản huynickphu đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-03 02:41:22', '2026-06-03 02:41:22', 'App\\Models\\User', 7),
(187, 7, 'user_logout', 'system', 'Tài khoản huynickphu đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-03 02:41:22', '2026-06-03 02:41:22', 'App\\Models\\User', 7),
(188, 7, 'user_logout', 'system', 'Tài khoản huynickphu đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-03 02:41:22', '2026-06-03 02:41:22', 'App\\Models\\User', 7),
(189, 8, 'user_login', 'system', 'Tài khoản dungdepzaivcl đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-03 02:42:11', '2026-06-03 02:42:11', 'App\\Models\\User', 8),
(190, 8, 'user_login', 'system', 'Tài khoản dungdepzaivcl đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-03 02:42:11', '2026-06-03 02:42:11', 'App\\Models\\User', 8),
(191, 8, 'user_logout', 'system', 'Tài khoản dungdepzaivcl đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-03 02:42:16', '2026-06-03 02:42:16', 'App\\Models\\User', 8),
(192, 8, 'user_logout', 'system', 'Tài khoản dungdepzaivcl đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-03 02:42:16', '2026-06-03 02:42:16', 'App\\Models\\User', 8),
(193, 8, 'user_login', 'system', 'Tài khoản dungdepzaivcl đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-03 02:42:45', '2026-06-03 02:42:45', 'App\\Models\\User', 8),
(194, 8, 'user_login', 'system', 'Tài khoản dungdepzaivcl đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-03 02:42:45', '2026-06-03 02:42:45', 'App\\Models\\User', 8),
(195, 2, 'user_logout', 'system', 'Tài khoản huyluong đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-03 02:44:12', '2026-06-03 02:44:12', 'App\\Models\\User', 2),
(196, 2, 'user_logout', 'system', 'Tài khoản huyluong đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-03 02:44:12', '2026-06-03 02:44:12', 'App\\Models\\User', 2),
(197, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-03 06:39:18', '2026-06-03 06:39:18', 'App\\Models\\User', 2),
(198, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-03 06:39:18', '2026-06-03 06:39:18', 'App\\Models\\User', 2),
(199, 7, 'user_login', 'system', 'Tài khoản huynickphu đã đăng nhập hệ thống.', '{\"ip_address\":\"42.113.79.148\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"42.113.79.148\"}', '2026-06-03 08:00:51', '2026-06-03 08:00:51', 'App\\Models\\User', 7),
(200, 7, 'user_login', 'system', 'Tài khoản huynickphu đã đăng nhập hệ thống.', '{\"ip_address\":\"42.113.79.148\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"42.113.79.148\"}', '2026-06-03 08:00:51', '2026-06-03 08:00:51', 'App\\Models\\User', 7),
(201, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"42.113.79.148\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"42.113.79.148\"}', '2026-06-03 08:04:51', '2026-06-03 08:04:51', 'App\\Models\\User', 2),
(202, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"42.113.79.148\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"42.113.79.148\"}', '2026-06-03 08:04:51', '2026-06-03 08:04:51', 'App\\Models\\User', 2),
(203, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-03 23:27:23', '2026-06-03 23:27:23', 'App\\Models\\User', 2),
(204, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-03 23:27:23', '2026-06-03 23:27:23', 'App\\Models\\User', 2),
(205, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-04 02:12:33', '2026-06-04 02:12:33', 'App\\Models\\User', 2),
(206, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-04 02:12:33', '2026-06-04 02:12:33', 'App\\Models\\User', 2),
(207, 2, 'created_order', 'order', 'Đơn hàng mới #42 đã được tạo.', '{\"total\":\"51020000.00\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:13:29', '2026-06-04 02:13:29', 'App\\Models\\Order', 42),
(208, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-04 02:14:00', '2026-06-04 02:14:00', 'App\\Models\\User', 2),
(209, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-04 02:14:00', '2026-06-04 02:14:00', 'App\\Models\\User', 2),
(210, 2, 'confirmed_order', 'order', 'Trạng thái đơn hàng #42 thay đổi từ \'Chờ xử lý\' sang \'Đã xác nhận\'.', '{\"old_status\":\"pending\",\"new_status\":\"confirmed\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:17:20', '2026-06-04 02:17:20', 'App\\Models\\Order', 42),
(211, 2, 'order_preparing', 'inventory', 'Đơn hàng #42 chuyển sang chuẩn bị hàng. Phiếu xuất #29 tạo chờ kho duyệt.', '{\"order_id\":42,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:19:34', '2026-06-04 02:19:34', 'App\\Models\\GoodsIssue', 29),
(212, 2, 'updated_status', 'order', 'Trạng thái đơn hàng #42 thay đổi từ \'Đã xác nhận\' sang \'preparing\'.', '{\"old_status\":\"confirmed\",\"new_status\":\"preparing\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:19:34', '2026-06-04 02:19:34', 'App\\Models\\Order', 42),
(213, 2, 'updated_tracking', 'order', 'Cập nhật mã vận đơn cho đơn hàng #42: SHIP-65HV2LQUNJ.', '{\"tracking_number\":\"SHIP-65HV2LQUNJ\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:19:34', '2026-06-04 02:19:34', 'App\\Models\\Order', 42),
(214, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #3 (Sản phẩm: Samsung Galaxy Z Fold 4) từ 3 thành 2.', '{\"old_remaining\":3,\"new_remaining\":2,\"difference\":-1,\"parent_receipt_id\":1,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:23:13', '2026-06-04 02:23:13', 'App\\Models\\GoodsReceiptDetail', 3),
(215, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #21 (Sản phẩm: OPPO Find X5 Pro) từ 11 thành 10.', '{\"old_remaining\":11,\"new_remaining\":10,\"difference\":-1,\"parent_receipt_id\":4,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:23:13', '2026-06-04 02:23:13', 'App\\Models\\GoodsReceiptDetail', 21),
(216, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #45 (Sản phẩm: Xiaomi Redmi 10A) từ 10 thành 9.', '{\"old_remaining\":10,\"new_remaining\":9,\"difference\":-1,\"parent_receipt_id\":6,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:23:14', '2026-06-04 02:23:14', 'App\\Models\\GoodsReceiptDetail', 45),
(217, 2, 'shipping_order', 'order', 'Trạng thái đơn hàng #42 thay đổi từ \'preparing\' sang \'Đang giao hàng\'.', '{\"old_status\":\"preparing\",\"new_status\":\"shipping\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:23:14', '2026-06-04 02:23:14', 'App\\Models\\Order', 42),
(218, 2, 'order_shipped', 'inventory', 'Phiếu xuất #29 hoàn thành. Đơn #42 → Đang giao hàng.', '{\"order_id\":42,\"batches\":[{\"product_name\":\"Samsung Galaxy Z Fold 4\",\"receipt_detail_id\":3,\"parent_receipt_id\":1,\"quantity_taken\":1,\"import_price\":\"27000000.00\"},{\"product_name\":\"OPPO Find X5 Pro\",\"receipt_detail_id\":21,\"parent_receipt_id\":4,\"quantity_taken\":1,\"import_price\":\"20000000.00\"},{\"product_name\":\"Xiaomi Redmi 10A\",\"receipt_detail_id\":45,\"parent_receipt_id\":6,\"quantity_taken\":1,\"import_price\":\"1000000.00\"}],\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:23:14', '2026-06-04 02:23:14', 'App\\Models\\GoodsIssue', 29),
(219, 2, 'delivered_order', 'order', 'Trạng thái đơn hàng #42 thay đổi từ \'Đang giao hàng\' sang \'Đã giao thành công\'.', '{\"old_status\":\"shipping\",\"new_status\":\"delivered\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:24:34', '2026-06-04 02:24:34', 'App\\Models\\Order', 42),
(220, 2, 'created_order', 'order', 'Đơn hàng mới #43 đã được tạo.', '{\"total\":\"22040000.00\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:26:06', '2026-06-04 02:26:06', 'App\\Models\\Order', 43),
(221, 2, 'confirmed_order', 'order', 'Trạng thái đơn hàng #43 thay đổi từ \'Chờ xử lý\' sang \'Đã xác nhận\'.', '{\"old_status\":\"pending\",\"new_status\":\"confirmed\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:27:48', '2026-06-04 02:27:48', 'App\\Models\\Order', 43),
(222, 2, 'order_preparing', 'inventory', 'Đơn hàng #43 chuyển sang chuẩn bị hàng. Phiếu xuất #30 tạo chờ kho duyệt.', '{\"order_id\":43,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:27:59', '2026-06-04 02:27:59', 'App\\Models\\GoodsIssue', 30),
(223, 2, 'updated_status', 'order', 'Trạng thái đơn hàng #43 thay đổi từ \'Đã xác nhận\' sang \'preparing\'.', '{\"old_status\":\"confirmed\",\"new_status\":\"preparing\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:27:59', '2026-06-04 02:27:59', 'App\\Models\\Order', 43),
(224, 2, 'updated_tracking', 'order', 'Cập nhật mã vận đơn cho đơn hàng #43: SHIP-NTVDZGGOC5.', '{\"tracking_number\":\"SHIP-NTVDZGGOC5\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:27:59', '2026-06-04 02:27:59', 'App\\Models\\Order', 43),
(225, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #86 (Sản phẩm: ASUS ZenBook 14 UX435) từ 12 thành 11.', '{\"old_remaining\":12,\"new_remaining\":11,\"difference\":-1,\"parent_receipt_id\":17,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:28:15', '2026-06-04 02:28:15', 'App\\Models\\GoodsReceiptDetail', 86),
(226, 2, 'shipping_order', 'order', 'Trạng thái đơn hàng #43 thay đổi từ \'preparing\' sang \'Đang giao hàng\'.', '{\"old_status\":\"preparing\",\"new_status\":\"shipping\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:28:15', '2026-06-04 02:28:15', 'App\\Models\\Order', 43),
(227, 2, 'order_shipped', 'inventory', 'Phiếu xuất #30 hoàn thành. Đơn #43 → Đang giao hàng.', '{\"order_id\":43,\"batches\":[{\"product_name\":\"ASUS ZenBook 14 UX435\",\"receipt_detail_id\":86,\"parent_receipt_id\":17,\"quantity_taken\":1,\"import_price\":\"13000000.00\"}],\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:28:15', '2026-06-04 02:28:15', 'App\\Models\\GoodsIssue', 30),
(228, 2, 'delivered_order', 'order', 'Trạng thái đơn hàng #24 thay đổi từ \'Đang giao hàng\' sang \'Đã giao thành công\'.', '{\"old_status\":\"shipping\",\"new_status\":\"delivered\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:29:05', '2026-06-04 02:29:05', 'App\\Models\\Order', 24),
(229, 2, 'delivered_order', 'order', 'Trạng thái đơn hàng #43 thay đổi từ \'Đang giao hàng\' sang \'Đã giao thành công\'.', '{\"old_status\":\"shipping\",\"new_status\":\"delivered\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:29:29', '2026-06-04 02:29:29', 'App\\Models\\Order', 43),
(230, 2, 'created_order', 'order', 'Đơn hàng mới #44 đã được tạo.', '{\"total\":\"33020000.00\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:33:30', '2026-06-04 02:33:30', 'App\\Models\\Order', 44),
(231, 2, 'confirmed_order', 'order', 'Trạng thái đơn hàng #44 thay đổi từ \'Chờ xử lý\' sang \'Đã xác nhận\'.', '{\"old_status\":\"pending\",\"new_status\":\"confirmed\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:33:59', '2026-06-04 02:33:59', 'App\\Models\\Order', 44),
(232, 2, 'order_preparing', 'inventory', 'Đơn hàng #44 chuyển sang chuẩn bị hàng. Phiếu xuất #31 tạo chờ kho duyệt.', '{\"order_id\":44,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:34:13', '2026-06-04 02:34:13', 'App\\Models\\GoodsIssue', 31),
(233, 2, 'updated_status', 'order', 'Trạng thái đơn hàng #44 thay đổi từ \'Đã xác nhận\' sang \'preparing\'.', '{\"old_status\":\"confirmed\",\"new_status\":\"preparing\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:34:13', '2026-06-04 02:34:13', 'App\\Models\\Order', 44),
(234, 2, 'updated_tracking', 'order', 'Cập nhật mã vận đơn cho đơn hàng #44: SHIP-A7AEAMMUMB.', '{\"tracking_number\":\"SHIP-A7AEAMMUMB\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:34:13', '2026-06-04 02:34:13', 'App\\Models\\Order', 44),
(235, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #29 (Sản phẩm: ASUS ROG Zephyrus G14) từ 13 thành 12.', '{\"old_remaining\":13,\"new_remaining\":12,\"difference\":-1,\"parent_receipt_id\":4,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:34:38', '2026-06-04 02:34:38', 'App\\Models\\GoodsReceiptDetail', 29),
(236, 2, 'shipping_order', 'order', 'Trạng thái đơn hàng #44 thay đổi từ \'preparing\' sang \'Đang giao hàng\'.', '{\"old_status\":\"preparing\",\"new_status\":\"shipping\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:34:38', '2026-06-04 02:34:38', 'App\\Models\\Order', 44),
(237, 2, 'order_shipped', 'inventory', 'Phiếu xuất #31 hoàn thành. Đơn #44 → Đang giao hàng.', '{\"order_id\":44,\"batches\":[{\"product_name\":\"ASUS ROG Zephyrus G14\",\"receipt_detail_id\":29,\"parent_receipt_id\":4,\"quantity_taken\":1,\"import_price\":\"29000000.00\"}],\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:34:38', '2026-06-04 02:34:38', 'App\\Models\\GoodsIssue', 31),
(238, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #29 (Sản phẩm: ASUS ROG Zephyrus G14) từ 12 thành 13.', '{\"old_remaining\":12,\"new_remaining\":13,\"difference\":1,\"parent_receipt_id\":4,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:35:15', '2026-06-04 02:35:15', 'App\\Models\\GoodsReceiptDetail', 29),
(239, 2, 'cancelled_order', 'order', 'Trạng thái đơn hàng #44 thay đổi từ \'Đang giao hàng\' sang \'Đã hủy\'.', '{\"old_status\":\"shipping\",\"new_status\":\"cancelled\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', '2026-06-04 02:35:15', '2026-06-04 02:35:15', 'App\\Models\\Order', 44),
(240, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-06 03:30:03', '2026-06-06 03:30:03', 'App\\Models\\User', 2),
(241, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-06-06 03:30:03', '2026-06-06 03:30:03', 'App\\Models\\User', 2),
(242, 2, 'confirmed_order', 'order', 'Trạng thái đơn hàng #41 thay đổi từ \'Chờ xử lý\' sang \'Đã xác nhận\'.', '{\"old_status\":\"pending\",\"new_status\":\"confirmed\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}', '2026-06-06 03:32:14', '2026-06-06 03:32:14', 'App\\Models\\Order', 41),
(243, 2, 'order_preparing', 'inventory', 'Đơn hàng #41 chuyển sang chuẩn bị hàng. Phiếu xuất #32 tạo chờ kho duyệt.', '{\"order_id\":41,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}', '2026-06-06 03:32:36', '2026-06-06 03:32:36', 'App\\Models\\GoodsIssue', 32),
(244, 2, 'updated_status', 'order', 'Trạng thái đơn hàng #41 thay đổi từ \'Đã xác nhận\' sang \'preparing\'.', '{\"old_status\":\"confirmed\",\"new_status\":\"preparing\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}', '2026-06-06 03:32:36', '2026-06-06 03:32:36', 'App\\Models\\Order', 41),
(245, 2, 'updated_tracking', 'order', 'Cập nhật mã vận đơn cho đơn hàng #41: SHIP-RECLQ4V4WK.', '{\"tracking_number\":\"SHIP-RECLQ4V4WK\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}', '2026-06-06 03:32:36', '2026-06-06 03:32:36', 'App\\Models\\Order', 41),
(246, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #1 (Sản phẩm: Samsung Galaxy S23+) từ 13 thành 12.', '{\"old_remaining\":13,\"new_remaining\":12,\"difference\":-1,\"parent_receipt_id\":1,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}', '2026-06-06 03:34:05', '2026-06-06 03:34:05', 'App\\Models\\GoodsReceiptDetail', 1),
(247, 2, 'update_receipt_detail_remaining', 'inventory', 'Đã cập nhật số lượng tồn kho của lô hàng #58 (Sản phẩm: Samsung Galaxy S23 Ultra) từ 16 thành 15.', '{\"old_remaining\":16,\"new_remaining\":15,\"difference\":-1,\"parent_receipt_id\":10,\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}', '2026-06-06 03:34:05', '2026-06-06 03:34:05', 'App\\Models\\GoodsReceiptDetail', 58),
(248, 2, 'shipping_order', 'order', 'Trạng thái đơn hàng #41 thay đổi từ \'preparing\' sang \'Đang giao hàng\'.', '{\"old_status\":\"preparing\",\"new_status\":\"shipping\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}', '2026-06-06 03:34:05', '2026-06-06 03:34:05', 'App\\Models\\Order', 41),
(249, 2, 'order_shipped', 'inventory', 'Phiếu xuất #32 hoàn thành. Đơn #41 → Đang giao hàng.', '{\"order_id\":41,\"batches\":[{\"product_name\":\"Samsung Galaxy S23+\",\"receipt_detail_id\":1,\"parent_receipt_id\":1,\"quantity_taken\":1,\"import_price\":\"20000000.00\"},{\"product_name\":\"Samsung Galaxy S23 Ultra\",\"receipt_detail_id\":58,\"parent_receipt_id\":10,\"quantity_taken\":1,\"import_price\":\"23000000.00\"}],\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}', '2026-06-06 03:34:05', '2026-06-06 03:34:05', 'App\\Models\\GoodsIssue', 32),
(250, 2, 'delivered_order', 'order', 'Trạng thái đơn hàng #41 thay đổi từ \'Đang giao hàng\' sang \'Đã giao thành công\'.', '{\"old_status\":\"shipping\",\"new_status\":\"delivered\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}', '2026-06-06 03:35:22', '2026-06-06 03:35:22', 'App\\Models\\Order', 41),
(251, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-07-11 08:39:55', '2026-07-11 08:39:55', 'App\\Models\\User', 2),
(252, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-07-11 08:39:55', '2026-07-11 08:39:55', 'App\\Models\\User', 2),
(253, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-07-13 07:51:12', '2026-07-13 07:51:12', 'App\\Models\\User', 2);
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `action_type`, `description`, `properties`, `created_at`, `updated_at`, `subject_type`, `subject_id`) VALUES
(254, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-07-13 07:51:12', '2026-07-13 07:51:12', 'App\\Models\\User', 2),
(255, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-07-13 19:42:43', '2026-07-13 19:42:43', 'App\\Models\\User', 2),
(256, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-07-13 19:42:43', '2026-07-13 19:42:43', 'App\\Models\\User', 2),
(257, 2, 'created_order', 'order', 'Đơn hàng mới #45 đã được tạo.', '{\"total\":\"21020000.00\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\"}', '2026-07-13 19:47:25', '2026-07-13 19:47:25', 'App\\Models\\Order', 45),
(258, 2, 'user_logout', 'system', 'Tài khoản huyluong đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\"}', '2026-07-13 20:30:54', '2026-07-13 20:30:54', 'App\\Models\\User', 2),
(259, 2, 'user_logout', 'system', 'Tài khoản huyluong đã đăng xuất.', '{\"ip_address\":\"127.0.0.1\",\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\"}', '2026-07-13 20:30:54', '2026-07-13 20:30:54', 'App\\Models\\User', 2),
(260, 7, 'user_login', 'system', 'Tài khoản huynickphu đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-07-13 20:32:00', '2026-07-13 20:32:00', 'App\\Models\\User', 7),
(261, 7, 'user_login', 'system', 'Tài khoản huynickphu đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-07-13 20:32:00', '2026-07-13 20:32:00', 'App\\Models\\User', 7),
(262, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-07-13 20:33:28', '2026-07-13 20:33:28', 'App\\Models\\User', 2),
(263, 2, 'user_login', 'system', 'Tài khoản huyluong đã đăng nhập hệ thống.', '{\"ip_address\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36\",\"ip\":\"127.0.0.1\"}', '2026-07-13 20:33:28', '2026-07-13 20:33:28', 'App\\Models\\User', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `name`, `phone`, `address`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 2, 'Lương Quốc Huy', '0902161559', 'số 127 tổ dân phố cơ khí yên viên', 1, '2026-03-28 01:06:27', '2026-03-28 01:06:27'),
(3, 4, 'thao2k5', '0893083042', 'SỐ 127 NGÕ 117 TDP CƠ KHÍ YÊN VIÊN', 1, '2026-03-29 02:01:57', '2026-03-29 02:01:57'),
(4, 6, 'Huyền Thương', '0867675098', 'SỐ 5 NGÕ 117 QUẬN TÂN BÌNH', 1, '2026-05-19 21:20:30', '2026-05-19 21:20:30'),
(5, 7, 'huynickphu', '0893083042', 'sô 10 Nguyễn Công Minh', 1, '2026-06-03 02:31:12', '2026-06-03 02:31:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `author_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `type` enum('carousel','popup','sidebar') NOT NULL DEFAULT 'carousel',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `banners`
--

INSERT INTO `banners` (`id`, `author_id`, `title`, `image`, `link`, `sort_order`, `is_active`, `start_date`, `end_date`, `type`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Giảm giá lên đến 35%', 'banners/01KKVH4T0T5M0B6HNGCNVJMXMM.png', 'http://127.0.0.1:8000/May-cu/tablet', 1, 1, NULL, NULL, 'carousel', '2026-03-16 07:08:02', '2026-03-27 20:45:51'),
(2, NULL, 'up to 7.56%', 'banners/01KKVH7FYFMH82WPBVM1GQZ46Z.jpg', 'http://127.0.0.1:8000/May-cu/tablet', 1, 1, NULL, NULL, 'carousel', '2026-03-16 07:36:32', '2026-03-16 07:36:32'),
(3, NULL, 'Giảm giá hấp dẫn ', 'banners/01KKVHA0RFZG0MZG22TFBWHF3C.jpg', 'http://127.0.0.1:8000/dien-thoai/huawei-p50-pro-53', 2, 1, NULL, NULL, 'carousel', '2026-03-16 07:37:55', '2026-03-16 07:37:55'),
(4, 2, 'Giảm giá siêu hấp dẫn ', 'banners/01KMS8VBDJ98QYH16RBSGR0Z23.jpg', 'http://127.0.0.1:8000/May-cu/tablet', 4, 1, NULL, NULL, 'carousel', '2026-03-27 20:47:19', '2026-03-27 20:47:19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Samsung', 'samsung', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(2, 'Apple', 'apple', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(3, 'OPPO', 'oppo', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(4, 'Xiaomi', 'xiaomi', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(5, 'Google', 'google', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(6, 'Huawei', 'huawei', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(7, 'Sony', 'sony', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(8, 'Asus', 'asus', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(9, 'Msi', 'msi', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(10, 'Dell', 'dell', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(11, 'HP', 'hp', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(12, 'Microsoft', 'microsoft', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(13, 'Lenovo', 'lenovo', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(14, 'JBL', 'jbl', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(15, 'Anker', 'anker', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(16, 'RAVPower', 'ravpower', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(17, 'Logitech', 'logitech', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(18, 'Razer', 'razer', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(19, 'Corsair', 'corsair', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(20, 'SteelSeries', 'steelseries', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(21, 'Akko', 'akko', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(22, 'Keychron', 'keychron', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(23, 'Energizer', 'energizer', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(24, 'Baseus', 'baseus', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(25, 'Bose', 'bose', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(26, 'Marshall', 'marshall', NULL, 1, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('thegioididong-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1783999923),
('thegioididong-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1783999923;', 1783999923),
('thegioididong-cache-902ba3cda1883801594b6e1b452790cc53948fda', 'i:1;', 1783999989),
('thegioididong-cache-902ba3cda1883801594b6e1b452790cc53948fda:timer', 'i:1783999989;', 1783999989),
('thegioididong-cache-annguyenhandsome99@gmail.com|127.0.0.1', 'i:4;', 1783999935),
('thegioididong-cache-annguyenhandsome99@gmail.com|127.0.0.1:timer', 'i:1783999935;', 1783999935),
('thegioididong-cache-chatbot:active_vouchers', 'a:2:{s:7:\"results\";a:0:{}s:7:\"message\";s:51:\"Hiện chưa có voucher nào đang hoạt động.\";}', 1783997353),
('thegioididong-cache-chatbot:hot_products:3', 'a:2:{s:5:\"count\";i:3;s:7:\"results\";a:3:{i:0;a:11:{s:4:\"name\";s:6:\"Tablet\";s:4:\"slug\";s:6:\"tablet\";s:3:\"sku\";N;s:5:\"price\";s:11:\"5.000.000đ\";s:10:\"sale_price\";s:11:\"4.499.999đ\";s:5:\"stock\";i:0;s:15:\"available_stock\";i:0;s:8:\"category\";s:19:\"Máy Cũ - Giá cũ\";s:5:\"brand\";s:6:\"Huawei\";s:11:\"is_featured\";b:0;s:3:\"url\";s:35:\"http://127.0.0.1:8000/May-cu/tablet\";}i:1;a:11:{s:4:\"name\";s:24:\"Samsung Galaxy S23 Ultra\";s:4:\"slug\";s:24:\"samsung-galaxy-s23-ultra\";s:3:\"sku\";N;s:5:\"price\";s:12:\"25.990.000đ\";s:10:\"sale_price\";N;s:5:\"stock\";i:15;s:15:\"available_stock\";i:15;s:8:\"category\";s:16:\"Điện Thoại \";s:5:\"brand\";s:7:\"Samsung\";s:11:\"is_featured\";b:0;s:3:\"url\";s:57:\"http://127.0.0.1:8000/dien-thoai/samsung-galaxy-s23-ultra\";}i:2;a:11:{s:4:\"name\";s:19:\"Samsung Galaxy S23+\";s:4:\"slug\";s:18:\"samsung-galaxy-s23\";s:3:\"sku\";N;s:5:\"price\";s:12:\"23.990.000đ\";s:10:\"sale_price\";N;s:5:\"stock\";i:31;s:15:\"available_stock\";i:31;s:8:\"category\";s:16:\"Điện Thoại \";s:5:\"brand\";s:7:\"Samsung\";s:11:\"is_featured\";b:0;s:3:\"url\";s:51:\"http://127.0.0.1:8000/dien-thoai/samsung-galaxy-s23\";}}}', 1783996930),
('thegioididong-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0', 'i:2;', 1783999597),
('thegioididong-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0:timer', 'i:1783999597;', 1783999597),
('thegioididong-cache-livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:2;', 1784000060),
('thegioididong-cache-livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1784000060;', 1784000060),
('thegioididong-cache-livewire-rate-limiter:b1fe581b2af4297a970a7af7337d88d4a50f5ba1', 'i:1;', 1780499150),
('thegioididong-cache-livewire-rate-limiter:b1fe581b2af4297a970a7af7337d88d4a50f5ba1:timer', 'i:1780499150;', 1780499150),
('thegioididong-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:41:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:14:\"view_dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:11:\"view_orders\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:4;i:2;i:5;i:3;i:6;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:11:\"edit_orders\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:14:\"confirm_orders\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:13:\"view_products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:3;i:2;i:4;i:3;i:5;i:4;i:6;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:13:\"edit_products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:3;i:2;i:5;i:3;i:6;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:10:\"view_users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:4;i:2;i:5;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:10:\"edit_users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:12:\"view_reviews\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:4;i:2;i:5;i:3;i:6;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:16:\"moderate_reviews\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:14:\"view_inventory\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:14:\"manage_banners\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:15:\"manage_vouchers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:25:\"manage_shipping_providers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:16:\"manage_suppliers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:5;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:12:\"manage_roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:12:\"access_admin\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:12:\"manage_posts\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:14:\"edit_inventory\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:13:\"manage_brands\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:17:\"manage_categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:16:\"create_inventory\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:15:\"create_products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:15:\"delete_products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:15:\"view_categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:6;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:11:\"view_brands\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:6;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:13:\"manage_orders\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:6;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:12:\"manage_users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:13:\"view_vouchers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:6;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:14:\"manage_reviews\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:6;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:13:\"view_partners\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:6;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:15:\"manage_partners\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:12:\"view_reports\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:16:\"view_system_logs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:18:\"view_activity_logs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:15:\"view_order_logs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:18:\"manage_collections\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:16:\"manage_inventory\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:20:\"manage_goods_receipt\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:18:\"manage_goods_issue\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:15:\"manage_shipping\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}}s:5:\"roles\";a:6:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"super-admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:11:\"Sales Staff\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:4:\"test\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:6;s:1:\"b\";s:5:\"staff\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:15:\"Warehouse Staff\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:17:\"Marketing creator\";s:1:\"c\";s:3:\"web\";}}}', 1784086337);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `parent_id`, `description`, `is_active`, `sort_order`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Điện Thoại ', 'dien-thoai', NULL, NULL, 1, 0, NULL, '2026-02-28 00:05:16', '2026-03-03 23:37:25'),
(2, 'Laptop', 'laptop', NULL, NULL, 1, 0, NULL, '2026-02-28 00:05:17', '2026-03-03 23:35:05'),
(3, 'Tablet', 'tablet', NULL, NULL, 1, 0, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(4, 'Smartwatch', 'smartwatch', NULL, NULL, 1, 0, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(5, 'Phụ Kiện', 'phu-kien', NULL, NULL, 1, 0, NULL, '2026-02-28 00:05:17', '2026-02-28 00:05:17'),
(6, 'Tai nghe', 'tai-nghe', 5, 'Chuyên mục Tai nghe', 1, 0, NULL, '2026-03-03 21:16:05', '2026-03-03 21:16:05'),
(7, 'Sạc nhanh', 'sac-nhanh', 5, 'Chuyên mục Sạc nhanh', 1, 0, NULL, '2026-03-03 21:16:05', '2026-03-03 21:16:05'),
(8, 'Chuột', 'chuot', 5, 'Chuyên mục Chuột', 1, 0, NULL, '2026-03-03 21:16:05', '2026-03-03 21:16:05'),
(9, 'Bàn phím', 'ban-phim', 5, 'Chuyên mục Bàn phím', 1, 0, NULL, '2026-03-03 21:16:05', '2026-03-03 21:16:05'),
(10, 'Sạc dự phòng', 'sac-du-phong', 5, 'Chuyên mục Sạc dự phòng', 1, 0, NULL, '2026-03-03 21:16:05', '2026-03-03 21:16:05'),
(11, 'Loa bluetooth', 'loa-bluetooth', 5, 'Chuyên mục Loa bluetooth', 1, 0, NULL, '2026-03-03 21:16:05', '2026-03-03 21:16:05'),
(13, 'Máy Cũ - Giá cũ', 'May-cu', NULL, 'Mãy cũ giá cũ chất lượng mới', 1, 0, 'categories/01KJVSFJWWTBGD4XTTYAW7VMJC.jpg', '2026-03-03 23:45:04', '2026-03-03 23:45:04');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chatbot_messages`
--

CREATE TABLE `chatbot_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_id` bigint(20) UNSIGNED NOT NULL,
  `role` enum('user','model') NOT NULL,
  `content` text NOT NULL,
  `tool_used` varchar(50) DEFAULT NULL,
  `response_time_ms` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chatbot_messages`
--

INSERT INTO `chatbot_messages` (`id`, `session_id`, `role`, `content`, `tool_used`, `response_time_ms`, `created_at`) VALUES
(1, 1, 'user', 'Thời gian giao hàng?', NULL, NULL, '2026-07-14 03:25:38'),
(2, 1, 'model', 'Dạ, thời gian giao hàng bên em thường từ 1 đến 7 ngày tùy vào khu vực nhận hàng của anh ạ. Nếu anh cần hỗ trợ thêm thông tin gì, anh cứ gọi hotline 1800.1060 để được giải đáp nhanh nhất nhé.', NULL, 1296, '2026-07-14 03:25:40'),
(3, 1, 'user', 'giới thiệu về cửa hàng', NULL, NULL, '2026-07-14 03:26:01'),
(4, 1, 'model', 'Dạ, bên em chuyên cung cấp các sản phẩm điện thoại, laptop và phụ kiện chính hãng với chính sách đổi trả trong 7 ngày và bảo hành lên đến 12 tháng ạ. Bên em hỗ trợ giao hàng toàn quốc từ 1-7 ngày, miễn phí vận chuyển cho đơn trên 500k cùng nhiều phương thức thanh toán linh hoạt như COD, VNPay hay MoMo để anh yên tâm mua sắm ạ.', NULL, 1267, '2026-07-14 03:26:03'),
(5, 2, 'user', 'Chính sách đổi trả?', NULL, NULL, '2026-07-14 03:31:03'),
(6, 2, 'model', 'Dạ, bên em áp dụng chính sách đổi trả trong vòng 7 ngày đối với các sản phẩm lỗi từ nhà sản xuất hoặc theo quy định cụ thể của từng mặt hàng ạ. Anh/chị cần hỗ trợ thêm thông tin gì cứ nhắn em nhé.', NULL, 1175, '2026-07-14 03:31:04'),
(7, 2, 'user', 'Có hỗ trợ trả góp không?', NULL, NULL, '2026-07-14 03:32:09'),
(8, 2, 'model', 'Dạ, bên em có hỗ trợ trả góp qua các công ty tài chính và thẻ tín dụng với thủ tục rất nhanh chóng ạ. Anh/chị có thể ghé trực tiếp cửa hàng hoặc liên hệ hotline 1800.1060 để nhân viên tư vấn chi tiết gói lãi suất tốt nhất cho mình nhé.', NULL, 1147, '2026-07-14 03:32:10');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chatbot_sessions`
--

CREATE TABLE `chatbot_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_token` char(36) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chatbot_sessions`
--

INSERT INTO `chatbot_sessions` (`id`, `session_token`, `user_id`, `user_agent`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, '290e9f0d-d08b-43ab-a827-075305028e46', 2, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '127.0.0.1', '2026-07-13 20:25:38', '2026-07-13 20:25:38'),
(2, '61fc1349-da91-4bf6-a636-7a141d174c57', 7, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '127.0.0.1', '2026-07-13 20:31:03', '2026-07-13 20:32:09');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `collections`
--

CREATE TABLE `collections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `show_on_home` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `collections`
--

INSERT INTO `collections` (`id`, `name`, `slug`, `image`, `description`, `is_active`, `show_on_home`, `sort_order`, `created_at`, `updated_at`, `parent_id`) VALUES
(1, 'Điện Thoại ', 'dien-thoai', NULL, NULL, 1, 1, 0, '2026-03-29 06:10:14', '2026-04-07 20:42:54', NULL),
(2, 'Laptop', 'laptop', NULL, NULL, 1, 1, 0, '2026-03-29 06:10:14', '2026-03-29 06:10:14', NULL),
(3, 'Tablet', 'tablet', NULL, NULL, 1, 1, 0, '2026-03-29 06:10:14', '2026-03-29 06:10:14', NULL),
(4, 'Smartwatch', 'smartwatch', NULL, NULL, 1, 1, 0, '2026-03-29 06:10:14', '2026-03-29 06:38:09', NULL),
(5, 'Phụ Kiện', 'phu-kien', NULL, NULL, 1, 1, 0, '2026-03-29 06:10:14', '2026-03-29 06:10:14', NULL),
(6, 'Phụ Kiện - Tai nghe', 'tai-nghe', NULL, 'Chuyên mục Tai nghe', 1, 1, 1, '2026-03-29 06:10:14', '2026-03-29 06:37:50', 5),
(7, 'Phụ Kiện - Sạc nhanh', 'sac-nhanh', NULL, 'Chuyên mục Sạc nhanh', 1, 1, 0, '2026-03-29 06:10:14', '2026-03-29 06:35:29', 5),
(8, 'Phụ Kiện - Chuột', 'chuot', NULL, 'Chuyên mục Chuột', 1, 1, 0, '2026-03-29 06:10:14', '2026-03-29 06:36:25', 5),
(9, 'Phụ Kiện - Bàn phím', 'ban-phim', NULL, 'Chuyên mục Bàn phím', 1, 1, 0, '2026-03-29 06:10:14', '2026-03-29 06:36:16', 5),
(10, 'Phụ Kiện - Sạc dự phòng', 'sac-du-phong', NULL, 'Chuyên mục Sạc dự phòng', 1, 1, 0, '2026-03-29 06:10:14', '2026-03-29 06:36:38', 5),
(11, 'Phụ Kiện - Loa bluetooth', 'loa-bluetooth', NULL, 'Chuyên mục Loa bluetooth', 1, 1, 0, '2026-03-29 06:10:14', '2026-03-29 06:37:03', 5),
(12, 'Máy Cũ - Giá cũ', 'May-cu', 'categories/01KJVSFJWWTBGD4XTTYAW7VMJC.jpg', 'Mãy cũ giá cũ chất lượng mới', 1, 1, 0, '2026-03-29 06:10:14', '2026-03-29 06:40:09', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `collection_product`
--

CREATE TABLE `collection_product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `collection_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `collection_product`
--

INSERT INTO `collection_product` (`id`, `collection_id`, `product_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 1, 3, NULL, NULL),
(4, 1, 4, NULL, NULL),
(5, 1, 5, NULL, NULL),
(6, 1, 6, NULL, NULL),
(7, 1, 7, NULL, NULL),
(8, 1, 8, NULL, NULL),
(9, 1, 9, NULL, NULL),
(10, 1, 10, NULL, NULL),
(11, 1, 11, NULL, NULL),
(12, 1, 12, NULL, NULL),
(13, 1, 13, NULL, NULL),
(14, 1, 14, NULL, NULL),
(15, 1, 15, NULL, NULL),
(16, 1, 16, NULL, NULL),
(17, 1, 17, NULL, NULL),
(18, 1, 18, NULL, NULL),
(19, 1, 19, NULL, NULL),
(20, 1, 20, NULL, NULL),
(21, 1, 21, NULL, NULL),
(22, 1, 22, NULL, NULL),
(23, 1, 23, NULL, NULL),
(24, 1, 24, NULL, NULL),
(25, 1, 25, NULL, NULL),
(26, 1, 26, NULL, NULL),
(27, 1, 27, NULL, NULL),
(28, 1, 28, NULL, NULL),
(29, 1, 29, NULL, NULL),
(30, 1, 30, NULL, NULL),
(31, 1, 31, NULL, NULL),
(32, 1, 32, NULL, NULL),
(33, 1, 33, NULL, NULL),
(34, 1, 34, NULL, NULL),
(35, 1, 35, NULL, NULL),
(36, 1, 36, NULL, NULL),
(37, 1, 37, NULL, NULL),
(38, 1, 38, NULL, NULL),
(39, 1, 39, NULL, NULL),
(40, 1, 40, NULL, NULL),
(41, 1, 41, NULL, NULL),
(42, 1, 42, NULL, NULL),
(43, 1, 43, NULL, NULL),
(44, 1, 44, NULL, NULL),
(45, 1, 45, NULL, NULL),
(46, 1, 46, NULL, NULL),
(47, 1, 47, NULL, NULL),
(48, 1, 48, NULL, NULL),
(49, 1, 49, NULL, NULL),
(50, 1, 50, NULL, NULL),
(51, 1, 51, NULL, NULL),
(52, 1, 52, NULL, NULL),
(53, 1, 53, NULL, NULL),
(54, 1, 54, NULL, NULL),
(55, 1, 55, NULL, NULL),
(56, 1, 56, NULL, NULL),
(57, 1, 57, NULL, NULL),
(58, 1, 58, NULL, NULL),
(59, 1, 59, NULL, NULL),
(60, 1, 60, NULL, NULL),
(61, 1, 61, NULL, NULL),
(62, 1, 62, NULL, NULL),
(63, 1, 63, NULL, NULL),
(64, 1, 64, NULL, NULL),
(65, 1, 65, NULL, NULL),
(66, 1, 66, NULL, NULL),
(67, 1, 67, NULL, NULL),
(68, 1, 68, NULL, NULL),
(69, 1, 69, NULL, NULL),
(70, 1, 70, NULL, NULL),
(71, 1, 202, NULL, NULL),
(72, 2, 71, NULL, NULL),
(73, 2, 72, NULL, NULL),
(74, 2, 73, NULL, NULL),
(75, 2, 74, NULL, NULL),
(76, 2, 75, NULL, NULL),
(77, 2, 76, NULL, NULL),
(78, 2, 77, NULL, NULL),
(79, 2, 78, NULL, NULL),
(80, 2, 79, NULL, NULL),
(81, 2, 80, NULL, NULL),
(82, 2, 81, NULL, NULL),
(83, 2, 82, NULL, NULL),
(84, 2, 83, NULL, NULL),
(85, 2, 84, NULL, NULL),
(86, 2, 85, NULL, NULL),
(87, 2, 86, NULL, NULL),
(88, 2, 87, NULL, NULL),
(89, 2, 88, NULL, NULL),
(90, 2, 89, NULL, NULL),
(91, 2, 90, NULL, NULL),
(92, 2, 91, NULL, NULL),
(93, 2, 92, NULL, NULL),
(94, 2, 93, NULL, NULL),
(95, 2, 94, NULL, NULL),
(96, 2, 95, NULL, NULL),
(97, 2, 96, NULL, NULL),
(98, 2, 97, NULL, NULL),
(99, 2, 98, NULL, NULL),
(100, 2, 99, NULL, NULL),
(101, 2, 100, NULL, NULL),
(102, 2, 101, NULL, NULL),
(103, 2, 102, NULL, NULL),
(104, 2, 103, NULL, NULL),
(105, 2, 104, NULL, NULL),
(106, 2, 105, NULL, NULL),
(107, 2, 106, NULL, NULL),
(108, 2, 107, NULL, NULL),
(109, 2, 108, NULL, NULL),
(110, 2, 109, NULL, NULL),
(111, 2, 110, NULL, NULL),
(112, 2, 111, NULL, NULL),
(113, 2, 112, NULL, NULL),
(114, 2, 113, NULL, NULL),
(115, 2, 114, NULL, NULL),
(116, 2, 115, NULL, NULL),
(117, 2, 116, NULL, NULL),
(118, 2, 117, NULL, NULL),
(119, 2, 118, NULL, NULL),
(120, 2, 119, NULL, NULL),
(121, 2, 120, NULL, NULL),
(122, 2, 121, NULL, NULL),
(123, 3, 122, NULL, NULL),
(124, 3, 123, NULL, NULL),
(125, 3, 124, NULL, NULL),
(126, 3, 125, NULL, NULL),
(127, 3, 126, NULL, NULL),
(128, 3, 127, NULL, NULL),
(129, 3, 128, NULL, NULL),
(130, 3, 129, NULL, NULL),
(131, 3, 130, NULL, NULL),
(132, 3, 131, NULL, NULL),
(133, 3, 132, NULL, NULL),
(134, 3, 133, NULL, NULL),
(135, 3, 134, NULL, NULL),
(136, 3, 135, NULL, NULL),
(137, 3, 136, NULL, NULL),
(138, 3, 137, NULL, NULL),
(139, 3, 138, NULL, NULL),
(140, 3, 139, NULL, NULL),
(141, 3, 140, NULL, NULL),
(142, 3, 141, NULL, NULL),
(143, 3, 142, NULL, NULL),
(144, 4, 143, NULL, NULL),
(145, 4, 144, NULL, NULL),
(146, 4, 145, NULL, NULL),
(147, 4, 146, NULL, NULL),
(148, 4, 147, NULL, NULL),
(149, 4, 148, NULL, NULL),
(150, 4, 149, NULL, NULL),
(151, 4, 150, NULL, NULL),
(152, 4, 151, NULL, NULL),
(153, 4, 152, NULL, NULL),
(154, 4, 153, NULL, NULL),
(155, 4, 154, NULL, NULL),
(156, 4, 155, NULL, NULL),
(157, 4, 156, NULL, NULL),
(158, 4, 157, NULL, NULL),
(159, 4, 158, NULL, NULL),
(160, 4, 159, NULL, NULL),
(161, 4, 160, NULL, NULL),
(162, 4, 161, NULL, NULL),
(163, 4, 162, NULL, NULL),
(164, 4, 163, NULL, NULL),
(165, 4, 164, NULL, NULL),
(166, 4, 165, NULL, NULL),
(167, 4, 166, NULL, NULL),
(168, 4, 167, NULL, NULL),
(169, 4, 168, NULL, NULL),
(170, 4, 203, NULL, NULL),
(171, 6, 169, NULL, NULL),
(172, 6, 170, NULL, NULL),
(173, 6, 171, NULL, NULL),
(174, 6, 172, NULL, NULL),
(175, 6, 173, NULL, NULL),
(176, 6, 199, NULL, NULL),
(177, 7, 174, NULL, NULL),
(178, 7, 175, NULL, NULL),
(179, 7, 176, NULL, NULL),
(180, 7, 177, NULL, NULL),
(181, 7, 178, NULL, NULL),
(182, 8, 179, NULL, NULL),
(183, 8, 180, NULL, NULL),
(184, 8, 181, NULL, NULL),
(185, 8, 182, NULL, NULL),
(186, 8, 183, NULL, NULL),
(187, 9, 184, NULL, NULL),
(188, 9, 185, NULL, NULL),
(189, 9, 186, NULL, NULL),
(190, 9, 187, NULL, NULL),
(191, 9, 188, NULL, NULL),
(192, 10, 189, NULL, NULL),
(193, 10, 190, NULL, NULL),
(194, 10, 191, NULL, NULL),
(195, 10, 192, NULL, NULL),
(196, 10, 193, NULL, NULL),
(197, 11, 194, NULL, NULL),
(198, 11, 195, NULL, NULL),
(199, 11, 196, NULL, NULL),
(200, 11, 197, NULL, NULL),
(201, 11, 198, NULL, NULL),
(202, 12, 200, NULL, NULL),
(203, 12, 201, NULL, NULL),
(204, 12, 80, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `goods_issues`
--

CREATE TABLE `goods_issues` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('auto','manual') NOT NULL DEFAULT 'auto',
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_cogs` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','completed','cancelled') NOT NULL DEFAULT 'completed',
  `note` text DEFAULT NULL,
  `author_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `goods_issues`
--

INSERT INTO `goods_issues` (`id`, `type`, `order_id`, `total_cogs`, `status`, `note`, `author_id`, `created_at`, `updated_at`) VALUES
(1, 'auto', 20, 147000000.00, 'completed', NULL, NULL, '2026-03-24 20:49:12', '2026-03-24 21:34:16'),
(2, 'auto', 23, 125000000.00, 'completed', NULL, NULL, '2026-03-24 20:52:22', '2026-03-24 21:34:13'),
(3, 'auto', 24, 23000000.00, 'completed', NULL, NULL, '2026-03-24 21:09:58', '2026-03-24 21:34:08'),
(4, 'auto', 25, 29000000.00, 'completed', NULL, NULL, '2026-03-24 21:23:08', '2026-03-24 21:23:08'),
(5, 'auto', 26, 4000000.00, 'completed', NULL, NULL, '2026-03-24 21:27:05', '2026-03-24 21:27:05'),
(6, 'auto', 27, 88000000.00, 'completed', NULL, NULL, '2026-03-24 21:53:01', '2026-03-24 21:53:01'),
(9, 'manual', NULL, 270000000.00, 'completed', 'hàng lỗi _ hỏng', 2, '2026-03-27 21:14:42', '2026-03-27 21:14:42'),
(10, 'manual', NULL, 135000000.00, 'completed', 'trà hàng về nhà sản xuất do lỗi mã', 2, '2026-03-27 21:18:40', '2026-03-27 21:18:40'),
(11, 'manual', NULL, 405000000.00, 'completed', 'bù trừ hao hụt do trước đó hệ thống không trừ', 2, '2026-03-27 21:31:06', '2026-03-27 21:31:06'),
(12, 'manual', NULL, 4789000.00, 'completed', 'đổi trả hàng cho khách', 2, '2026-03-27 21:49:05', '2026-03-27 21:49:05'),
(13, 'manual', NULL, 8789000.00, 'completed', 'đổi trả hàng cho khách', 2, '2026-03-27 23:38:06', '2026-03-27 23:38:06'),
(14, 'manual', NULL, 96578000.00, 'completed', 'Tiêu hủy', 2, '2026-03-27 23:45:39', '2026-03-27 23:45:39'),
(15, 'manual', NULL, 31789000.00, 'completed', 'Trả hàng', 2, '2026-03-28 00:09:40', '2026-03-28 00:09:40'),
(16, 'auto', 28, 26000000.00, 'completed', NULL, NULL, '2026-03-29 09:01:40', '2026-03-29 09:01:40'),
(17, 'auto', 30, 61000000.00, 'completed', NULL, NULL, '2026-03-29 19:16:29', '2026-03-29 19:16:29'),
(18, 'auto', 29, 47750000.00, 'completed', NULL, NULL, '2026-03-29 19:18:34', '2026-03-29 19:18:34'),
(19, 'auto', 31, 63000000.00, 'completed', NULL, NULL, '2026-04-29 09:03:49', '2026-04-29 09:03:49'),
(20, 'auto', 33, 27000000.00, 'completed', NULL, NULL, '2026-05-19 21:21:16', '2026-05-19 21:21:16'),
(21, 'auto', 32, 23000000.00, 'completed', NULL, NULL, '2026-05-19 21:34:22', '2026-05-20 00:14:57'),
(23, 'auto', 36, 15000000.00, 'completed', NULL, NULL, '2026-05-20 00:18:59', '2026-05-20 00:18:59'),
(24, 'auto', 37, 4789000.00, 'completed', NULL, NULL, '2026-05-20 01:59:48', '2026-05-20 01:59:48'),
(25, 'auto', 38, 15000000.00, 'completed', NULL, NULL, '2026-05-20 02:13:02', '2026-05-20 02:13:02'),
(26, 'auto', 39, 51000000.00, 'completed', NULL, NULL, '2026-05-20 04:08:57', '2026-05-20 04:08:57'),
(27, 'auto', 34, 35000000.00, 'completed', NULL, NULL, '2026-05-20 04:09:48', '2026-05-20 04:09:48'),
(28, 'auto', 40, 4000000.00, 'completed', NULL, NULL, '2026-05-20 04:09:58', '2026-05-20 04:09:58'),
(29, 'auto', 42, 48000000.00, 'completed', NULL, NULL, '2026-06-04 02:19:34', '2026-06-04 02:23:14'),
(30, 'auto', 43, 13000000.00, 'completed', NULL, NULL, '2026-06-04 02:27:59', '2026-06-04 02:28:15'),
(31, 'auto', 44, 29000000.00, 'cancelled', NULL, NULL, '2026-06-04 02:34:13', '2026-06-04 02:35:15'),
(32, 'auto', 41, 43000000.00, 'completed', NULL, NULL, '2026-06-06 03:32:36', '2026-06-06 03:34:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `goods_issue_details`
--

CREATE TABLE `goods_issue_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `goods_issue_id` bigint(20) UNSIGNED NOT NULL,
  `goods_receipt_detail_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `import_price` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `goods_issue_details`
--

INSERT INTO `goods_issue_details` (`id`, `goods_issue_id`, `goods_receipt_detail_id`, `product_id`, `quantity`, `import_price`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 4, 29, 71, 1, 29000000.00, 29000000.00, '2026-03-24 21:23:08', '2026-03-24 21:23:08'),
(2, 5, 64, 192, 2, 700000.00, 1400000.00, '2026-03-24 21:27:05', '2026-03-24 21:27:05'),
(3, 5, 61, 191, 2, 800000.00, 1600000.00, '2026-03-24 21:27:05', '2026-03-24 21:27:05'),
(4, 5, 62, 190, 2, 500000.00, 1000000.00, '2026-03-24 21:27:05', '2026-03-24 21:27:05'),
(5, 3, 58, 1, 1, 23000000.00, 23000000.00, '2026-03-24 21:34:08', '2026-03-24 21:34:08'),
(6, 2, 29, 71, 3, 29000000.00, 87000000.00, '2026-03-24 21:34:13', '2026-03-24 21:34:13'),
(7, 2, 17, 17, 2, 19000000.00, 38000000.00, '2026-03-24 21:34:13', '2026-03-24 21:34:13'),
(8, 1, 29, 71, 3, 29000000.00, 87000000.00, '2026-03-24 21:34:16', '2026-03-24 21:34:16'),
(9, 1, 1, 2, 3, 20000000.00, 60000000.00, '2026-03-24 21:34:16', '2026-03-24 21:34:16'),
(10, 6, 11, 10, 10, 8000000.00, 80000000.00, '2026-03-24 21:53:01', '2026-03-24 21:53:01'),
(11, 6, 65, 10, 1, 8000000.00, 8000000.00, '2026-03-24 21:53:01', '2026-03-24 21:53:01'),
(13, 9, 3, 7, 10, 27000000.00, 270000000.00, '2026-03-27 21:14:42', '2026-03-27 21:14:42'),
(14, 10, 3, 7, 5, 27000000.00, 135000000.00, '2026-03-27 21:18:40', '2026-03-27 21:18:40'),
(15, 11, 3, 7, 15, 27000000.00, 405000000.00, '2026-03-27 21:31:06', '2026-03-27 21:31:06'),
(16, 12, 10, 9, 1, 4789000.00, 4789000.00, '2026-03-27 21:49:05', '2026-03-27 21:49:05'),
(17, 13, 40, 35, 1, 4000000.00, 4000000.00, '2026-03-27 23:38:06', '2026-03-27 23:38:06'),
(18, 13, 10, 9, 1, 4789000.00, 4789000.00, '2026-03-27 23:38:06', '2026-03-27 23:38:06'),
(19, 14, 10, 9, 2, 4789000.00, 9578000.00, '2026-03-27 23:45:39', '2026-03-27 23:45:39'),
(20, 14, 9, 8, 5, 16000000.00, 80000000.00, '2026-03-27 23:45:39', '2026-03-27 23:45:39'),
(21, 14, 6, 4, 1, 7000000.00, 7000000.00, '2026-03-27 23:45:39', '2026-03-27 23:45:39'),
(22, 15, 10, 9, 1, 4789000.00, 4789000.00, '2026-03-28 00:09:40', '2026-03-28 00:09:40'),
(23, 15, 3, 7, 1, 27000000.00, 27000000.00, '2026-03-28 00:09:40', '2026-03-28 00:09:40'),
(24, 16, 45, 40, 1, 1000000.00, 1000000.00, '2026-03-29 09:01:40', '2026-03-29 09:01:40'),
(25, 16, 6, 4, 1, 7000000.00, 7000000.00, '2026-03-29 09:01:40', '2026-03-29 09:01:40'),
(26, 16, 18, 18, 1, 18000000.00, 18000000.00, '2026-03-29 09:01:40', '2026-03-29 09:01:40'),
(27, 17, 58, 1, 1, 23000000.00, 23000000.00, '2026-03-29 19:16:29', '2026-03-29 19:16:29'),
(28, 17, 1, 2, 1, 20000000.00, 20000000.00, '2026-03-29 19:16:29', '2026-03-29 19:16:29'),
(29, 17, 5, 3, 1, 18000000.00, 18000000.00, '2026-03-29 19:16:29', '2026-03-29 19:16:29'),
(30, 18, 5, 3, 1, 18000000.00, 18000000.00, '2026-03-29 19:18:34', '2026-03-29 19:18:34'),
(31, 18, 8, 6, 1, 15750000.00, 15750000.00, '2026-03-29 19:18:34', '2026-03-29 19:18:34'),
(32, 18, 26, 68, 1, 14000000.00, 14000000.00, '2026-03-29 19:18:34', '2026-03-29 19:18:34'),
(33, 19, 21, 21, 2, 20000000.00, 40000000.00, '2026-04-29 09:03:49', '2026-04-29 09:03:49'),
(34, 19, 58, 1, 1, 23000000.00, 23000000.00, '2026-04-29 09:03:49', '2026-04-29 09:03:49'),
(35, 20, 22, 22, 1, 17000000.00, 17000000.00, '2026-05-19 21:21:16', '2026-05-19 21:21:16'),
(36, 20, 32, 27, 1, 10000000.00, 10000000.00, '2026-05-19 21:21:16', '2026-05-19 21:21:16'),
(37, 21, 58, 1, 1, 23000000.00, 23000000.00, '2026-05-19 21:34:22', '2026-05-19 21:34:22'),
(38, 23, 25, 67, 1, 15000000.00, 15000000.00, '2026-05-20 00:18:59', '2026-05-20 00:18:59'),
(39, 24, 10, 9, 1, 4789000.00, 4789000.00, '2026-05-20 01:59:48', '2026-05-20 01:59:48'),
(40, 25, 19, 19, 1, 15000000.00, 15000000.00, '2026-05-20 02:13:02', '2026-05-20 02:13:02'),
(41, 26, 1, 2, 1, 20000000.00, 20000000.00, '2026-05-20 04:08:57', '2026-05-20 04:08:57'),
(42, 26, 25, 67, 1, 15000000.00, 15000000.00, '2026-05-20 04:08:57', '2026-05-20 04:08:57'),
(43, 26, 69, 55, 4, 4000000.00, 16000000.00, '2026-05-20 04:08:57', '2026-05-20 04:08:57'),
(44, 27, 67, 53, 1, 15000000.00, 15000000.00, '2026-05-20 04:09:48', '2026-05-20 04:09:48'),
(45, 27, 21, 21, 1, 20000000.00, 20000000.00, '2026-05-20 04:09:48', '2026-05-20 04:09:48'),
(46, 28, 69, 55, 1, 4000000.00, 4000000.00, '2026-05-20 04:09:58', '2026-05-20 04:09:58'),
(50, 29, 3, 7, 1, 27000000.00, 27000000.00, '2026-06-04 02:23:13', '2026-06-04 02:23:13'),
(51, 29, 21, 21, 1, 20000000.00, 20000000.00, '2026-06-04 02:23:13', '2026-06-04 02:23:13'),
(52, 29, 45, 40, 1, 1000000.00, 1000000.00, '2026-06-04 02:23:14', '2026-06-04 02:23:14'),
(54, 30, 86, 75, 1, 13000000.00, 13000000.00, '2026-06-04 02:28:15', '2026-06-04 02:28:15'),
(56, 31, 29, 71, 1, 29000000.00, 29000000.00, '2026-06-04 02:34:38', '2026-06-04 02:34:38'),
(59, 32, 1, 2, 1, 20000000.00, 20000000.00, '2026-06-06 03:34:05', '2026-06-06 03:34:05'),
(60, 32, 58, 1, 1, 23000000.00, 23000000.00, '2026-06-06 03:34:05', '2026-06-06 03:34:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `goods_receipts`
--

CREATE TABLE `goods_receipts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `status` enum('pending','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `goods_receipts`
--

INSERT INTO `goods_receipts` (`id`, `supplier_id`, `supplier_name`, `user_id`, `total_amount`, `note`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 'Oppo', 2, 1393000000.00, NULL, 'completed', '2026-03-19 19:45:16', '2026-03-19 19:45:16'),
(2, 4, 'Oppo', 2, 974390000.00, '', 'completed', '2026-03-19 20:06:15', '2026-03-19 20:06:15'),
(3, 3, 'Samsung', 2, 1682000000.00, '', 'completed', '2026-03-19 20:45:34', '2026-03-19 20:45:34'),
(4, 3, 'Samsung', 2, 2432000000.00, '', 'completed', '2026-03-19 20:47:07', '2026-03-19 20:47:07'),
(5, 4, 'Oppo', 2, 1159500000.00, '', 'completed', '2026-03-19 20:48:29', '2026-03-19 20:48:29'),
(6, 4, 'Oppo', 2, 1190500000.00, '', 'completed', '2026-03-20 20:12:46', '2026-03-20 20:12:46'),
(7, 3, 'Samsung', 5, 528500000.00, 'sưa lại số lượng nhâp pixel 5 thành 8', 'completed', '2026-03-20 20:35:04', '2026-03-21 01:30:26'),
(8, 3, 'Samsung', 2, 92000000.00, NULL, 'completed', '2026-03-24 04:49:53', '2026-03-24 06:59:02'),
(9, 3, 'Samsung', 2, 237000000.00, '', 'completed', '2026-03-24 08:16:31', '2026-03-24 08:16:31'),
(10, 3, 'Samsung', 1, 1159000000.00, '', 'completed', '2026-03-24 20:09:06', '2026-03-24 20:09:06'),
(11, 3, 'Samsung', 1, 32400000.00, '', 'completed', '2026-03-24 21:25:09', '2026-03-24 21:25:09'),
(12, 3, 'Samsung', 1, 80000000.00, '', 'completed', '2026-03-24 21:51:55', '2026-03-24 21:51:55'),
(13, 3, 'Samsung', 2, 829350000.00, '', 'completed', '2026-03-29 03:05:45', '2026-03-29 03:05:45'),
(14, 4, 'Oppo', 2, 484000000.00, '', 'completed', '2026-03-29 03:46:00', '2026-03-29 03:46:00'),
(15, 3, 'Samsung', 2, 543000000.00, '', 'completed', '2026-03-29 03:55:03', '2026-03-29 03:55:03'),
(16, 4, 'Oppo', 2, 840000000.00, 'Test', 'completed', '2026-05-20 00:40:18', '2026-05-20 00:43:31'),
(17, 3, 'Samsung', 2, 1049999980.00, '', 'completed', '2026-05-20 04:11:18', '2026-05-20 04:11:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `goods_receipt_details`
--

CREATE TABLE `goods_receipt_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `goods_receipt_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `remaining_quantity` int(11) NOT NULL DEFAULT 0,
  `import_price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `goods_receipt_details`
--

INSERT INTO `goods_receipt_details` (`id`, `goods_receipt_id`, `product_id`, `product_name`, `quantity`, `remaining_quantity`, `import_price`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Samsung Galaxy S23+', 18, 12, 20000000.00, '2026-03-19 19:45:16', '2026-06-06 03:34:05'),
(2, 1, 11, 'iPhone 15 Pro Max', 20, 20, 26000000.00, '2026-03-19 19:45:16', '2026-03-27 21:19:01'),
(3, 1, 7, 'Samsung Galaxy Z Fold 4', 19, 2, 27000000.00, '2026-03-19 19:45:16', '2026-06-04 02:23:13'),
(4, 2, 11, 'iPhone 15 Pro Max', 5, 5, 26000000.00, '2026-03-19 20:06:15', '2026-03-27 21:19:01'),
(5, 2, 3, 'Samsung Galaxy S23', 10, 8, 18000000.00, '2026-03-19 20:06:15', '2026-03-29 19:18:34'),
(6, 2, 4, 'Samsung Galaxy A53', 10, 8, 7000000.00, '2026-03-19 20:06:15', '2026-03-29 09:01:40'),
(7, 2, 5, 'Samsung Galaxy A73', 19, 19, 9500000.00, '2026-03-19 20:06:15', '2026-03-27 21:19:01'),
(8, 2, 6, 'Samsung Galaxy Note 20', 8, 7, 15750000.00, '2026-03-19 20:06:15', '2026-03-29 19:18:34'),
(9, 2, 8, 'Samsung Galaxy Z Flip 4', 15, 10, 16000000.00, '2026-03-19 20:06:15', '2026-03-27 23:45:39'),
(10, 2, 9, 'Samsung Galaxy M33', 10, 4, 4789000.00, '2026-03-19 20:06:15', '2026-05-20 01:59:48'),
(11, 3, 10, 'Samsung Galaxy M53', 10, 0, 8000000.00, '2026-03-19 20:45:34', '2026-03-27 21:19:01'),
(12, 3, 12, 'iPhone 15 Pro', 12, 12, 25000000.00, '2026-03-19 20:45:34', '2026-03-27 21:19:01'),
(13, 3, 13, 'iPhone 15', 12, 12, 23000000.00, '2026-03-19 20:45:34', '2026-03-27 21:19:01'),
(14, 3, 14, 'iPhone 15 Plus', 15, 15, 24000000.00, '2026-03-19 20:45:34', '2026-03-27 21:19:01'),
(15, 3, 15, 'iPhone 14 Pro Max', 12, 12, 20000000.00, '2026-03-19 20:45:34', '2026-03-27 21:19:01'),
(16, 3, 16, 'iPhone 14 Pro', 12, 12, 20000000.00, '2026-03-19 20:45:34', '2026-03-27 21:19:01'),
(17, 3, 17, 'iPhone 14', 6, 4, 19000000.00, '2026-03-19 20:45:34', '2026-03-27 21:19:01'),
(18, 3, 18, 'iPhone 14 Plus', 4, 3, 18000000.00, '2026-03-19 20:45:34', '2026-03-29 09:01:40'),
(19, 4, 19, 'iPhone SE (3rd gen)', 12, 11, 15000000.00, '2026-03-19 20:47:07', '2026-05-20 02:13:02'),
(20, 4, 20, 'iPhone 13 mini', 15, 15, 17000000.00, '2026-03-19 20:47:07', '2026-03-27 21:19:01'),
(21, 4, 21, 'OPPO Find X5 Pro', 14, 10, 20000000.00, '2026-03-19 20:47:07', '2026-06-04 02:23:13'),
(22, 4, 22, 'OPPO Find X5', 13, 12, 17000000.00, '2026-03-19 20:47:07', '2026-05-19 21:21:16'),
(23, 4, 23, 'OPPO Reno 8 Pro', 12, 11, 14000000.00, '2026-03-19 20:47:07', '2026-03-27 21:19:01'),
(24, 4, 24, 'OPPO Reno 8', 12, 12, 12000000.00, '2026-03-19 20:47:07', '2026-03-27 21:19:01'),
(25, 4, 67, 'Sony Xperia 1 II', 15, 13, 15000000.00, '2026-03-19 20:47:07', '2026-05-20 04:08:57'),
(26, 4, 68, 'Sony Xperia 5 II', 12, 10, 14000000.00, '2026-03-19 20:47:07', '2026-03-29 19:18:34'),
(27, 4, 69, 'Sony Xperia 10 II', 19, 19, 7000000.00, '2026-03-19 20:47:07', '2026-03-27 21:19:01'),
(28, 4, 70, 'Sony Xperia L4', 13, 13, 6000000.00, '2026-03-19 20:47:07', '2026-03-27 21:19:01'),
(29, 4, 71, 'ASUS ROG Zephyrus G14', 20, 13, 29000000.00, '2026-03-19 20:47:07', '2026-06-04 02:35:15'),
(30, 5, 25, 'OPPO A95', 5, 5, 8900000.00, '2026-03-19 20:48:29', '2026-03-27 21:19:01'),
(31, 5, 26, 'OPPO A57', 22, 22, 6000000.00, '2026-03-19 20:48:29', '2026-03-27 21:19:01'),
(32, 5, 27, 'OPPO F21 Pro', 12, 11, 10000000.00, '2026-03-19 20:48:29', '2026-05-19 21:21:16'),
(33, 5, 28, 'OPPO F19 Pro', 12, 12, 10000000.00, '2026-03-19 20:48:29', '2026-03-27 21:19:01'),
(34, 5, 29, 'OPPO Reno 7', 12, 12, 11000000.00, '2026-03-19 20:48:29', '2026-03-27 21:19:01'),
(35, 5, 30, 'OPPO Reno 6 Pro', 7, 7, 11000000.00, '2026-03-19 20:48:29', '2026-03-27 21:19:01'),
(36, 5, 31, 'Xiaomi 13 Pro', 12, 12, 16000000.00, '2026-03-19 20:48:29', '2026-03-27 21:19:01'),
(37, 5, 32, 'Xiaomi 13', 15, 15, 14000000.00, '2026-03-19 20:48:29', '2026-03-27 21:19:01'),
(38, 5, 33, 'Xiaomi Redmi Note 12 Pro', 12, 12, 6000000.00, '2026-03-19 20:48:29', '2026-03-27 21:19:01'),
(39, 5, 34, 'Xiaomi Redmi Note 12', 12, 12, 5000000.00, '2026-03-19 20:48:29', '2026-03-27 21:19:01'),
(40, 6, 35, 'Xiaomi Redmi 10', 10, 9, 4000000.00, '2026-03-20 20:12:47', '2026-03-27 23:38:06'),
(41, 6, 36, 'Xiaomi Mi 11 Ultra', 12, 12, 15000000.00, '2026-03-20 20:12:47', '2026-03-27 21:19:01'),
(42, 6, 37, 'Xiaomi Mi 11', 19, 18, 13000000.00, '2026-03-20 20:12:47', '2026-03-27 21:19:01'),
(43, 6, 38, 'Xiaomi Poco F4', 11, 11, 6000000.00, '2026-03-20 20:12:47', '2026-03-27 21:19:01'),
(44, 6, 39, 'Xiaomi Redmi K40', 5, 5, 7500000.00, '2026-03-20 20:12:47', '2026-03-27 21:19:01'),
(45, 6, 40, 'Xiaomi Redmi 10A', 11, 9, 1000000.00, '2026-03-20 20:12:47', '2026-06-04 02:23:14'),
(46, 6, 41, 'Google Pixel 7 Pro', 11, 11, 18000000.00, '2026-03-20 20:12:47', '2026-03-27 21:19:01'),
(47, 6, 42, 'Google Pixel 7', 16, 16, 15000000.00, '2026-03-20 20:12:47', '2026-03-27 21:19:01'),
(48, 6, 43, 'Google Pixel 6 Pro', 2, 2, 14000000.00, '2026-03-20 20:12:47', '2026-03-27 21:19:01'),
(49, 6, 44, 'Google Pixel 6', 11, 11, 13000000.00, '2026-03-20 20:12:47', '2026-03-27 21:19:01'),
(50, 7, 45, 'Google Pixel 5', 10, 10, 10000000.00, '2026-03-20 20:35:04', '2026-03-27 21:19:01'),
(51, 7, 46, 'Google Pixel 4a 5G', 20, 20, 10000000.00, '2026-03-20 20:35:04', '2026-03-27 21:19:01'),
(52, 7, 47, 'Google Pixel 4a', 15, 15, 9000000.00, '2026-03-20 20:35:04', '2026-03-27 21:19:01'),
(53, 7, 48, 'Google Pixel 4', 11, 11, 8500000.00, '2026-03-20 20:35:04', '2026-03-27 21:19:01'),
(54, 8, 1, 'Samsung Galaxy S23 Ultra', 4, 0, 23000000.00, '2026-03-24 04:49:53', '2026-03-27 21:19:01'),
(55, 9, 49, 'Google Pixel 3a', 11, 11, 7000000.00, '2026-03-24 08:16:31', '2026-03-27 21:19:01'),
(56, 9, 50, 'Google Pixel 3', 12, 12, 5000000.00, '2026-03-24 08:16:31', '2026-03-27 21:19:01'),
(57, 9, 51, 'Huawei Mate 50 Pro', 5, 5, 20000000.00, '2026-03-24 08:16:31', '2026-03-27 21:19:01'),
(58, 10, 1, 'Samsung Galaxy S23 Ultra', 19, 15, 23000000.00, '2026-03-24 20:09:06', '2026-06-06 03:34:05'),
(59, 10, 3, 'Samsung Galaxy S23', 19, 19, 18000000.00, '2026-03-24 20:09:06', '2026-03-27 21:19:01'),
(60, 10, 2, 'Samsung Galaxy S23+', 19, 19, 20000000.00, '2026-03-24 20:09:06', '2026-03-27 21:19:01'),
(61, 11, 191, 'Sạc dự phòng Samsung 10.000mAh Wireless', 11, 9, 800000.00, '2026-03-24 21:25:09', '2026-03-27 21:19:01'),
(62, 11, 190, 'Sạc dự phòng Xiaomi Mi 20.000mAh 18W', 15, 13, 500000.00, '2026-03-24 21:25:09', '2026-03-27 21:19:01'),
(63, 11, 189, 'Sạc dự phòng Anker PowerCore 20.000mAh', 14, 14, 600000.00, '2026-03-24 21:25:09', '2026-03-27 21:19:01'),
(64, 11, 192, 'Sạc dự phòng Energizer 30.000mAh', 11, 9, 700000.00, '2026-03-24 21:25:09', '2026-03-27 21:19:01'),
(65, 12, 10, 'Samsung Galaxy M53', 10, 9, 8000000.00, '2026-03-24 21:51:55', '2026-03-27 21:19:01'),
(66, 13, 52, 'Huawei Mate 40 Pro', 10, 10, 19000000.00, '2026-03-29 03:05:45', '2026-03-29 03:05:45'),
(67, 13, 53, 'Huawei P50 Pro', 8, 7, 15000000.00, '2026-03-29 03:05:45', '2026-05-20 04:09:48'),
(68, 13, 54, 'Huawei Nova 9', 12, 12, 10000000.00, '2026-03-29 03:05:45', '2026-03-29 03:05:45'),
(69, 13, 55, 'Huawei Y9a', 5, 0, 4000000.00, '2026-03-29 03:05:45', '2026-05-20 04:09:58'),
(70, 13, 56, 'Huawei Mate Xs', 15, 15, 20000000.00, '2026-03-29 03:05:45', '2026-03-29 03:05:45'),
(71, 13, 57, 'Huawei P40 Pro', 5, 5, 15000000.00, '2026-03-29 03:05:45', '2026-03-29 03:05:45'),
(72, 13, 162, 'Xiaomi Mi Band 7', 5, 5, 870000.00, '2026-03-29 03:05:45', '2026-03-29 03:05:45'),
(73, 14, 58, 'Huawei Mate 30 Pro', 11, 11, 15000000.00, '2026-03-29 03:46:00', '2026-03-29 03:46:00'),
(74, 14, 59, 'Huawei Nova 8', 11, 11, 10000000.00, '2026-03-29 03:46:00', '2026-03-29 03:46:00'),
(75, 14, 60, 'Huawei Enjoy 20 Pro', 11, 11, 4000000.00, '2026-03-29 03:46:00', '2026-03-29 03:46:00'),
(76, 14, 62, 'Sony Xperia 5 IV', 11, 11, 15000000.00, '2026-03-29 03:46:00', '2026-03-29 03:46:00'),
(77, 15, 61, 'Sony Xperia 1 IV', 12, 12, 17000000.00, '2026-03-29 03:55:03', '2026-03-29 03:55:03'),
(78, 15, 63, 'Sony Xperia 10 IV', 5, 5, 10000000.00, '2026-03-29 03:55:03', '2026-03-29 03:55:03'),
(79, 15, 64, 'Sony Xperia 1 III', 6, 6, 17000000.00, '2026-03-29 03:55:03', '2026-03-29 03:55:03'),
(80, 15, 65, 'Sony Xperia 5 III', 11, 11, 17000000.00, '2026-03-29 03:55:03', '2026-03-29 03:55:03'),
(81, 16, 66, 'Sony Xperia 10 III', 10, 10, 9000000.00, '2026-05-20 00:40:18', '2026-05-20 00:40:18'),
(82, 16, 72, 'ASUS ROG Zephyrus G15', 10, 10, 25000000.00, '2026-05-20 00:40:18', '2026-05-20 00:40:18'),
(83, 16, 73, 'ASUS ROG Strix Scar 15', 10, 10, 30000000.00, '2026-05-20 00:40:18', '2026-05-20 00:40:18'),
(84, 16, 74, 'ASUS TUF Gaming F15', 10, 10, 20000000.00, '2026-05-20 00:40:18', '2026-05-20 00:40:18'),
(85, 17, 55, 'Huawei Y9a', 10, 10, 4000000.00, '2026-05-20 04:11:18', '2026-05-20 04:11:18'),
(86, 17, 75, 'ASUS ZenBook 14 UX435', 12, 11, 13000000.00, '2026-05-20 04:11:18', '2026-06-04 02:28:15'),
(87, 17, 76, 'ASUS VivoBook 15 X1504VA', 11, 11, 14000000.00, '2026-05-20 04:11:18', '2026-05-20 04:11:18'),
(88, 17, 77, 'ASUS ExpertBook B9', 10, 10, 19999999.00, '2026-05-20 04:11:18', '2026-05-20 04:11:18'),
(89, 17, 78, 'ASUS ROG Flow X13', 10, 10, 19999999.00, '2026-05-20 04:11:18', '2026-05-20 04:11:18'),
(90, 17, 79, 'ASUS ROG Strix G17', 10, 10, 30000000.00, '2026-05-20 04:11:18', '2026-05-20 04:11:18');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(9, '0001_01_01_000000_create_users_table', 1),
(10, '0001_01_01_000001_create_cache_table', 1),
(11, '0001_01_01_000002_create_jobs_table', 1),
(12, '2026_02_28_024736_create_brands_table', 1),
(13, '2026_02_28_024736_create_categories_table', 1),
(14, '2026_02_28_024736_create_products_table', 1),
(15, '2026_02_28_024737_create_orders_table', 1),
(16, '2026_02_28_024738_create_order_details_table', 1),
(17, '2026_03_01_063556_add_slug_to_products_table', 2),
(18, '2026_03_01_063724_create_posts_table', 3),
(19, '2026_03_02_144157_add_status_to_users_table', 4),
(20, '2026_03_02_150021_create_permission_tables', 5),
(21, '2026_03_03_043242_add_admin_note_to_orders_table', 6),
(22, '2026_03_04_062334_add_status_and_sort_order_to_categories_table', 7),
(23, '2026_03_16_000000_create_vouchers_table', 8),
(24, '2026_03_16_000001_add_voucher_to_orders_table', 8),
(25, '2026_03_16_000002_create_user_voucher_table', 9),
(26, '2026_03_16_205600_create_banners_table', 10),
(27, '2026_03_16_224800_create_reviews_table', 11),
(28, '2026_03_17_111800_create_partners_table', 12),
(29, '2026_03_17_111900_add_shipping_fields_to_orders_table', 12),
(30, '2026_03_20_021900_create_goods_receipts_table', 13),
(31, '2026_03_21_213000_increase_decimal_size_for_prices', 14),
(32, '2026_03_24_133530_create_activity_logs_table', 15),
(33, '2026_03_25_104500_create_goods_issues_table', 16),
(34, '2026_03_25_104501_create_goods_issue_details_table', 16),
(35, '2026_03_25_104502_add_remaining_quantity_to_goods_receipt_details_table', 16),
(36, '2026_03_25_110000_fix_and_sync_inventory_data', 17),
(37, '2026_03_27_150001_phase1_update_users_table', 17),
(38, '2026_03_27_150002_phase1_update_orders_table', 17),
(39, '2026_03_27_150003_phase1_update_order_details_table', 17),
(40, '2026_03_27_155001_phase2_create_product_images_table', 17),
(41, '2026_03_27_155002_phase2_update_products_table', 17),
(42, '2026_03_27_155003_phase2_update_vouchers_and_user_voucher', 17),
(43, '2026_03_27_160001_phase3_update_posts_table', 17),
(44, '2026_03_27_160002_phase3_update_brands_banners_reviews', 18),
(45, '2026_03_27_235900_update_orders_metadata_final', 19),
(46, '2026_03_28_000001_refine_orders_subtotal', 20),
(47, '2026_03_28_104000_add_author_id_to_banners_table', 21),
(48, '2026_03_28_040343_add_manual_fields_to_goods_issues_table', 22),
(49, '2026_03_28_044215_add_morphs_and_properties_to_activity_logs', 23),
(50, '2026_03_28_075530_create_addresses_table', 24),
(51, '2026_03_28_075532_add_profile_fields_to_users_table', 24),
(52, '2026_03_29_183700_create_collections_table', 25),
(53, '2026_03_29_202800_add_parent_id_to_collections_table', 26),
(54, '2026_05_20_065824_add_is_active_to_products_table', 27),
(55, '2026_05_20_073041_add_status_to_goods_receipts_table', 28),
(56, '2026_05_20_073042_add_pending_to_goods_issues_status', 28),
(57, '2026_05_20_073536_make_goods_receipt_detail_id_nullable_in_goods_issue_details', 29),
(58, '2026_05_20_112037_fix_goods_receipts_supplier_cascade', 30),
(59, '2026_05_20_112038_add_snapshot_fields_to_goods_receipts_and_orders', 30),
(60, '2026_05_20_112040_add_product_name_to_goods_receipt_details', 30),
(61, '2026_06_04_000001_add_preparing_status_to_orders', 31),
(62, '2026_06_04_000002_fix_payment_status_for_delivered_and_online_orders', 32),
(63, '2026_07_14_101249_create_chatbot_tables', 32);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 4),
(5, 'App\\Models\\User', 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subtotal` decimal(15,2) DEFAULT NULL,
  `total` decimal(15,2) NOT NULL,
  `shipping_name` varchar(255) DEFAULT NULL,
  `shipping_address` varchar(500) DEFAULT NULL,
  `shipping_phone` varchar(50) DEFAULT NULL,
  `status` enum('pending','confirmed','preparing','shipping','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` enum('cod','vnpay','momo') DEFAULT 'cod',
  `payment_status` enum('unpaid','paid','refunded') NOT NULL DEFAULT 'unpaid',
  `delivered_at` timestamp NULL DEFAULT NULL,
  `preparing_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `shipping_method` varchar(255) DEFAULT NULL,
  `shipping_fee` decimal(15,2) NOT NULL,
  `admin_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `voucher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `discount_amount` decimal(15,2) DEFAULT NULL,
  `partner_id` bigint(20) UNSIGNED DEFAULT NULL,
  `shipping_provider_name` varchar(255) DEFAULT NULL,
  `tracking_number` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `subtotal`, `total`, `shipping_name`, `shipping_address`, `shipping_phone`, `status`, `payment_method`, `payment_status`, `delivered_at`, `preparing_at`, `cancelled_at`, `shipping_method`, `shipping_fee`, `admin_note`, `created_at`, `updated_at`, `voucher_id`, `discount_amount`, `partner_id`, `shipping_provider_name`, `tracking_number`) VALUES
(10, 4, 89990000.00, 89990000.00, 'thao2k5', 'số 127 tổ dân phố cơ khí Yên Viên', '0904248860', 'delivered', 'vnpay', 'paid', '2026-03-19 20:43:00', NULL, NULL, NULL, 0.00, NULL, '2026-03-03 23:34:27', '2026-06-04 02:31:13', NULL, NULL, 1, 'Vn express', 'VNPost-LNLE1X7N'),
(11, 2, 8990000.00, 8990000.00, 'huyluong', 'no 14. pho chua boc', '0867675025', 'delivered', 'vnpay', 'paid', '2026-03-19 20:43:16', NULL, NULL, NULL, 0.00, NULL, '2026-03-10 00:48:12', '2026-06-04 02:31:13', NULL, NULL, 2, 'Logictics VN', 'VNPost-KOQUZM49'),
(12, 2, 13960000.00, 13960000.00, 'huyluong', 'số 132 Thái Hà', '32131231', 'delivered', 'vnpay', 'paid', '2026-03-19 20:43:20', NULL, NULL, NULL, 0.00, NULL, '2026-03-14 02:06:34', '2026-06-04 02:31:13', NULL, NULL, 2, 'Logictics VN', 'VNPost-ZXEFHPRC'),
(13, 2, 30990000.00, 30990000.00, 'huyluong', 'số 78 Hà huy tập', '00120403412431', 'delivered', 'cod', 'paid', '2026-03-29 18:26:41', NULL, '2026-03-19 20:43:36', NULL, 0.00, NULL, '2026-03-15 18:14:21', '2026-06-04 02:31:13', NULL, NULL, 2, 'Logictics VN', 'VNPost-FNDX3XTS'),
(14, 4, 33980000.00, 30582000.00, 'thao2k5', 'SỐ 127 NGÕ 117 TDP CƠ KHÍ YÊN VIÊN', '0893083042', 'delivered', 'cod', 'paid', '2026-03-19 20:43:31', NULL, NULL, NULL, 0.00, NULL, '2026-03-15 19:02:34', '2026-06-04 02:31:13', 3, 3398000.00, 2, 'Logictics VN', 'VNPost-RKEJUCJM'),
(15, 4, 42480000.00, 41480000.00, 'thao2k5', 'SỐ 127 NGÕ 117 TDP CƠ KHÍ YÊN VIÊN', '0903421423', 'delivered', 'cod', 'paid', '2026-03-19 20:43:41', NULL, NULL, NULL, 0.00, NULL, '2026-03-15 19:17:40', '2026-06-04 02:31:13', 1, 1000000.00, 1, 'Vn express', 'VNPost-G85B9HMR'),
(16, 4, 7990000.00, 7191000.00, 'thao2k5', 'SỐ 127 NGÕ 117 TDP CƠ KHÍ YÊN VIÊN', '0893083042', 'delivered', 'vnpay', 'paid', '2026-03-19 20:43:46', NULL, NULL, NULL, 0.00, NULL, '2026-03-16 06:29:49', '2026-06-04 02:31:13', 3, 799000.00, 1, 'Vn express', 'VNPost-RDMFIZN6'),
(17, 4, 22990000.00, 23020000.00, 'thao2k5', 'SỐ 127 NGÕ 117 TDP CƠ KHÍ YÊN VIÊN', '0904348869', 'delivered', 'cod', 'paid', '2026-03-20 20:38:18', NULL, NULL, 'standard', 30000.00, NULL, '2026-03-16 23:04:48', '2026-06-04 02:31:13', NULL, NULL, NULL, NULL, NULL),
(18, 4, 25990000.00, 26040000.00, 'thao2k5', 'SỐ 127 NGÕ 117 TDP CƠ KHÍ YÊN VIÊN', '023451231', 'delivered', 'vnpay', 'paid', '2026-03-24 20:58:56', NULL, NULL, 'express', 50000.00, NULL, '2026-03-19 20:07:55', '2026-06-04 02:31:13', NULL, NULL, 2, 'Logictics VN', 'VNPost-JRDOB6UC'),
(19, 4, 21990000.00, 21040000.00, 'thao2k5', 'SỐ 127 NGÕ 117 TDP CƠ KHÍ YÊN VIÊN', '0893083042', 'cancelled', 'cod', 'unpaid', NULL, NULL, '2026-03-21 01:43:33', 'express', 50000.00, NULL, '2026-03-21 01:33:21', '2026-03-21 01:43:33', 1, 1000000.00, NULL, NULL, NULL),
(20, 4, 170940000.00, 170970000.00, 'thao2k5', 'SỐ 127 NGÕ 117 TDP CƠ KHÍ YÊN VIÊN', '0903421423', 'delivered', 'cod', 'paid', '2026-03-28 00:36:41', NULL, NULL, 'standard', 30000.00, NULL, '2026-03-21 07:30:17', '2026-06-04 02:31:13', NULL, NULL, 1, 'Vn express', 'VNPost-TZOFYEKC'),
(21, 4, 64960000.00, 64990000.00, 'thao2k5', 'SỐ 127 NGÕ 117 TDP CƠ KHÍ YÊN VIÊN', '0893083042', 'delivered', 'cod', 'paid', '2026-03-29 17:55:58', NULL, NULL, 'standard', 30000.00, NULL, '2026-03-21 08:45:28', '2026-06-04 02:31:13', NULL, NULL, 2, 'Logictics VN', 'VNPost-J0FAB1J8'),
(22, 4, 103960000.00, 103990000.00, 'thao2k5', 'SỐ 127 NGÕ 117 TDP CƠ KHÍ YÊN VIÊN', '0893083042', 'delivered', 'cod', 'paid', '2026-03-29 18:35:12', NULL, NULL, 'standard', 30000.00, NULL, '2026-03-24 05:13:59', '2026-06-04 02:31:13', NULL, NULL, 2, 'Logictics VN', 'VNPost-T4AOIWBK'),
(23, 4, 142950000.00, 142980000.00, 'thao2k4', 'SỐ 127 NGÕ 117 TDP CƠ KHÍ YÊN VIÊN', '0893083042', 'delivered', 'cod', 'paid', '2026-03-29 18:35:46', NULL, NULL, 'standard', 30000.00, NULL, '2026-03-24 20:51:59', '2026-06-04 02:31:13', NULL, NULL, 2, 'Logictics VN', 'VNPost-G5L75CNY'),
(24, 4, 25990000.00, 26020000.00, 'thao2k5', 'SỐ 127 NGÕ 117 TDP CƠ KHÍ YÊN VIÊN', '0893083042', 'delivered', 'vnpay', 'paid', '2026-06-04 02:29:05', NULL, NULL, 'standard', 30000.00, NULL, '2026-03-24 21:09:45', '2026-06-04 02:31:13', NULL, NULL, 1, 'Vn express', 'VNPost-SC1TXDOO'),
(25, 4, 32990000.00, 33020000.00, 'thao2k5', 'SỐ 127 NGÕ 117 TDP CƠ KHÍ YÊN VIÊN', '0893083042', 'shipping', 'vnpay', 'paid', NULL, NULL, NULL, 'standard', 30000.00, NULL, '2026-03-24 21:16:31', '2026-06-04 02:31:13', NULL, NULL, 1, 'Vn express', 'VNPost-3OGP1PMC'),
(26, 4, 5860000.00, 5890000.00, 'thao2k5', 'SỐ 127 NGÕ 117 TDP CƠ KHÍ YÊN VIÊN', '0893083042', 'shipping', 'vnpay', 'paid', NULL, NULL, NULL, 'standard', 30000.00, NULL, '2026-03-24 21:26:36', '2026-06-04 02:31:13', NULL, NULL, 2, 'Logictics VN', 'VNPost-C1YQJJ9Z'),
(27, 4, 120890000.00, 120920000.00, 'thao2k5', 'SỐ 127 NGÕ 117 TDP CƠ KHÍ YÊN VIÊN', '0893083042', 'shipping', 'vnpay', 'paid', NULL, NULL, NULL, 'standard', 30000.00, NULL, '2026-03-24 21:52:38', '2026-06-04 02:31:13', NULL, NULL, 2, 'Logictics VN', 'VNPost-NL9QX8S5'),
(28, 2, 37970000.00, 38020000.00, 'Lương Quốc Huy', 'số 127 tổ dân phố cơ khí yên viên', '0902161559', 'shipping', 'cod', 'unpaid', NULL, NULL, NULL, 'express', 50000.00, NULL, '2026-03-28 02:28:30', '2026-03-29 09:01:40', NULL, NULL, 1, 'Vn express', 'SHIP-DTXD3W8NBI'),
(29, 4, 58970000.00, 59000000.00, 'thao2k5', 'SỐ 127 NGÕ 117 TDP CƠ KHÍ YÊN VIÊN', '0893083042', 'shipping', 'momo', 'paid', NULL, NULL, NULL, 'standard', 30000.00, NULL, '2026-03-29 02:01:57', '2026-03-29 19:18:34', NULL, NULL, 2, 'Logictics VN', 'SHIP-N9SVT7UXXR'),
(30, 4, 71970000.00, 72020000.00, 'Nguyễn Thu Thảo', 'SỐ 127 NGÕ 117 TDP CƠ KHÍ YÊN VIÊN', '0893083042', 'shipping', 'cod', 'unpaid', NULL, NULL, NULL, 'express', 50000.00, NULL, '2026-03-29 19:15:57', '2026-03-29 19:16:29', NULL, 0.00, 1, 'Vn express', 'SHIP-FP09B8GQ6N'),
(31, 2, 69970000.00, 70000000.00, 'Lương Quốc Huy', 'số 127 tổ dân phố cơ khí yên viên', '0902161559', 'delivered', 'vnpay', 'paid', '2026-04-29 09:04:09', NULL, NULL, 'standard', 30000.00, NULL, '2026-04-29 08:11:35', '2026-04-29 09:04:09', NULL, NULL, 1, 'Vn express', 'SHIP-TAVS9MYROU'),
(32, 2, 30489999.00, 30539999.00, 'Lương Quốc Huy', 'số 127 tổ dân phố cơ khí yên viên', '0902161559', 'shipping', 'cod', 'unpaid', NULL, NULL, NULL, 'express', 50000.00, NULL, '2026-05-12 21:06:25', '2026-05-19 21:34:22', NULL, NULL, 2, 'Logictics VN', 'SHIP-KMZNYYS50H'),
(33, 6, 32980000.00, 29727000.00, 'Huyền Thương', 'SỐ 5 NGÕ 117 QUẬN TÂN BÌNH', '0867675098', 'delivered', 'vnpay', 'paid', '2026-05-19 21:22:18', NULL, NULL, 'express', 50000.00, NULL, '2026-05-19 21:20:30', '2026-05-19 21:22:18', 4, 3303000.00, 1, 'Vn express', 'SHIP-5ZNKFW8HQ6'),
(34, 4, 42980000.00, 38709000.00, 'thao2k5', 'SỐ 127 NGÕ 117 TDP CƠ KHÍ YÊN VIÊN', '0893083042', 'shipping', 'cod', 'unpaid', NULL, NULL, NULL, 'standard', 30000.00, NULL, '2026-05-19 21:33:54', '2026-05-20 04:09:48', 4, 4301000.00, 2, 'Logictics VN', 'SHIP-7MLNSGYDGD'),
(35, 4, 9000000.00, 9030000.00, 'thao2k5', 'SỐ 127 NGÕ 117 TDP CƠ KHÍ YÊN VIÊN', '0893083042', 'delivered', 'momo', 'paid', '2026-05-20 00:08:26', NULL, NULL, 'standard', 30000.00, NULL, '2026-05-20 00:06:14', '2026-05-20 00:08:26', NULL, NULL, 1, 'Vn express', 'SHIP-1UF1IL0VQZ'),
(36, 4, 19990000.00, 20020000.00, 'thao2k5', 'SỐ 127 NGÕ 117 TDP CƠ KHÍ YÊN VIÊN', '0893083042', 'shipping', 'vnpay', 'paid', NULL, NULL, NULL, 'standard', 30000.00, NULL, '2026-05-20 00:18:29', '2026-05-20 00:18:59', NULL, NULL, 1, 'Vn express', 'SHIP-ZUH67HOJFM'),
(37, 2, 8990000.00, 9020000.00, 'Lương Quốc Huy', 'số 127 tổ dân phố cơ khí yên viên', '0902161559', 'shipping', 'cod', 'unpaid', NULL, NULL, NULL, 'standard', 30000.00, NULL, '2026-05-20 01:58:49', '2026-05-20 01:59:48', NULL, NULL, 2, 'Logictics VN', 'SHIP-TS9I5AT1CJ'),
(38, 2, 16990000.00, 17020000.00, 'Lương Quốc Huy', 'số 127 tổ dân phố cơ khí yên viên', '0902161559', 'shipping', 'cod', 'unpaid', NULL, NULL, NULL, 'standard', 30000.00, NULL, '2026-05-20 02:11:52', '2026-05-20 02:13:02', NULL, NULL, 2, 'Logictics VN', 'SHIP-DV6UXUVXMW'),
(39, 2, 75940000.00, 75970000.00, 'Lương Quốc Huy', 'số 127 tổ dân phố cơ khí yên viên', '0902161559', 'shipping', 'cod', 'unpaid', NULL, NULL, NULL, 'standard', 30000.00, NULL, '2026-05-20 03:59:18', '2026-05-20 04:08:57', NULL, NULL, 2, 'Logictics VN', 'SHIP-LNRLPHPPFS'),
(40, 2, 7990000.00, 8020000.00, 'Lương Quốc Huy', 'số 127 tổ dân phố cơ khí yên viên', '0902161559', 'shipping', 'momo', 'paid', NULL, NULL, NULL, 'standard', 30000.00, NULL, '2026-05-20 04:08:37', '2026-05-20 04:09:58', NULL, NULL, 1, 'Vn express', 'SHIP-XRZMEDUQIU'),
(41, 7, 49980000.00, 50030000.00, 'huynickphu', 'sô 10 Nguyễn Công Minh', '0893083042', 'delivered', 'vnpay', 'paid', '2026-06-06 03:35:22', '2026-06-06 03:32:36', NULL, 'express', 50000.00, NULL, '2026-06-03 02:31:12', '2026-06-06 03:35:22', NULL, NULL, 1, 'Vn expressr', 'SHIP-RECLQ4V4WK'),
(42, 2, 55970000.00, 51020000.00, 'Lương Quốc Huy', 'số 127 tổ dân phố cơ khí yên viên', '0902161559', 'delivered', 'cod', 'paid', '2026-06-04 02:24:34', '2026-06-04 02:19:34', NULL, 'express', 50000.00, NULL, '2026-06-04 02:13:29', '2026-06-04 02:24:34', 4, 5000000.00, 1, 'Vn expressr', 'SHIP-65HV2LQUNJ'),
(43, 2, 21990000.00, 22040000.00, 'Lương Quốc Huy', 'số 127 tổ dân phố cơ khí yên viên', '0902161559', 'delivered', 'vnpay', 'paid', '2026-06-04 02:29:29', '2026-06-04 02:27:59', NULL, 'express', 50000.00, NULL, '2026-06-04 02:26:06', '2026-06-04 02:29:29', NULL, NULL, 2, 'Logictics VN', 'SHIP-NTVDZGGOC5'),
(44, 2, 32990000.00, 33020000.00, 'Lương Quốc Huy', 'số 127 tổ dân phố cơ khí yên viên', '0902161559', 'cancelled', 'momo', 'paid', NULL, '2026-06-04 02:34:13', '2026-06-04 02:35:15', 'standard', 30000.00, NULL, '2026-06-04 02:33:30', '2026-06-04 02:35:15', NULL, NULL, 1, 'Vn expressr', 'SHIP-A7AEAMMUMB'),
(45, 2, 20990000.00, 21020000.00, 'Lương Quốc Huy', 'số 127 tổ dân phố cơ khí yên viên', '0902161559', 'pending', 'vnpay', 'paid', NULL, NULL, NULL, 'standard', 30000.00, NULL, '2026-07-13 19:47:25', '2026-07-13 19:47:26', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price_at_purchase` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `product_name`, `product_image`, `quantity`, `price_at_purchase`, `created_at`, `updated_at`) VALUES
(19, 10, 83, 'MSI Titan GT77', 'img/msititangt77.jpg', 1, 89990000.00, '2026-03-03 23:34:27', '2026-03-03 23:34:27'),
(20, 11, 9, 'Samsung Galaxy M33', 'img/galaxym33.jpg', 1, 8990000.00, '2026-03-10 00:48:12', '2026-03-10 00:48:12'),
(21, 12, 187, 'Bàn phím cơ Akko 3068B', 'img/akko-3068b.png', 3, 2590000.00, '2026-03-14 02:06:35', '2026-03-14 02:06:35'),
(22, 12, 185, 'Bàn phím cơ Logitech G Pro X', 'img/logitech-g-prox.png', 1, 3200000.00, '2026-03-14 02:06:35', '2026-03-14 02:06:35'),
(23, 12, 184, 'Bàn phím cơ Razer BlackWidow V3', 'img/ban-phim-razer.png', 1, 2990000.00, '2026-03-14 02:06:35', '2026-03-14 02:06:35'),
(24, 13, 90, 'MSI Vector GP76', 'img/msivectorgp76.jpg', 1, 30990000.00, '2026-03-15 18:14:21', '2026-03-15 18:14:21'),
(25, 14, 1, 'Samsung Galaxy S23 Ultra', 'img/s23ultra.jpg', 1, 25990000.00, '2026-03-15 19:02:34', '2026-03-15 19:02:34'),
(26, 14, 55, 'Huawei Y9a', 'img/huaway9a.jpg', 1, 7990000.00, '2026-03-15 19:02:34', '2026-03-15 19:02:34'),
(27, 15, 180, 'Chuột Razer DeathAdder V2', 'img/chuot-razer.png', 1, 1490000.00, '2026-03-15 19:17:40', '2026-03-15 19:17:40'),
(28, 15, 73, 'ASUS ROG Strix Scar 15', 'img/asusrogstrixscar15.jpg', 1, 40990000.00, '2026-03-15 19:17:40', '2026-03-15 19:17:40'),
(29, 16, 144, 'Samsung Galaxy Watch 5', 'img/watch5.jpg', 1, 7990000.00, '2026-03-16 06:29:49', '2026-03-16 06:29:49'),
(30, 17, 97, 'Dell G3 15 Gaming', 'img/dellg315.jpg', 1, 22990000.00, '2026-03-16 23:04:48', '2026-03-16 23:04:48'),
(31, 18, 1, 'Samsung Galaxy S23 Ultra', 'img/s23ultra.jpg', 1, 25990000.00, '2026-03-19 20:07:55', '2026-03-19 20:07:55'),
(32, 19, 21, 'OPPO Find X5 Pro', 'img/oppofindx5pro.jpg', 1, 21990000.00, '2026-03-21 01:33:21', '2026-03-21 01:33:21'),
(33, 20, 71, 'ASUS ROG Zephyrus G14', 'img/asusrogzephyrusg14.jpg', 3, 32990000.00, '2026-03-21 07:30:17', '2026-03-21 07:30:17'),
(34, 20, 2, 'Samsung Galaxy S23+', 'img/s23plus.jpg', 3, 23990000.00, '2026-03-21 07:30:17', '2026-03-21 07:30:17'),
(35, 21, 45, 'Google Pixel 5', 'img/pixel5.jpg', 1, 13990000.00, '2026-03-21 08:45:28', '2026-03-21 08:45:28'),
(36, 21, 68, 'Sony Xperia 5 II', 'img/sonyxperia5ii.jpg', 1, 17990000.00, '2026-03-21 08:45:28', '2026-03-21 08:45:28'),
(37, 21, 23, 'OPPO Reno 8 Pro', 'img/opporeno8pro.jpg', 1, 16990000.00, '2026-03-21 08:45:28', '2026-03-21 08:45:28'),
(38, 21, 37, 'Xiaomi Mi 11', 'img/mi11.jpg', 1, 15990000.00, '2026-03-21 08:45:28', '2026-03-21 08:45:28'),
(39, 22, 1, 'Samsung Galaxy S23 Ultra', 'img/s23ultra.jpg', 4, 25990000.00, '2026-03-24 05:13:59', '2026-03-24 05:13:59'),
(40, 23, 71, 'ASUS ROG Zephyrus G14', 'img/asusrogzephyrusg14.jpg', 3, 32990000.00, '2026-03-24 20:51:59', '2026-03-24 20:51:59'),
(41, 23, 17, 'iPhone 14', 'img/iphone14.jpg', 2, 21990000.00, '2026-03-24 20:51:59', '2026-03-24 20:51:59'),
(42, 24, 1, 'Samsung Galaxy S23 Ultra', 'img/s23ultra.jpg', 1, 25990000.00, '2026-03-24 21:09:45', '2026-03-24 21:09:45'),
(43, 25, 71, 'ASUS ROG Zephyrus G14', 'img/asusrogzephyrusg14.jpg', 1, 32990000.00, '2026-03-24 21:16:31', '2026-03-24 21:16:31'),
(44, 26, 192, 'Sạc dự phòng Energizer 30.000mAh', 'img/sacdp-energizer.png', 2, 1190000.00, '2026-03-24 21:26:36', '2026-03-24 21:26:36'),
(45, 26, 191, 'Sạc dự phòng Samsung 10.000mAh Wireless', 'img/sacdp-samsung.png', 2, 990000.00, '2026-03-24 21:26:36', '2026-03-24 21:26:36'),
(46, 26, 190, 'Sạc dự phòng Xiaomi Mi 20.000mAh 18W', 'img/sacdp-xiaomi.png', 2, 750000.00, '2026-03-24 21:26:36', '2026-03-24 21:26:36'),
(47, 27, 10, 'Samsung Galaxy M53', 'img/galaxym53.jpg', 11, 10990000.00, '2026-03-24 21:52:38', '2026-03-24 21:52:38'),
(48, 28, 40, 'Xiaomi Redmi 10A', 'img/redmi10a.jpg', 1, 3990000.00, '2026-03-28 02:28:30', '2026-03-28 02:28:30'),
(49, 28, 4, 'Samsung Galaxy A53', 'img/galaxya53.jpg', 1, 10990000.00, '2026-03-28 02:28:30', '2026-03-28 02:28:30'),
(50, 28, 18, 'iPhone 14 Plus', 'img/iphone14plus.jpg', 1, 22990000.00, '2026-03-28 02:28:30', '2026-03-28 02:28:30'),
(51, 29, 3, 'Samsung Galaxy S23', 'img/s23.jpg', 1, 21990000.00, '2026-03-29 02:01:57', '2026-03-29 02:01:57'),
(52, 29, 6, 'Samsung Galaxy Note 20', 'img/note20.jpg', 1, 18990000.00, '2026-03-29 02:01:57', '2026-03-29 02:01:57'),
(53, 29, 68, 'Sony Xperia 5 II', 'img/sonyxperia5ii.jpg', 1, 17990000.00, '2026-03-29 02:01:57', '2026-03-29 02:01:57'),
(54, 30, 1, 'Samsung Galaxy S23 Ultra', 'img/s23ultra.jpg', 1, 25990000.00, '2026-03-29 19:15:57', '2026-03-29 19:15:57'),
(55, 30, 2, 'Samsung Galaxy S23+', 'img/s23plus.jpg', 1, 23990000.00, '2026-03-29 19:15:57', '2026-03-29 19:15:57'),
(56, 30, 3, 'Samsung Galaxy S23', 'img/s23.jpg', 1, 21990000.00, '2026-03-29 19:15:57', '2026-03-29 19:15:57'),
(57, 31, 21, 'OPPO Find X5 Pro', 'img/oppofindx5pro.jpg', 2, 21990000.00, '2026-04-29 08:11:36', '2026-04-29 08:11:36'),
(58, 31, 1, 'Samsung Galaxy S23 Ultra', 'img/s23ultra.jpg', 1, 25990000.00, '2026-04-29 08:11:36', '2026-04-29 08:11:36'),
(59, 32, 1, 'Samsung Galaxy S23 Ultra', 'img/s23ultra.jpg', 1, 25990000.00, '2026-05-12 21:06:25', '2026-05-12 21:06:25'),
(60, 32, 200, 'Tablet', 'img/01KJVSPBB3RQFDYMM56VEBFXPJ.jpg', 1, 4499999.00, '2026-05-12 21:06:25', '2026-05-12 21:06:25'),
(61, 33, 22, 'OPPO Find X5', 'img/oppofindx5.jpg', 1, 19990000.00, '2026-05-19 21:20:30', '2026-05-19 21:20:30'),
(62, 33, 27, 'OPPO F21 Pro', 'img/oppof21pro.jpg', 1, 12990000.00, '2026-05-19 21:20:30', '2026-05-19 21:20:30'),
(63, 34, 53, 'Huawei P50 Pro', 'img/huawaip50pro.jpg', 1, 20990000.00, '2026-05-19 21:33:54', '2026-05-19 21:33:54'),
(64, 34, 21, 'OPPO Find X5 Pro', 'img/oppofindx5pro.jpg', 1, 21990000.00, '2026-05-19 21:33:54', '2026-05-19 21:33:54'),
(65, 35, 201, 'Máy tính bảng Samsung Galaxy Tab A11 4G 4GB/64GB', 'img/01KJVTY6BMK71KMA8WXWAEHGW6.jpg', 1, 9000000.00, '2026-05-20 00:06:14', '2026-05-20 00:06:14'),
(66, 36, 67, 'Sony Xperia 1 II', 'img/sonyxperia1ii.jpg', 1, 19990000.00, '2026-05-20 00:18:29', '2026-05-20 00:18:29'),
(67, 37, 9, 'Samsung Galaxy M33', 'img/galaxym33.jpg', 1, 8990000.00, '2026-05-20 01:58:49', '2026-05-20 01:58:49'),
(68, 38, 19, 'iPhone SE (3rd gen)', 'img/iphonese3.jpg', 1, 16990000.00, '2026-05-20 02:11:52', '2026-05-20 02:11:52'),
(69, 39, 2, 'Samsung Galaxy S23+', 'img/s23plus.jpg', 1, 23990000.00, '2026-05-20 03:59:18', '2026-05-20 03:59:18'),
(70, 39, 67, 'Sony Xperia 1 II', 'img/sonyxperia1ii.jpg', 1, 19990000.00, '2026-05-20 03:59:18', '2026-05-20 03:59:18'),
(71, 39, 55, 'Huawei Y9a', 'img/huaway9a.jpg', 4, 7990000.00, '2026-05-20 03:59:18', '2026-05-20 03:59:18'),
(72, 40, 55, 'Huawei Y9a', 'img/huaway9a.jpg', 1, 7990000.00, '2026-05-20 04:08:37', '2026-05-20 04:08:37'),
(73, 41, 2, 'Samsung Galaxy S23+', 'img/s23plus.jpg', 1, 23990000.00, '2026-06-03 02:31:12', '2026-06-03 02:31:12'),
(74, 41, 1, 'Samsung Galaxy S23 Ultra', 'img/s23ultra.jpg', 1, 25990000.00, '2026-06-03 02:31:12', '2026-06-03 02:31:12'),
(75, 42, 7, 'Samsung Galaxy Z Fold 4', 'img/zfold4.jpg', 1, 29990000.00, '2026-06-04 02:13:29', '2026-06-04 02:13:29'),
(76, 42, 21, 'OPPO Find X5 Pro', 'img/oppofindx5pro.jpg', 1, 21990000.00, '2026-06-04 02:13:29', '2026-06-04 02:13:29'),
(77, 42, 40, 'Xiaomi Redmi 10A', 'img/redmi10a.jpg', 1, 3990000.00, '2026-06-04 02:13:29', '2026-06-04 02:13:29'),
(78, 43, 75, 'ASUS ZenBook 14 UX435', 'img/asuszenbook14ux435.jpg', 1, 21990000.00, '2026-06-04 02:26:06', '2026-06-04 02:26:06'),
(79, 44, 71, 'ASUS ROG Zephyrus G14', 'img/asusrogzephyrusg14.jpg', 1, 32990000.00, '2026-06-04 02:33:30', '2026-06-04 02:33:30'),
(80, 45, 53, 'Huawei P50 Pro', 'img/huawaip50pro.jpg', 1, 20990000.00, '2026-07-13 19:47:25', '2026-07-13 19:47:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `partners`
--

CREATE TABLE `partners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('shipping_provider','supplier') NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `partners`
--

INSERT INTO `partners` (`id`, `name`, `type`, `phone`, `email`, `address`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Vn expressr', 'shipping_provider', '0012040', 'rolltothedeath@gmail.com', 'no 12. pho chua boc', 1, '2026-03-16 22:57:13', '2026-05-20 04:27:47'),
(2, 'Logictics VN', 'shipping_provider', '123213314', 'huymonsterhuman@gmail.com', 'Số 5 Thái Hà', 1, '2026-03-16 22:58:04', '2026-03-16 22:58:04'),
(3, 'Samsung', 'supplier', '000111', 'samsung@gmail.com', 'No 12, quận 1 thành phố HCM', 1, '2026-03-18 06:44:43', '2026-03-18 06:44:43'),
(4, 'Oppo', 'supplier', '232323230101', 'OOp@gmail.com', '23 Phạm Ngọc Trường, HCM Hà Nội', 1, '2026-03-19 19:33:54', '2026-03-19 19:33:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('annguyenhandsome99@gmail.com', '$2y$12$.Ra8cZMKcwb3LVoaASDnzuBqEa9aOh4T9HMOPLrxg.1YOV5K4ZJKi', '2026-03-04 20:59:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view_dashboard', 'web', '2026-03-20 03:21:30', '2026-03-20 03:21:30'),
(2, 'view_orders', 'web', '2026-03-20 03:21:30', '2026-03-20 03:21:30'),
(3, 'edit_orders', 'web', '2026-03-20 03:21:30', '2026-03-20 03:21:30'),
(4, 'confirm_orders', 'web', '2026-03-20 03:21:30', '2026-03-20 03:21:30'),
(5, 'view_products', 'web', '2026-03-20 03:21:30', '2026-03-20 03:21:30'),
(6, 'edit_products', 'web', '2026-03-20 03:21:30', '2026-03-20 03:21:30'),
(7, 'view_users', 'web', '2026-03-20 03:21:30', '2026-03-20 03:21:30'),
(8, 'edit_users', 'web', '2026-03-20 03:21:30', '2026-03-20 03:21:30'),
(9, 'view_reviews', 'web', '2026-03-20 03:21:30', '2026-03-20 03:21:30'),
(10, 'moderate_reviews', 'web', '2026-03-20 03:21:30', '2026-03-20 03:21:30'),
(11, 'view_inventory', 'web', '2026-03-20 03:21:30', '2026-03-20 03:21:30'),
(12, 'manage_banners', 'web', '2026-03-20 03:21:30', '2026-03-20 03:21:30'),
(13, 'manage_vouchers', 'web', '2026-03-20 03:21:30', '2026-03-20 03:21:30'),
(14, 'manage_shipping_providers', 'web', '2026-03-20 03:21:30', '2026-03-20 03:21:30'),
(15, 'manage_suppliers', 'web', '2026-03-20 03:21:30', '2026-03-20 03:21:30'),
(16, 'manage_roles', 'web', '2026-03-20 03:21:30', '2026-03-20 03:21:30'),
(17, 'access_admin', 'web', '2026-03-20 03:48:45', '2026-03-20 03:48:45'),
(18, 'manage_posts', 'web', '2026-03-20 04:07:02', '2026-03-20 04:07:02'),
(19, 'edit_inventory', 'web', '2026-03-20 20:26:30', '2026-03-20 20:26:30'),
(20, 'manage_brands', 'web', '2026-03-21 00:38:51', '2026-03-21 00:38:51'),
(21, 'manage_categories', 'web', '2026-03-21 00:38:51', '2026-03-21 00:38:51'),
(22, 'create_inventory', 'web', '2026-03-21 01:10:04', '2026-03-21 01:10:04'),
(23, 'create_products', 'web', '2026-03-27 09:10:14', '2026-03-27 09:10:14'),
(24, 'delete_products', 'web', '2026-03-27 09:10:14', '2026-03-27 09:10:14'),
(25, 'view_categories', 'web', '2026-03-27 09:10:14', '2026-03-27 09:10:14'),
(26, 'view_brands', 'web', '2026-03-27 09:10:14', '2026-03-27 09:10:14'),
(27, 'manage_orders', 'web', '2026-03-27 09:10:14', '2026-03-27 09:10:14'),
(28, 'manage_users', 'web', '2026-03-27 09:10:14', '2026-03-27 09:10:14'),
(29, 'view_vouchers', 'web', '2026-03-27 09:10:14', '2026-03-27 09:10:14'),
(30, 'manage_reviews', 'web', '2026-03-27 09:10:14', '2026-03-27 09:10:14'),
(31, 'view_partners', 'web', '2026-03-27 09:10:14', '2026-03-27 09:10:14'),
(32, 'manage_partners', 'web', '2026-03-27 09:10:14', '2026-03-27 09:10:14'),
(33, 'view_reports', 'web', '2026-03-27 09:10:14', '2026-03-27 09:10:14'),
(34, 'view_system_logs', 'web', '2026-03-29 21:35:41', '2026-03-29 21:35:41'),
(35, 'view_activity_logs', 'web', '2026-03-29 21:35:41', '2026-03-29 21:35:41'),
(36, 'view_order_logs', 'web', '2026-03-29 21:35:41', '2026-03-29 21:35:41'),
(37, 'manage_collections', 'web', '2026-03-29 21:35:41', '2026-03-29 21:35:41'),
(38, 'manage_inventory', 'web', '2026-03-29 21:35:41', '2026-03-29 21:35:41'),
(39, 'manage_goods_receipt', 'web', '2026-03-29 21:35:41', '2026-03-29 21:35:41'),
(40, 'manage_goods_issue', 'web', '2026-03-29 21:35:41', '2026-03-29 21:35:41'),
(41, 'manage_shipping', 'web', '2026-03-29 21:35:41', '2026-03-29 21:35:41');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `views` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `post_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `author_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `posts`
--

INSERT INTO `posts` (`id`, `title`, `slug`, `image`, `excerpt`, `content`, `is_published`, `published_at`, `views`, `created_at`, `updated_at`, `post_category_id`, `author_id`) VALUES
(1, 'Chặn số điện thoại lừa đảo trong vài giây với ứng dụng nTrust cực dễ ai cũng làm được, xem ngay hướng dẫn', 'chan-so-dien-thoai-lua-dao-trong-vai-giay-voi-ung-dung-ntrust-cuc-de-ai-cung-lam-duoc-xem-ngay-huong-dan', 'img/posts/01KJRVNBFRCEGBECGHMKHAAHKK.png', 'Chặn số điện thoại lừa đảo trong vài giây', '<h2>Trong thời đại công nghệ phát triển, những cuộc gọi lừa đảo và quấy rối qua <a href=\"https://www.thegioididong.com/dtdd\"><span style=\"text-decoration: underline;\">điện thoại</span></a> ngày càng trở nên phổ biến, gây không ít phiền toái và rủi ro cho người dùng. Để bảo vệ thông tin cá nhân và tránh những tình huống không mong muốn, việc chặn các số điện thoại lừa đảo là vô cùng cần thiết. Với ứng dụng nTrust, quá trình này trở nên đơn giản và hiệu quả hơn bao giờ hết. Trong bài viết này mình sẽ hướng dẫn bạn <a href=\"https://www.thegioididong.com/tin-tuc/cach-chan-so-dien-thoai-lua-dao-1569755\"><span style=\"text-decoration: underline;\">cách chặn số điện thoại lừa đảo</span></a> bằng phần mềm nTrust, giúp bạn an tâm hơn khi sử dụng điện thoại hàng ngày nha.</h2><h3><strong>1. Cách chặn số điện thoại lừa đảo</strong></h3><p>Cách chặn cuộc gọi quấy rối như thế nào? cách chặn cuộc gọi rác viettel ra sao? Có ứng dụng nào chặn cuộc gọi lừa đảo hay không? ... Tất tần tật sẽ được giải đáp trong bài viết này.</p><p><strong>Để có thể chặn số điện thoại lừa đảo bạn hãy làm như sau:</strong></p><p><strong>Bước 1:</strong> Vào CH Play hoặc App Store trên điện thoại của bạn &gt; <strong>Cài đặt phần mềm nTrust</strong> về điện thoại. Bạn cũng có thể nhấn vào link bên dưới để tiến hành cài đặt nhanh hơn nhé. Sau khi cài đặt xong bạn hãy <strong>mở ứng dụng này lên</strong>.</p><ul><li><a href=\"https://apps.apple.com/vn/app/ntrust-ph%C3%B2ng-ch%E1%BB%91ng-l%E1%BB%ABa-%C4%91%E1%BA%A3o/id6504554337\"><span style=\"text-decoration: underline;\">nTrust dành cho iOS</span></a></li><li><a href=\"https://play.google.com/store/search?q=ntrust&amp;c=apps&amp;hl=vi\"><span style=\"text-decoration: underline;\">nTrust dành cho Android</span></a><figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:1883,&quot;url&quot;:&quot;https://cdnv2.tgdd.vn/mwg-static/common/News/1569755/minh-1.jpg&quot;,&quot;width&quot;:1738}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://cdnv2.tgdd.vn/mwg-static/common/News/1569755/minh-1.jpg\" width=\"1738\" height=\"1883\"><figcaption class=\"attachment__caption\"></figcaption></figure></li></ul><p><strong>Bước 2:</strong> <strong>Điền số điện thoại và thông tin cá nhân của bạn</strong> để tiến hành đăng ký sử dụng phần mềm phòng chống lừa đảo miễn phí.</p><p><figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:1387,&quot;url&quot;:&quot;https://cdnv2.tgdd.vn/mwg-static/common/News/1568701/cach-nhan-biet-so-dien-thoai-lua-dao-thum-22.jpg&quot;,&quot;width&quot;:1280}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://cdnv2.tgdd.vn/mwg-static/common/News/1568701/cach-nhan-biet-so-dien-thoai-lua-dao-thum-22.jpg\" width=\"1280\" height=\"1387\"><figcaption class=\"attachment__caption\"></figcaption></figure></p><p><strong>Bước 3:</strong> Nhấn chọn vào mục <strong>Cài đặt</strong> &gt; Nhấn vào mục <strong>Kích hoạt nhận diện số điện thoại lừa đảo</strong>.</p><p><figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:1387,&quot;url&quot;:&quot;https://cdnv2.tgdd.vn/mwg-static/common/News/1569755/chan-so-lua-dao-1.jpg&quot;,&quot;width&quot;:1280}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://cdnv2.tgdd.vn/mwg-static/common/News/1569755/chan-so-lua-dao-1.jpg\" width=\"1280\" height=\"1387\"><figcaption class=\"attachment__caption\"></figcaption></figure></p><p><strong>Bước 4:</strong> Bạn hãy nhấn chọn mục <strong>Kích hoạt</strong> &gt; Chọn ứng dụng <strong>nTrust</strong> và nhấn vào mục <strong>Đặt làm mặc định là xong rồi</strong>.</p><p><figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:1387,&quot;url&quot;:&quot;https://cdnv2.tgdd.vn/mwg-static/common/News/1569755/chan-so-lua-dao-12.jpg&quot;,&quot;width&quot;:1280}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://cdnv2.tgdd.vn/mwg-static/common/News/1569755/chan-so-lua-dao-12.jpg\" width=\"1280\" height=\"1387\"><figcaption class=\"attachment__caption\"></figcaption></figure></p><h3><strong>2. nTrust là phần mềm gì?</strong></h3><p>nTrust - Phần mềm phòng chống lừa đảo do <strong>Hiệp hội An ninh mạng quốc gia phát triển</strong>, vận hành. Phần mềm hoàn toàn miễn phí, giúp phát hiện các dấu hiệu lừa đảo thông qua kiểm tra số điện thoại, địa chỉ website (link), kiểm tra số tài khoản, quét mã độc và kiểm tra mã QR.</p><p>Ứng dụng phòng chống lừa đảo này sử dụng cơ sở dữ liệu lớn về các số điện thoại, trang web, số tài khoản ngân hàng và mã QR đã được xác định là lừa đảo để cảnh báo người dùng.</p><p><figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:664,&quot;url&quot;:&quot;https://cdnv2.tgdd.vn/mwg-static/common/News/1568701/cach-nhan-biet-so-dien-thoai-lua-dao.jpg&quot;,&quot;width&quot;:1280}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://cdnv2.tgdd.vn/mwg-static/common/News/1568701/cach-nhan-biet-so-dien-thoai-lua-dao.jpg\" width=\"1280\" height=\"664\"><figcaption class=\"attachment__caption\"></figcaption></figure></p><p>Chặn các số điện thoại lừa đảo giúp bảo vệ bạn khỏi những cuộc gọi quấy rối, góp phần giữ cho cuộc sống số của bạn an toàn hơn. Với ứng dụng nTrust, quy trình này trở nên đơn giản và hiệu quả hơn bao giờ hết. Bằng những thao tác dễ dàng, bạn có thể nhanh chóng chặn các số không mong muốn, đảm bảo sự riêng tư và an tâm trong quá trình sử dụng điện thoại.&nbsp;</p><p>Nếu bạn cần một chiếc điện thoại có thể đồng hành suốt cả ngày dài mà không phải lo sạc pin nhiều lần, những mẫu máy sở hữu dung lượng pin lớn sẽ là lựa chọn rất đáng cân nhắc. Tại Thế Giới Di Động đang có nhiều sản phẩm pin trâu, đáp ứng tốt nhu cầu làm việc, học tập lẫn giải trí. Nhấn ngay nút cam bên dưới để tham khảo và chọn cho mình chiếc điện thoại phù hợp nhất.</p><p><br></p>', 1, '2026-03-28 03:53:08', 1, '2026-03-02 20:25:27', '2026-05-12 20:14:24', 1, NULL),
(2, 'Nhập mã VNPAY0126 giảm tối đa 100k cho đơn hàng khi thanh toán qua VNPay-QR', 'nhap-ma-vnpaytgdd1-giam-toi-da-150k-1573706', 'img/posts/01KMS998YKH67Z4Y3WK9YH6VEB.jpg', 'Khách hàng khi mua sắm sản phẩm công nghệ tại Thế Giới Di Động đừng quên thanh toán bằng VNPay-QR nhé. Chúng tôi đang có chương trình giảm đến 100K. Đây là cơ hội để bạn được giảm thêm tiền, tiết kiệm cũng kha khá trong thời buổi khó khăn hiện tại.', '<p><strong>1. Thời gian triển khai: </strong>Đến hết 31/03/2026.</p><p><strong>2. Nội dung chương trình:</strong></p><p>Nhập mã: <strong>VNPAY0126.</strong></p><ul><li>Giảm ngay 40,000đ cho đơn hàng tối thiểu 5,000,000đ khi thanh toán qua VNPAY áp dụng cho tất cả sản phẩm (Trừ Thu hộ, Sim, Thẻ cào, Bảo hiểm...).</li><li>Giảm ngay 100,000đ cho đơn hàng tối thiểu 15,000,000đ khi thanh toán qua VNPAY áp dụng cho tất cả sản phẩm (Trừ Thu hộ, Sim, Thẻ cào, Bảo hiểm...).</li></ul><p><strong>3. Phạm vi áp dụng:</strong></p><p>Tất cả hệ thống cửa hàng/Website Thế Giới Di Động/Điện máy XANH/TopZone trên toàn quốc.</p><p><strong>4. Sản phẩm áp dụng:</strong></p><p>Tất cả sản phẩm (Trừ dịch vụ thu hộ, thẻ cào, bảo hiểm, SIM số).</p>', 1, '2026-03-27 20:53:25', 3, '2026-03-27 20:54:55', '2026-07-13 19:46:41', 1, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `post_categories`
--

CREATE TABLE `post_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `post_categories`
--

INSERT INTO `post_categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Guide', 'guide', '2026-03-27 08:41:36', '2026-03-27 08:41:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `sale_price` decimal(15,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `screen` varchar(255) DEFAULT NULL,
  `chip` varchar(255) DEFAULT NULL,
  `camera` varchar(255) DEFAULT NULL,
  `battery` varchar(255) DEFAULT NULL,
  `os` varchar(255) DEFAULT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `weight` int(11) DEFAULT NULL COMMENT 'Weight in grams, used for shipping fee calculation',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `views` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `sku`, `price`, `sale_price`, `image`, `description`, `screen`, `chip`, `camera`, `battery`, `os`, `brand_id`, `category_id`, `stock`, `weight`, `is_featured`, `is_active`, `views`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Samsung Galaxy S23 Ultra', 'samsung-galaxy-s23-ultra', NULL, 25990000.00, NULL, 'img/s23ultra.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình Dynamic AMOLED 2X, Full HD+', 'Snapdragon 8 Gen 2', '108MP chính, hỗ trợ quang học', '5000mAh, sạc nhanh', 'Android 13', 1, 1, 15, NULL, 0, 1, 10, '2026-02-28 00:05:17', '2026-06-06 03:34:05', NULL),
(2, 'Samsung Galaxy S23+', 'samsung-galaxy-s23', NULL, 23990000.00, NULL, 'img/s23plus.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình Dynamic AMOLED 2X, Full HD+', 'Snapdragon 8 Gen 2', '50MP kép', '4700mAh, sạc nhanh', 'Android 13', 1, 1, 31, NULL, 0, 1, 10, '2026-02-28 00:05:17', '2026-06-06 03:34:05', NULL),
(3, 'Samsung Galaxy S23', 'samsung-galaxy-s23-3', NULL, 21990000.00, NULL, 'img/s23.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình Dynamic AMOLED 2X, HD+', 'Snapdragon 8 Gen 2', '50MP kép', '4500mAh, sạc nhanh', 'Android 13', 1, 1, 27, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-05-20 02:10:42', NULL),
(4, 'Samsung Galaxy A53', 'samsung-galaxy-a53-4', NULL, 10990000.00, NULL, 'img/galaxya53.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình Super AMOLED, Full HD+', 'Exynos 1280', '64MP chính', '5000mAh, sạc nhanh', 'Android 12', 1, 1, 8, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-05-20 02:10:42', NULL),
(5, 'Samsung Galaxy A73', 'samsung-galaxy-a73-5', NULL, 12990000.00, NULL, 'img/galaxya73.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình Super AMOLED, Full HD+', 'Exynos 1380', '108MP chính', '5000mAh, sạc nhanh', 'Android 13', 1, 1, 19, NULL, 0, 1, 2, '2026-02-28 00:05:17', '2026-03-29 07:39:10', NULL),
(6, 'Samsung Galaxy Note 20', 'samsung-galaxy-note-20-6', NULL, 18990000.00, NULL, 'img/note20.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình AMOLED, Full HD+', 'Snapdragon 865+', '12MP kép', '4300mAh, sạc nhanh', 'Android 10', 1, 1, 7, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-05-20 02:10:42', NULL),
(7, 'Samsung Galaxy Z Fold 4', 'samsung-galaxy-z-fold-4-7', NULL, 29990000.00, NULL, 'img/zfold4.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình Dynamic AMOLED 2X, Full HD+', 'Snapdragon 8+ Gen 1', '50MP chính', '4400mAh, sạc nhanh', 'Android 12', 1, 1, 2, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-06-04 02:23:13', NULL),
(8, 'Samsung Galaxy Z Flip 4', 'samsung-galaxy-z-flip-4-8', NULL, 19990000.00, NULL, 'img/zflip4.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình Dynamic AMOLED 2X, HD+', 'Snapdragon 8+ Gen 1', '12MP kép', '3700mAh, sạc nhanh', 'Android 12', 1, 1, 10, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-27 23:45:39', NULL),
(9, 'Samsung Galaxy M33', 'samsung-galaxy-m33-9', NULL, 8990000.00, NULL, 'img/galaxym33.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình TFT, HD+', 'Exynos 1280', '50MP chính', '6000mAh, sạc nhanh', 'Android 12', 1, 1, 4, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-05-20 02:10:30', NULL),
(10, 'Samsung Galaxy M53', 'samsung-galaxy-m53-10', NULL, 10990000.00, NULL, 'img/galaxym53.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình Super AMOLED, Full HD+', 'Snapdragon 750G', '108MP chính', '5000mAh, sạc nhanh', 'Android 13', 1, 1, 9, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-05-19 21:33:07', '2026-05-19 21:33:07'),
(11, 'iPhone 15 Pro Max', 'iphone-15-pro-max-11', NULL, 29990000.00, NULL, 'img/iphone15promax.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình 6.7 inch OLED, Full HD+', 'A17 Pro', '48MP chính, hỗ trợ quang học', '4000mAh, sạc nhanh', 'iOS 16', 2, 1, 25, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-19 20:06:15', NULL),
(12, 'iPhone 15 Pro', 'iphone-15-pro-12', NULL, 27990000.00, NULL, 'img/iphone15pro.png', 'Miễn phí bảo hành và dịch vụ', 'Màn hình 6.1 inch OLED, Full HD+', 'A17 Pro', '48MP chính, hỗ trợ quang học', '3500mAh, sạc nhanh', 'iOS 16', 2, 1, 12, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-19 20:45:34', NULL),
(13, 'iPhone 15', 'iphone-15-13', NULL, 25990000.00, NULL, 'img/iphone15.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình 6.1 inch OLED, HD+', 'A16 Bionic', '12MP kép', '3200mAh, sạc nhanh', 'iOS 16', 2, 1, 12, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-19 20:45:34', NULL),
(14, 'iPhone 15 Plus', 'iphone-15-plus-14', NULL, 26990000.00, NULL, 'img/iphone15plus.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình 6.7 inch OLED, Full HD+', 'A16 Bionic', '12MP kép', '3700mAh, sạc nhanh', 'iOS 16', 2, 1, 15, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-19 20:45:34', NULL),
(15, 'iPhone 14 Pro Max', 'iphone-14-pro-max-15', NULL, 24990000.00, NULL, 'img/iphone14promax.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình 6.7 inch OLED, Full HD+', 'A16 Bionic', '48MP chính, hỗ trợ quang học', '4200mAh, sạc nhanh', 'iOS 16', 2, 1, 12, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-19 20:45:34', NULL),
(16, 'iPhone 14 Pro', 'iphone-14-pro-16', NULL, 23990000.00, NULL, 'img/iphone14pro.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình 6.1 inch OLED, Full HD+', 'A16 Bionic', '48MP chính, hỗ trợ quang học', '3600mAh, sạc nhanh', 'iOS 16', 2, 1, 12, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-19 20:45:34', NULL),
(17, 'iPhone 14', 'iphone-14-17', NULL, 21990000.00, NULL, 'img/iphone14.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình 6.1 inch OLED, HD+', 'A15 Bionic', '12MP kép', '3300mAh, sạc nhanh', 'iOS 16', 2, 1, 4, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-24 20:51:59', NULL),
(18, 'iPhone 14 Plus', 'iphone-14-plus-18', NULL, 22990000.00, NULL, 'img/iphone14plus.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình 6.7 inch OLED, Full HD+', 'A15 Bionic', '12MP kép', '3800mAh, sạc nhanh', 'iOS 16', 2, 1, 3, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-05-20 02:10:42', NULL),
(19, 'iPhone SE (3rd gen)', 'iphone-se-3rd-gen-19', NULL, 16990000.00, NULL, 'img/iphonese3.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình 4.7 inch Retina, HD', 'A15 Bionic', '12MP đơn', '2000mAh, sạc nhanh', 'iOS 15', 2, 1, 11, NULL, 0, 1, 2, '2026-02-28 00:05:17', '2026-05-20 02:13:02', NULL),
(20, 'iPhone 13 mini', 'iphone-13-mini-20', NULL, 19990000.00, NULL, 'img/iphone13mini.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình 5.4 inch OLED, HD+', 'A15 Bionic', '12MP kép', '2400mAh, sạc nhanh', 'iOS 15', 2, 1, 15, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-19 20:47:07', NULL),
(21, 'OPPO Find X5 Pro', 'oppo-find-x5-pro-21', NULL, 21990000.00, NULL, 'img/oppofindx5pro.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình AMOLED 6.7 inch, FHD+', 'Snapdragon 8 Gen 1', '50MP chính, cảm biến Sony', '4800mAh, sạc nhanh', 'Android 12, ColorOS', 3, 1, 10, NULL, 0, 1, 2, '2026-02-28 00:05:17', '2026-06-04 02:23:13', NULL),
(22, 'OPPO Find X5', 'oppo-find-x5-22', NULL, 19990000.00, NULL, 'img/oppofindx5.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình AMOLED 6.55 inch, FHD+', 'Snapdragon 8 Gen 1', '48MP chính', '4500mAh, sạc nhanh', 'Android 12, ColorOS', 3, 1, 12, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-05-20 02:10:42', NULL),
(23, 'OPPO Reno 8 Pro', 'oppo-reno-8-pro-23', NULL, 16990000.00, NULL, 'img/opporeno8pro.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình AMOLED 6.7 inch, FHD+', 'Snapdragon 8885', '64MP chính', '4500mAh, sạc nhanh', 'Android 12, ColorOS', 3, 1, 11, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-23 06:34:56', NULL),
(24, 'OPPO Reno 8', 'oppo-reno-8-24', NULL, 14990000.00, NULL, 'img/opporeno8.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình AMOLED 6.55 inch, FHD+', 'Snapdragon 888', '50MP chính', '4300mAh, sạc nhanh', 'Android 12, ColorOS', 3, 1, 12, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-19 20:47:07', NULL),
(25, 'OPPO A95', 'oppo-a95-25', NULL, 10990000.00, NULL, 'img/oppoa95.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình LCD 6.5 inch, HD+', 'Helio P95', '48MP chính', '4000mAh, sạc nhanh', 'Android 11, ColorOS', 3, 1, 5, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-19 20:48:29', NULL),
(26, 'OPPO A57', 'oppo-a57-26', NULL, 7990000.00, NULL, 'img/oppoa57.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình LCD 6.5 inch, HD+', 'Helio P35', '13MP chính', '3500mAh', 'Android 10, ColorOS', 3, 1, 22, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-19 20:48:29', NULL),
(27, 'OPPO F21 Pro', 'oppo-f21-pro-27', NULL, 12990000.00, NULL, 'img/oppof21pro.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình AMOLED 6.43 inch, FHD+', 'Snapdragon 720G', '48MP chính', '4100mAh, sạc nhanh', 'Android 11, ColorOS', 3, 1, 11, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-05-20 02:10:42', NULL),
(28, 'OPPO F19 Pro', 'oppo-f19-pro-28', NULL, 11990000.00, NULL, 'img/oppof19pro.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình AMOLED 6.43 inch, FHD+', 'Snapdragon 720G', '48MP chính', '4000mAh, sạc nhanh', 'Android 11, ColorOS', 3, 1, 12, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-19 20:48:29', NULL),
(29, 'OPPO Reno 7', 'oppo-reno-7-29', NULL, 13990000.00, NULL, 'img/opporeno7.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình AMOLED 6.55 inch, FHD+', 'MediaTek Dimensity 900', '64MP chính', '4500mAh, sạc nhanh', 'Android 12, ColorOS', 3, 1, 12, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-19 20:48:29', NULL),
(30, 'OPPO Reno 6 Pro', 'oppo-reno-6-pro-30', NULL, 12990000.00, NULL, 'img/opporeno6pro.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình AMOLED 6.55 inch, FHD+', 'MediaTek Dimensity 1200', '64MP chính', '4300mAh, sạc nhanh', 'Android 12, ColorOS', 3, 1, 7, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-19 20:48:29', NULL),
(31, 'Xiaomi 13 Pro', 'xiaomi-13-pro-31', NULL, 18990000.00, NULL, 'img/xiaomi13pro.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình AMOLED 6.73 inch, QHD+', 'Snapdragon 8 Gen 2', '50MP chính, cảm biến lớn', '4820mAh, sạc nhanh', 'Android 13, MIUI 14', 4, 1, 12, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-19 20:48:29', NULL),
(32, 'Xiaomi 13', 'xiaomi-13-32', NULL, 16990000.00, NULL, 'img/xiaomi13.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình AMOLED 6.55 inch, Full HD+', 'Snapdragon 8 Gen 2', '50MP chính', '4500mAh, sạc nhanh', 'Android 13, MIUI 14', 4, 1, 15, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-19 20:48:29', NULL),
(33, 'Xiaomi Redmi Note 12 Pro', 'xiaomi-redmi-note-12-pro-33', NULL, 7990000.00, NULL, 'img/redminote12pro.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình AMOLED 6.67 inch, Full HD+', 'MediaTek Dimensity 1080', '108MP chính', '5000mAh, sạc nhanh', 'Android 12, MIUI 14', 4, 1, 12, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-19 20:48:29', NULL),
(34, 'Xiaomi Redmi Note 12', 'xiaomi-redmi-note-12-34', NULL, 6990000.00, NULL, 'img/redminote12.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình LCD 6.5 inch, HD+', 'MediaTek Dimensity 920', '50MP chính', '5000mAh, sạc nhanh', 'Android 12, MIUI 14', 4, 1, 12, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-19 20:48:29', NULL),
(35, 'Xiaomi Redmi 10', 'xiaomi-redmi-10-35', NULL, 4990000.00, NULL, 'img/redmi10.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình LCD 6.5 inch, HD+', 'Helio G88', '50MP chính', '5000mAh', 'Android 11, MIUI 12', 4, 1, 9, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-27 23:38:06', NULL),
(36, 'Xiaomi Mi 11 Ultra', 'xiaomi-mi-11-ultra-36', NULL, 17990000.00, NULL, 'img/mi11ultra.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình AMOLED 6.81 inch, QHD+', 'Snapdragon 888', '50MP chính, hỗ trợ quang học', '5000mAh, sạc nhanh', 'Android 11, MIUI 12', 4, 1, 12, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-20 20:12:47', NULL),
(37, 'Xiaomi Mi 11', 'xiaomi-mi-11-37', NULL, 15990000.00, NULL, 'img/mi11.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình AMOLED 6.81 inch, Full HD+', 'Snapdragon 888', '108MP chính, hỗ trợ quang học', '4600mAh, sạc nhanh', 'Android 11, MIUI 12', 4, 1, 18, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-21 08:45:28', NULL),
(38, 'Xiaomi Poco F4', 'xiaomi-poco-f4-38', NULL, 10990000.00, NULL, 'img/pocof4.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình AMOLED 6.67 inch, Full HD+', 'Snapdragon 870', '64MP chính', '4500mAh, sạc nhanh', 'Android 12, MIUI 12', 4, 1, 11, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-20 20:12:47', NULL),
(39, 'Xiaomi Redmi K40', 'xiaomi-redmi-k40-39', NULL, 9990000.00, NULL, 'img/redmik40.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình AMOLED 6.67 inch, Full HD+', 'Snapdragon 870', '48MP chính', '4520mAh, sạc nhanh', 'Android 12, MIUI 12', 4, 1, 5, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-20 20:12:47', NULL),
(40, 'Xiaomi Redmi 10A', 'xiaomi-redmi-10a-40', NULL, 3990000.00, NULL, 'img/redmi10a.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình LCD 6.53 inch, HD+', 'Unisoc SC9863A', '13MP chính', '5000mAh', 'Android 11, MIUI 12', 4, 1, 9, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-06-04 02:23:14', NULL),
(41, 'Google Pixel 7 Pro', 'google-pixel-7-pro-41', NULL, 20990000.00, NULL, 'img/pixel7pro.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình LTPO OLED 6.7 inch, QHD+', 'Tensor G2', '50MP chính, hỗ trợ AI', '5000mAh, sạc nhanh', 'Android 13, Stock Android', 5, 1, 11, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-20 20:12:47', NULL),
(42, 'Google Pixel 7', 'google-pixel-7-42', NULL, 17990000.00, NULL, 'img/pixel7.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình LTPO OLED 6.3 inch, FHD+', 'Tensor G2', '50MP chính', '4355mAh, sạc nhanh', 'Android 13, Stock Android', 5, 1, 16, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-20 20:12:47', NULL),
(43, 'Google Pixel 6 Pro', 'google-pixel-6-pro-43', NULL, 18990000.00, NULL, 'img/pixel6pro.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình LTPO OLED 6.71 inch, QHD+', 'Tensor', '50MP chính, hỗ trợ AI', '5000mAh, sạc nhanh', 'Android 12, Stock Android', 5, 1, 2, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-20 20:12:47', NULL),
(44, 'Google Pixel 6', 'google-pixel-6-44', NULL, 15990000.00, NULL, 'img/pixel6.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 6.4 inch, FHD+', 'Tensor', '50MP chính', '4614mAh, sạc nhanh', 'Android 12, Stock Android', 5, 1, 11, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-20 20:12:47', NULL),
(45, 'Google Pixel 5', 'google-pixel-5-45', NULL, 13990000.00, NULL, 'img/pixel5.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 6.0 inch, FHD+', 'Snapdragon 765G', '12.2MP kép', '4080mAh, sạc nhanh', 'Android 11, Stock Android', 5, 1, 10, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-05-20 02:10:42', NULL),
(46, 'Google Pixel 4a 5G', 'google-pixel-4a-5g-46', NULL, 14990000.00, NULL, 'img/pixel4a5g.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 6.2 inch, FHD+', 'Snapdragon 765G', '12.2MP kép', '3885mAh, sạc nhanh', 'Android 11, Stock Android', 5, 1, 20, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-21 01:30:26', NULL),
(47, 'Google Pixel 4a', 'google-pixel-4a-47', NULL, 12990000.00, NULL, 'img/pixel4a.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 5.8 inch, HD+', 'Snapdragon 730G', '12.2MP kép', '3140mAh, sạc nhanh', 'Android 11, Stock Android', 5, 1, 15, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-21 01:19:58', NULL),
(48, 'Google Pixel 4', 'google-pixel-4-48', NULL, 11990000.00, NULL, 'img/pixel4.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 5.7 inch, FHD+', 'Snapdragon 855', '12.2MP kép', '2800mAh, sạc nhanh', 'Android 10, Stock Android', 5, 1, 11, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-21 01:19:58', NULL),
(49, 'Google Pixel 3a', 'google-pixel-3a-49', NULL, 10990000.00, NULL, 'img/pixel3a.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 5.6 inch, FHD+', 'Snapdragon 670', '12.2MP kép', '3000mAh, sạc nhanh', 'Android 9, Stock Android', 5, 1, 11, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-24 08:16:31', NULL),
(50, 'Google Pixel 3', 'google-pixel-3-50', NULL, 9990000.00, NULL, 'img/pixel3.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 5.5 inch, HD+', 'Snapdragon 670', '12.2MP kép', '2915mAh, sạc nhanh', 'Android 9, Stock Android', 5, 1, 12, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-24 08:16:31', NULL),
(51, 'Huawei Mate 50 Pro', 'huawei-mate-50-pro-51', NULL, 22990000.00, NULL, 'img/huawaimate50pro.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 6.74 inch, FHD+', 'Kirin 9000', '50MP chính, hỗ trợ quang học', '4700mAh, sạc nhanh', 'HarmonyOS 3.0', 6, 1, 5, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-24 08:16:31', NULL),
(52, 'Huawei Mate 40 Pro', 'huawei-mate-40-pro-52', NULL, 21990000.00, NULL, 'img/huawaimate40pro.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 6.76 inch, FHD+', 'Kirin 9000', '50MP chính, hỗ trợ quang học', '4400mAh, sạc nhanh', 'HarmonyOS 2.0', 6, 1, 10, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-29 03:05:45', NULL),
(53, 'Huawei P50 Pro', 'huawei-p50-pro-53', NULL, 20990000.00, NULL, 'img/huawaip50pro.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 6.6 inch, FHD+', 'Kirin 9000', '50MP chính, hỗ trợ quang học', '4360mAh, sạc nhanh', 'HarmonyOS 2.0', 6, 1, 7, NULL, 0, 1, 4, '2026-02-28 00:05:17', '2026-07-13 19:46:21', NULL),
(54, 'Huawei Nova 9', 'huawei-nova-9-54', NULL, 12990000.00, NULL, 'img/huawainova9.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 6.57 inch, FHD+', 'Snapdragon 778G', '64MP chính', '4300mAh, sạc nhanh', 'HarmonyOS 2.0', 6, 1, 12, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-29 03:05:45', NULL),
(55, 'Huawei Y9a', 'huawei-y9a-55', NULL, 7990000.00, NULL, 'img/huaway9a.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình IPS LCD 6.63 inch, HD+', 'Kirin 710A', '48MP chính', '5000mAh, sạc nhanh', 'Android 10, EMUI 10', 6, 1, 10, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-05-20 04:11:33', NULL),
(56, 'Huawei Mate Xs', 'huawei-mate-xs-56', NULL, 24990000.00, NULL, 'img/huawaimatex.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình AMOLED gập 8.0 inch, FHD+', 'Kirin 990 5G', '40MP chính', '4500mAh, sạc nhanh', 'HarmonyOS 2.0', 6, 1, 15, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-29 03:05:45', NULL),
(57, 'Huawei P40 Pro', 'huawei-p40-pro-57', NULL, 19990000.00, NULL, 'img/huawaip40pro.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 6.58 inch, FHD+', 'Kirin 990 5G', '50MP chính, hỗ trợ quang học', '4200mAh, sạc nhanh', 'Android 10, EMUI 10', 6, 1, 5, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-29 03:05:45', NULL),
(58, 'Huawei Mate 30 Pro', 'huawei-mate-30-pro-58', NULL, 18990000.00, NULL, 'img/huawaimate30pro.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 6.53 inch, FHD+', 'Kirin 990', '40MP chính', '4500mAh, sạc nhanh', 'Android 10, EMUI 10', 6, 1, 11, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-29 03:46:00', NULL),
(59, 'Huawei Nova 8', 'huawei-nova-8-59', NULL, 11990000.00, NULL, 'img/huawainova8.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 6.57 inch, FHD+', 'Snapdragon 765G', '64MP chính', '3800mAh, sạc nhanh', 'Android 10, EMUI 10', 6, 1, 11, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-29 03:46:00', NULL),
(60, 'Huawei Enjoy 20 Pro', 'huawei-enjoy-20-pro-60', NULL, 8990000.00, NULL, 'img/huawaienjoy20pro.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình IPS LCD 6.3 inch, HD+', 'Kirin 710', '16MP chính', '4000mAh', 'Android 10, EMUI 10', 6, 1, 11, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-29 03:46:00', NULL),
(61, 'Sony Xperia 1 IV', 'sony-xperia-1-iv-61', NULL, 21990000.00, NULL, 'img/sonyxperia1iv.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 6.5 inch, 4K HDR', 'Snapdragon 8 Gen 1', '12MP ba camera chuyên nghiệp', '5000mAh, sạc nhanh', 'Android 12', 7, 1, 12, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-29 03:55:03', NULL),
(62, 'Sony Xperia 5 IV', 'sony-xperia-5-iv-62', NULL, 19990000.00, NULL, 'img/sonyxperia5iv.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 6.1 inch, FHD+', 'Snapdragon 8 Gen 1', '12MP kép', '4500mAh, sạc nhanh', 'Android 12', 7, 1, 11, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-29 03:46:00', NULL),
(63, 'Sony Xperia 10 IV', 'sony-xperia-10-iv-63', NULL, 12990000.00, NULL, 'img/sonyxperia10iv.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 6.0 inch, FHD+', 'Snapdragon 695', '12MP đơn', '4500mAh, sạc nhanh', 'Android 12', 7, 1, 5, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-29 03:55:03', NULL),
(64, 'Sony Xperia 1 III', 'sony-xperia-1-iii-64', NULL, 20990000.00, NULL, 'img/sonyxperia1iii.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 6.5 inch, 4K HDR', 'Snapdragon 888', '12MP ba camera chuyên nghiệp', '4800mAh, sạc nhanh', 'Android 11', 7, 1, 6, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-29 03:55:03', NULL),
(65, 'Sony Xperia 5 III', 'sony-xperia-5-iii-65', NULL, 18990000.00, NULL, 'img/sonyxperia5iii.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 6.1 inch, FHD+', 'Snapdragon 888', '12MP kép', '4500mAh, sạc nhanh', 'Android 11', 7, 1, 11, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-29 03:55:03', NULL),
(66, 'Sony Xperia 10 III', 'sony-xperia-10-iii-66', NULL, 11990000.00, NULL, 'img/sonyxperia10iii.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 6.0 inch, FHD+', 'Snapdragon 690', '12MP đơn', '4000mAh, sạc nhanh', 'Android 11', 7, 1, 10, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-05-20 00:43:31', NULL),
(67, 'Sony Xperia 1 II', 'sony-xperia-1-ii-67', NULL, 19990000.00, NULL, 'img/sonyxperia1ii.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 6.5 inch, 4K HDR', 'Snapdragon 865', '12MP ba camera chuyên nghiệp', '4500mAh, sạc nhanh', 'Android 10', 7, 1, 13, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-05-20 04:08:57', NULL),
(68, 'Sony Xperia 5 II', 'sony-xperia-5-ii-68', NULL, 17990000.00, NULL, 'img/sonyxperia5ii.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 6.1 inch, FHD+', 'Snapdragon 865', '12MP kép', '4300mAh, sạc nhanh', 'Android 10', 7, 1, 10, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-05-20 02:10:42', NULL),
(69, 'Sony Xperia 10 II', 'sony-xperia-10-ii-69', NULL, 10990000.00, NULL, 'img/sonyxperia10ii.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình OLED 6.0 inch, FHD+', 'Snapdragon 690', '12MP đơn', '4000mAh, sạc nhanh', 'Android 10', 7, 1, 19, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-19 20:47:07', NULL),
(70, 'Sony Xperia L4', 'sony-xperia-l4-70', NULL, 8990000.00, NULL, 'img/sonyxperial4.jpg', 'Miễn phí bảo hành và dịch vụ', 'Màn hình IPS LCD 6.2 inch, HD+', 'Unisoc T610', '13MP đơn', '3580mAh', 'Android 10', 7, 1, 13, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-19 20:47:07', NULL),
(71, 'ASUS ROG Zephyrus G14', 'asus-rog-zephyrus-g14-71', NULL, 32990000.00, NULL, 'img/asusrogzephyrusg14.jpg', 'Miễn phí bảo hành và dịch vụ', '14 inch, QHD, 120Hz', 'AMD Ryzen 9 6900HS', 'HD webcam', '76Wh', 'Windows 11 Home', 8, 2, 13, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-06-04 02:35:15', NULL),
(72, 'ASUS ROG Zephyrus G15', 'asus-rog-zephyrus-g15-72', NULL, 35990000.00, NULL, 'img/asusrogzephyrusg15.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, QHD, 165Hz', 'AMD Ryzen 9 6900HS', 'HD webcam', '90Wh', 'Windows 11 Home', 8, 2, 10, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-05-20 00:43:31', NULL),
(73, 'ASUS ROG Strix Scar 15', 'asus-rog-strix-scar-15-73', NULL, 40990000.00, NULL, 'img/asusrogstrixscar15.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, Full HD, 300Hz', 'Intel Core i9-12900H', 'HD webcam', '86Wh', 'Windows 11 Home', 8, 2, 10, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-05-20 00:43:31', NULL),
(74, 'ASUS TUF Gaming F15', 'asus-tuf-gaming-f15-74', NULL, 24990000.00, NULL, 'img/asustufgamingf15.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, Full HD, 144Hz', 'Intel Core i7-12700H', 'HD webcam', '48Wh', 'Windows 11 Home', 8, 2, 10, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-05-20 00:43:31', NULL),
(75, 'ASUS ZenBook 14 UX435', 'asus-zenbook-14-ux435-75', NULL, 21990000.00, NULL, 'img/asuszenbook14ux435.jpg', 'Miễn phí bảo hành và dịch vụ', '14 inch, Full HD', 'Intel Core i7-1165G7', 'HD webcam', '67Wh', 'Windows 11 Home', 8, 2, 11, NULL, 0, 1, 1, '2026-02-28 00:05:17', '2026-06-04 02:31:59', NULL),
(76, 'ASUS VivoBook 15 X1504VA', 'asus-vivobook-15-x1504va-76', NULL, 17990000.00, NULL, 'img/asusvivobook15.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, Full HD', 'Intel Core i5-1135G7', 'HD webcam', '42Wh', 'Windows 11 Home', 8, 2, 11, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-05-20 04:11:33', NULL),
(77, 'ASUS ExpertBook B9', 'asus-expertbook-b9-77', NULL, 28990000.00, NULL, 'img/asusexpertbookb9.jpg', 'Miễn phí bảo hành và dịch vụ', '14 inch, Full HD', 'Intel Core i7-1165G7', 'HD webcam', '56Wh', 'Windows 11 Pro', 8, 2, 10, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-05-20 04:11:33', NULL),
(78, 'ASUS ROG Flow X13', 'asus-rog-flow-x13-78', NULL, 29990000.00, NULL, 'img/asusrogflowx13.jpg', 'Miễn phí bảo hành và dịch vụ', '13.4 inch, Full HD, touch', 'AMD Ryzen 9 5980HS', 'HD webcam', '62Wh', 'Windows 11 Home', 8, 2, 10, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-05-20 04:11:33', NULL),
(79, 'ASUS ROG Strix G17', 'asus-rog-strix-g17-79', NULL, 37990000.00, NULL, 'img/asusrogstrixg17.jpg', 'Miễn phí bảo hành và dịch vụ', '17.3 inch, Full HD, 144Hz', 'AMD Ryzen 7 6800H', 'HD webcam', '90Wh', 'Windows 11 Home', 8, 2, 10, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-05-20 04:11:33', NULL),
(80, 'ASUS ZenBook Pro Duo 15', 'asus-zenbook-pro-duo-15-80', NULL, 49990000.00, NULL, 'img/asuszenbookproduo15.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch 4K OLED, touch', 'Intel Core i9-11900H', 'HD webcam', '95Wh', 'Windows 11 Pro', 8, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(81, 'MSI Stealth 15M', 'msi-stealth-15m-81', NULL, 29990000.00, NULL, 'img/msistealth15m.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, FHD, 144Hz', 'Intel Core i7-11800H', 'HD webcam', '51Wh', 'Windows 11 Home', 9, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(82, 'MSI Raider GE66', 'msi-raider-ge66-82', NULL, 40990000.00, NULL, 'img/msiraiderge66.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, QHD, 240Hz', 'Intel Core i9-12900HK', 'HD webcam', '99.9Wh', 'Windows 11 Home', 9, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(83, 'MSI Titan GT77', 'msi-titan-gt77-83', NULL, 89990000.00, NULL, 'img/msititangt77.jpg', 'Miễn phí bảo hành và dịch vụ', '17.3 inch, 4K, 120Hz', 'Intel Core i9-13980HX', 'HD webcam', '99Wh', 'Windows 11 Home', 9, 2, 0, NULL, 0, 1, 3, '2026-02-28 00:05:17', '2026-03-03 20:28:03', NULL),
(84, 'MSI Modern 15', 'msi-modern-15-84', NULL, 19990000.00, NULL, 'img/msimodern15.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, FHD, 60Hz', 'Intel Core i5-1135G7', 'HD webcam', '42Wh', 'Windows 11 Home', 9, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(85, 'MSI Prestige 14', 'msi-prestige-14-85', NULL, 22990000.00, NULL, 'img/msiprestige14.jpg', 'Miễn phí bảo hành và dịch vụ', '14 inch, FHD, IPS', 'Intel Core i7-1165G7', 'HD webcam', '58Wh', 'Windows 11 Home', 9, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(86, 'MSI Creator 15', 'msi-creator-15-86', NULL, 26990000.00, NULL, 'img/msicreator15.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, 4K, Adobe RGB', 'Intel Core i7-11800H', 'HD webcam', '72Wh', 'Windows 11 Home', 9, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(87, 'MSI Pulse GL66', 'msi-pulse-gl66-87', NULL, 21990000.00, NULL, 'img/msipulsegl66.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, FHD, 144Hz', 'Intel Core i7-12700H', 'HD webcam', '55Wh', 'Windows 11 Home', 9, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(88, 'MSI Delta 15', 'msi-delta-15-88', NULL, 18990000.00, NULL, 'img/msidelta15.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, FHD, 60Hz', 'AMD Ryzen 7 5800H', 'HD webcam', '52Wh', 'Windows 11 Home', 9, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(89, 'MSI GF65 Thin', 'msi-gf65-thin-89', NULL, 16990000.00, NULL, 'img/msigf65thin.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, FHD, 144Hz', 'Intel Core i5-10500H', 'HD webcam', '51Wh', 'Windows 11 Home', 9, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(90, 'MSI Vector GP76', 'msi-vector-gp76-90', NULL, 30990000.00, NULL, 'img/msivectorgp76.jpg', 'Miễn phí bảo hành và dịch vụ', '17.3 inch, FHD, 144Hz', 'Intel Core i7-11800H', 'HD webcam', '84Wh', 'Windows 11 Home', 9, 2, 0, NULL, 0, 1, 3, '2026-02-28 00:05:17', '2026-03-16 06:45:38', NULL),
(91, 'Dell XPS 13', 'dell-xps-13-91', NULL, 28990000.00, NULL, 'img/dellxps13.jpg', 'Miễn phí bảo hành và dịch vụ', '13.4 inch, FHD+ InfinityEdge', 'Intel Core i7-1165G7', 'HD webcam', '52Wh', 'Windows 11 Home', 10, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(92, 'Dell XPS 15', 'dell-xps-15-92', NULL, 35990000.00, NULL, 'img/dellxps15.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, 4K OLED', 'Intel Core i7-11800H', 'HD webcam', '86Wh', 'Windows 11 Home', 10, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(93, 'Dell Inspiron 15 5000', 'dell-inspiron-15-5000-93', NULL, 19990000.00, NULL, 'img/dellinspiron15.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, FHD', 'Intel Core i5-1135G7', 'HD webcam', '42Wh', 'Windows 11 Home', 10, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(94, 'Dell Inspiron 17 3000', 'dell-inspiron-17-3000-94', NULL, 18990000.00, NULL, 'img/dellinspiron17.jpg', 'Miễn phí bảo hành và dịch vụ', '17.3 inch, FHD', 'Intel Core i5-1135G7', 'HD webcam', '45Wh', 'Windows 11 Home', 10, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(95, 'Dell Latitude 7420', 'dell-latitude-7420-95', NULL, 32990000.00, NULL, 'img/delllatitude7420.jpg', 'Miễn phí bảo hành và dịch vụ', '14 inch, FHD', 'Intel Core i7-1165G7', 'HD webcam', '60Wh', 'Windows 11 Pro', 10, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(96, 'Dell Precision 5550', 'dell-precision-5550-96', NULL, 39990000.00, NULL, 'img/dellprecision5550.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, 4K UHD', 'Intel Xeon', 'HD webcam', '97Wh', 'Windows 11 Pro', 10, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(97, 'Dell G3 15 Gaming', 'dell-g3-15-gaming-97', NULL, 22990000.00, NULL, 'img/dellg315.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, FHD, 120Hz', 'Intel Core i5-10500H', 'HD webcam', '56Wh', 'Windows 11 Home', 10, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(98, 'Dell G7 15 Gaming', 'dell-g7-15-gaming-98', NULL, 27990000.00, NULL, 'img/dellg715.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, FHD, 144Hz', 'Intel Core i7-10750H', 'HD webcam', '70Wh', 'Windows 11 Home', 10, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(99, 'Dell Alienware m15 R6', 'dell-alienware-m15-r6-99', NULL, 44990000.00, NULL, 'img/dellalienwarem15r6.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, FHD, 300Hz', 'Intel Core i9-11900H', 'HD webcam', '86Wh', 'Windows 11 Home', 10, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(100, 'Dell Alienware m17 R5', 'dell-alienware-m17-r5-100', NULL, 49990000.00, NULL, 'img/dellalienwarem17r5.jpg', 'Miễn phí bảo hành và dịch vụ', '17.3 inch, FHD, 360Hz', 'Intel Core i9-11900HK', 'HD webcam', '97Wh', 'Windows 11 Home', 10, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(101, 'HP Spectre x360 14', 'hp-spectre-x360-14-101', NULL, 39990000.00, NULL, 'img/hpspectrex36014.jpg', 'Miễn phí bảo hành và dịch vụ', '14 inch, FHD, touch', 'Intel Core i7-1165G7', 'HD webcam', '60Wh', 'Windows 11 Home', 11, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(102, 'HP ENVY x360 15', 'hp-envy-x360-15-102', NULL, 35990000.00, NULL, 'img/hpenvyx36015.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, FHD, touch', 'Intel Core i7-1165G7', 'HD webcam', '65Wh', 'Windows 11 Home', 11, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(103, 'HP Pavilion 15', 'hp-pavilion-15-103', NULL, 21990000.00, NULL, 'img/hppavilion15.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, FHD', 'Intel Core i5-1135G7', 'HD webcam', '42Wh', 'Windows 11 Home', 11, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(104, 'HP Pavilion Gaming 15', 'hp-pavilion-gaming-15-104', NULL, 24990000.00, NULL, 'img/hppaviliongaming15.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, FHD, 144Hz', 'Intel Core i5-10300H', 'HD webcam', '48Wh', 'Windows 11 Home', 11, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(105, 'HP Elite Dragonfly', 'hp-elite-dragonfly-105', NULL, 44990000.00, NULL, 'img/hpelitedragonfly.jpg', 'Miễn phí bảo hành và dịch vụ', '13.3 inch, FHD', 'Intel Core i7-1165G7', 'HD webcam', '56Wh', 'Windows 11 Pro', 11, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(106, 'HP Omen 15', 'hp-omen-15-106', NULL, 32990000.00, NULL, 'img/hpomen15.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, FHD, 144Hz', 'Intel Core i7-10750H', 'HD webcam', '70Wh', 'Windows 11 Home', 11, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(107, 'HP Omen 17', 'hp-omen-17-107', NULL, 39990000.00, NULL, 'img/hpomen17.jpg', 'Miễn phí bảo hành và dịch vụ', '17.3 inch, FHD, 144Hz', 'Intel Core i7-10750H', 'HD webcam', '80Wh', 'Windows 11 Home', 11, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(108, 'HP ProBook 450 G8', 'hp-probook-450-g8-108', NULL, 23990000.00, NULL, 'img/hpprobook450g8.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, FHD', 'Intel Core i5-1135G7', 'HD webcam', '45Wh', 'Windows 11 Pro', 11, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(109, 'HP ZBook Firefly 15 G8', 'hp-zbook-firefly-15-g8-109', NULL, 31990000.00, NULL, 'img/hpzbookfirefly15g8.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, FHD', 'Intel Core i7-1165G7', 'HD webcam', '56Wh', 'Windows 11 Pro', 11, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(110, 'HP ZBook Studio G8', 'hp-zbook-studio-g8-110', NULL, 44990000.00, NULL, 'img/hpzbookstudiog8.jpg', 'Miễn phí bảo hành và dịch vụ', '15.6 inch, 4K UHD', 'Intel Core i9-11900H', 'HD webcam', '97Wh', 'Windows 11 Pro', 11, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(111, 'MacBook Air M2', 'macbook-air-m2-111', NULL, 34990000.00, NULL, 'img/macbookairm2.jpg', 'Miễn phí bảo hành và dịch vụ', '13.6 inch Retina', 'Apple M2', '1080p FaceTime HD', '52.6Wh', 'macOS Ventura', 2, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(112, 'MacBook Air M1', 'macbook-air-m1-112', NULL, 29990000.00, NULL, 'img/macbookairm1.jpg', 'Miễn phí bảo hành và dịch vụ', '13.3 inch Retina', 'Apple M1', '720p FaceTime HD', '49.9Wh', 'macOS Monterey', 2, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(113, 'MacBook Pro 13-inch M2', 'macbook-pro-13-inch-m2-113', NULL, 38990000.00, NULL, 'img/macbookprom2_13.jpg', 'Miễn phí bảo hành và dịch vụ', '13.3 inch Retina', 'Apple M2', '720p FaceTime HD', '58.2Wh', 'macOS Ventura', 2, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(114, 'MacBook Pro 13-inch M1', 'macbook-pro-13-inch-m1-114', NULL, 35990000.00, NULL, 'img/macbookpro13m1.jpg', 'Miễn phí bảo hành và dịch vụ', '13.3 inch Retina', 'Apple M1', '720p FaceTime HD', '58.2Wh', 'macOS Monterey', 2, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(115, 'MacBook Pro 14-inch M1 Pro', 'macbook-pro-14-inch-m1-pro-115', NULL, 49990000.00, NULL, 'img/macbookpro14m1pro.jpg', 'Miễn phí bảo hành và dịch vụ', '14.2 inch Liquid Retina XDR', 'Apple M1 Pro', '1080p FaceTime HD', '70Wh', 'macOS Monterey', 2, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(116, 'MacBook Pro 16-inch M1 Max', 'macbook-pro-16-inch-m1-max-116', NULL, 69990000.00, NULL, 'img/macbookpro16m1max.jpg', 'Miễn phí bảo hành và dịch vụ', '16.2 inch Liquid Retina XDR', 'Apple M1 Max', '1080p FaceTime HD', '100Wh', 'macOS Monterey', 2, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(117, 'MacBook Pro 14-inch M2 Pro', 'macbook-pro-14-inch-m2-pro-117', NULL, 52990000.00, NULL, 'img/macbookpro14m2pro.jpg', 'Miễn phí bảo hành và dịch vụ', '14.2 inch Liquid Retina XDR', 'Apple M2 Pro', '1080p FaceTime HD', '70Wh', 'macOS Ventura', 2, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(118, 'MacBook Pro 16-inch M2 Max', 'macbook-pro-16-inch-m2-max-118', NULL, 74990000.00, NULL, 'img/macbookpro16m2max.jpg', 'Miễn phí bảo hành và dịch vụ', '16.2 inch Liquid Retina XDR', 'Apple M2 Max', '1080p FaceTime HD', '100Wh', 'macOS Ventura', 2, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(119, 'MacBook Pro 13-inch (Intel)', 'macbook-pro-13-inch-intel-119', NULL, 32990000.00, NULL, 'img/macbookpro13intel.jpg', 'Miễn phí bảo hành và dịch vụ', '13.3 inch Retina', 'Intel Core i5', '720p FaceTime HD', '58Wh', 'macOS Big Sur', 2, 2, 0, NULL, 0, 1, 1, '2026-02-28 00:05:17', '2026-03-01 20:00:45', NULL),
(120, 'MacBook Air (Intel)', 'macbook-air-intel-120', NULL, 27990000.00, NULL, 'img/macbookairintel.jpg', 'Miễn phí bảo hành và dịch vụ', '13.3 inch Retina', 'Intel Core i5', '720p FaceTime HD', '49Wh', 'macOS Mojave', 2, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(121, 'MacBook Air M4', 'macbook-air-m4-121', NULL, 41990000.00, NULL, 'img/macbookairm4.png', 'Miễn phí bảo hành và dịch vụ.', '15.3 inch Retina', 'Apple M4', '1080p FaceTime HD', '66.5Wh', 'macOS Sequoia', 2, 2, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(122, 'iPad Pro 12.9 (2022)', 'ipad-pro-129-2022-122', NULL, 25990000.00, NULL, 'img/ipad_pro_12_9.png', 'Máy tính bảng cao cấp từ Apple với màn hình lớn.', '12.9 inch Liquid Retina', 'Apple M2', '12MP + LiDAR', '10,758mAh', 'iPadOS 16', 2, 3, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(123, 'iPad Air 5 (2022)', 'ipad-air-5-2022-123', NULL, 16990000.00, NULL, 'img/ipad_air_5.png', 'Máy tính bảng mạnh mẽ với chip M1.', '10.9 inch Liquid Retina', 'Apple M1', '12MP', '7,606mAh', 'iPadOS 16', 2, 3, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(124, 'iPad 10 (2022)', 'ipad-10-2022-124', NULL, 12990000.00, NULL, 'img/ipad_10.png', 'Mẫu iPad phổ thông với thiết kế mới.', '10.9 inch Liquid Retina', 'Apple A14 Bionic', '12MP', '8,700mAh', 'iPadOS 16', 2, 3, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(125, 'iPad Mini 6 (2021)', 'ipad-mini-6-2021-125', NULL, 14990000.00, NULL, 'img/ipad_mini_6.png', 'Máy tính bảng nhỏ gọn với sức mạnh vượt trội.', '8.3 inch Liquid Retina', 'Apple A15 Bionic', '12MP', '5,124mAh', 'iPadOS 16', 2, 3, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(126, 'Samsung Galaxy Tab S9 Ultra', 'samsung-galaxy-tab-s9-ultra-126', NULL, 27990000.00, NULL, 'img/galaxy_tab_s9_ultra.png', 'Máy tính bảng Android mạnh mẽ nhất của Samsung.', '14.6 inch Dynamic AMOLED 2X', 'Snapdragon 8 Gen 2', '13MP + 6MP', '11,200mAh', 'Android 13', 1, 3, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(127, 'Samsung Galaxy Tab S9+', 'samsung-galaxy-tab-s9-127', NULL, 22990000.00, NULL, 'img/galaxy_tab_s9_plus.png', 'Phiên bản nhỏ hơn của Tab S9 Ultra.', '12.4 inch Super AMOLED', 'Snapdragon 8 Gen 2', '12MP', '10,090mAh', 'Android 13', 1, 3, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(128, 'Samsung Galaxy Tab A8', 'samsung-galaxy-tab-a8-128', NULL, 5490000.00, NULL, 'img/galaxy_tab_a8.png', 'Máy tính bảng giá rẻ phù hợp cho học tập.', '10.5 inch TFT LCD', 'Unisoc T618', '8MP', '7,040mAh', 'Android 12', 1, 3, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(129, 'Samsung Galaxy Tab S6 Lite (2022)', 'samsung-galaxy-tab-s6-lite-2022-129', NULL, 7990000.00, NULL, 'img/galaxy_tab_s6_lite.png', 'Tablet tầm trung đi kèm bút S Pen.', '10.4 inch TFT LCD', 'Snapdragon 720G', '8MP', '7,040mAh', 'Android 12', 1, 3, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(130, 'Xiaomi Pad 6', 'xiaomi-pad-6-130', NULL, 8990000.00, NULL, 'img/xiaomi_pad_6.png', 'Tablet hiệu năng cao trong phân khúc giá rẻ.', '11 inch IPS LCD', 'Snapdragon 870', '13MP', '8,840mAh', 'Android 13', 4, 3, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(131, 'Xiaomi Pad 6 Pro', 'xiaomi-pad-6-pro-131', NULL, 12990000.00, NULL, 'img/xiaomi_pad_6_pro.png', 'Bản nâng cấp mạnh mẽ với Snapdragon 8+ Gen 1.', '11 inch IPS LCD', 'Snapdragon 8+ Gen 1', '50MP', '10,000mAh', 'Android 13', 4, 3, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(132, 'Xiaomi Redmi Pad', 'xiaomi-redmi-pad-132', NULL, 5990000.00, NULL, 'img/xiaomi_redmi_pad.png', 'Máy tính bảng giá rẻ của Xiaomi.', '10.61 inch IPS LCD', 'MediaTek Helio G99', '8MP', '8,000mAh', 'Android 12', 4, 3, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(133, 'Microsoft Surface Pro 9', 'microsoft-surface-pro-9-133', NULL, 32990000.00, NULL, 'img/surface_pro_9.png', 'Máy tính bảng 2 trong 1 chạy Windows.', '13 inch PixelSense', 'Intel Core i7-1265U', '10MP', '47.7Wh', 'Windows 11', 12, 3, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(134, 'Microsoft Surface Go 3', 'microsoft-surface-go-3-134', NULL, 9990000.00, NULL, 'img/surface_go_3.png', 'Tablet Windows giá rẻ, nhỏ gọn.', '10.5 inch PixelSense', 'Intel Pentium Gold 6500Y', '8MP', '28Wh', 'Windows 11', 12, 3, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(135, 'Microsoft Surface Pro 7+', 'microsoft-surface-pro-7-135', NULL, 24990000.00, NULL, 'img/surface_pro_7_plus.png', 'Phiên bản nâng cấp của Surface Pro 7.', '12.3 inch PixelSense', 'Intel Core i5-1135G7', '8MP', '50.4Wh', 'Windows 11', 12, 3, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(136, 'Lenovo Tab P12 Pro', 'lenovo-tab-p12-pro-136', NULL, 14990000.00, NULL, 'img/lenovo_tab_p12_pro.png', 'Máy tính bảng Lenovo cao cấp.', '12.6 inch AMOLED', 'Snapdragon 870', '13MP + 5MP', '10,200mAh', 'Android 11', 13, 3, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(137, 'Lenovo Tab M10 Plus (3rd Gen)', 'lenovo-tab-m10-plus-3rd-gen-137', NULL, 5290000.00, NULL, 'img/lenovo_tab_m10_plus.png', 'Máy tính bảng giá rẻ cho nhu cầu cơ bản.', '10.61 inch IPS LCD', 'MediaTek Helio G80', '8MP', '7,700mAh', 'Android 12', 13, 3, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(138, 'Lenovo Yoga Tab 13', 'lenovo-yoga-tab-13-138', NULL, 14990000.00, NULL, 'img/lenovo_yoga_tab_13.png', 'Máy tính bảng chuyên cho giải trí và đa nhiệm.', '13 inch IPS LCD', 'Snapdragon 870', '8MP', '10,000mAh', 'Android 11', 13, 3, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(139, 'Huawei MatePad 11', 'huawei-matepad-11-139', NULL, 11990000.00, NULL, 'img/huawei_matepad_11.png', 'Máy tính bảng Huawei với màn hình 120Hz.', '10.95 inch IPS LCD', 'Snapdragon 865', '13MP', '7,250mAh', 'HarmonyOS', 6, 3, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(140, 'Huawei MatePad Pro 13.2', 'huawei-matepad-pro-132-140', NULL, 23990000.00, NULL, 'img/huawei_matepad_pro_13_2.png', 'Phiên bản cao cấp nhất của dòng MatePad.', '13.2 inch OLED', 'Kirin 9000S', '16MP', '10,100mAh', 'HarmonyOS', 6, 3, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(141, 'Huawei MatePad T10s', 'huawei-matepad-t10s-141', NULL, 4990000.00, NULL, 'img/huawei_matepad_t10s.png', 'Tablet giá rẻ với màn hình lớn.', '10.1 inch IPS LCD', 'Kirin 710A', '5MP', '5,100mAh', 'HarmonyOS', 6, 3, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(142, 'iPad Pro M4', 'ipad-pro-m4-142', NULL, 59000000.00, NULL, 'img/67f744e930a39.png', 'Máy tính bảng cao cấp từ Apple với màn hình lớn.', '13 inch', 'Apple M4', '12 MP', '38.99 Wh', 'iPadOS 17', 2, 3, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(143, 'Samsung Galaxy Watch 5 Pro', 'samsung-galaxy-watch-5-pro-143', NULL, 9990000.00, NULL, 'img/watch5pro.jpg', 'Miễn phí bảo hành và dịch vụ', 'Super AMOLED, Always-on', 'Exynos W920', 'Cảm biến BioActive, GPS, NFC', '590mAh, sạc nhanh', 'Wear OS 3.5', 1, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(144, 'Samsung Galaxy Watch 5', 'samsung-galaxy-watch-5-144', NULL, 7990000.00, NULL, 'img/watch5.jpg', 'Miễn phí bảo hành và dịch vụ', 'Super AMOLED, Always-on', 'Exynos W920', 'Cảm biến BioActive, GPS, NFC', '410mAh, sạc nhanh', 'Wear OS 3.5', 1, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(145, 'Samsung Galaxy Watch 4 Classic', 'samsung-galaxy-watch-4-classic-145', NULL, 6990000.00, NULL, 'img/watch4classic.jpg', 'Miễn phí bảo hành và dịch vụ', 'Super AMOLED, Always-on', 'Exynos W920', 'Cảm biến nhịp tim, GPS, NFC', '361mAh, sạc nhanh', 'Wear OS 3.0', 1, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(146, 'Samsung Galaxy Watch 4', 'samsung-galaxy-watch-4-146', NULL, 5990000.00, NULL, 'img/watch4.jpg', 'Miễn phí bảo hành và dịch vụ', 'Super AMOLED, Always-on', 'Exynos W920', 'Cảm biến nhịp tim, GPS, NFC', '247mAh, sạc nhanh', 'Wear OS 3.0', 1, 4, 0, NULL, 0, 1, 1, '2026-02-28 00:05:17', '2026-03-29 07:19:43', NULL),
(147, 'Samsung Galaxy Watch 3', 'samsung-galaxy-watch-3-147', NULL, 4990000.00, NULL, 'img/watch3.jpg', 'Miễn phí bảo hành và dịch vụ', 'Super AMOLED, Always-on', 'Exynos 9110', 'Cảm biến nhịp tim, GPS', '340mAh, sạc nhanh', 'Tizen OS', 1, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(148, 'Samsung Galaxy Watch Active 2', 'samsung-galaxy-watch-active-2-148', NULL, 3990000.00, NULL, 'img/watchactive2.jpg', 'Miễn phí bảo hành và dịch vụ', 'Super AMOLED, Always-on', 'Exynos 9110', 'Cảm biến nhịp tim, GPS', '340mAh, sạc nhanh', 'Tizen OS', 1, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(149, 'Samsung Galaxy Watch Active', 'samsung-galaxy-watch-active-149', NULL, 2990000.00, NULL, 'img/watchactive.jpg', 'Miễn phí bảo hành và dịch vụ', 'Super AMOLED, Always-on', 'Exynos 9110', 'Cảm biến nhịp tim, GPS', '230mAh, sạc nhanh', 'Tizen OS', 1, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(150, 'Samsung Galaxy Fit 2', 'samsung-galaxy-fit-2-150', NULL, 1490000.00, NULL, 'img/galaxyfit2.jpg', 'Miễn phí bảo hành và dịch vụ', 'AMOLED, Always-on', 'Realtek RTK8762C', 'Cảm biến nhịp tim, bước chân', '159mAh, 15 ngày', 'Realtime OS', 1, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(151, 'Samsung Galaxy Watch 6', 'samsung-galaxy-watch-6-151', NULL, 8990000.00, NULL, 'img/watch6.jpg', 'Miễn phí bảo hành và dịch vụ', 'Super AMOLED, Always-on', 'Exynos W930', 'Cảm biến BioActive, GPS, NFC', '425mAh, sạc nhanh', 'Wear OS 4', 1, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(152, 'Samsung Galaxy Watch 6 Classic', 'samsung-galaxy-watch-6-classic-152', NULL, 10990000.00, NULL, 'img/watch6classic.jpg', 'Miễn phí bảo hành và dịch vụ', 'Super AMOLED, Always-on', 'Exynos W930', 'Cảm biến BioActive, GPS, NFC', '470mAh, sạc nhanh', 'Wear OS 4', 1, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(153, 'Apple Watch Series 9', 'apple-watch-series-9-153', NULL, 11990000.00, NULL, 'img/watchseries9.jpg', 'Miễn phí bảo hành và dịch vụ', 'LTPO OLED, Always-on', 'Apple S9', 'Cảm biến nhịp tim, ECG, GPS, NFC', '308mAh, sạc nhanh', 'watchOS 10', 2, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(154, 'Apple Watch Ultra 2', 'apple-watch-ultra-2-154', NULL, 20990000.00, NULL, 'img/watchultra2.jpg', 'Miễn phí bảo hành và dịch vụ', 'LTPO OLED, Always-on', 'Apple S9', 'Cảm biến nhịp tim, ECG, GPS, NFC, độ cao', '542mAh, sạc nhanh', 'watchOS 10', 2, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(155, 'Apple Watch SE (2023)', 'apple-watch-se-2023-155', NULL, 7990000.00, NULL, 'img/watchse2023.jpg', 'Miễn phí bảo hành và dịch vụ', 'LTPO OLED, Always-on', 'Apple S8', 'Cảm biến nhịp tim, GPS, NFC', '296mAh, sạc nhanh', 'watchOS 10', 2, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(156, 'Apple Watch Series 8', 'apple-watch-series-8-156', NULL, 10990000.00, NULL, 'img/watchseries8.jpg', 'Miễn phí bảo hành và dịch vụ', 'LTPO OLED, Always-on', 'Apple S8', 'Cảm biến nhịp tim, ECG, GPS, NFC', '308mAh, sạc nhanh', 'watchOS 9', 2, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(157, 'Apple Watch Ultra', 'apple-watch-ultra-157', NULL, 19990000.00, NULL, 'img/watchultra.jpg', 'Miễn phí bảo hành và dịch vụ', 'LTPO OLED, Always-on', 'Apple S8', 'Cảm biến nhịp tim, ECG, GPS, NFC, độ cao', '542mAh, sạc nhanh', 'watchOS 9', 2, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(158, 'OPPO Watch 4 Pro', 'oppo-watch-4-pro-158', NULL, 9990000.00, NULL, 'img/oppowatch4pro.jpg', 'Miễn phí bảo hành và dịch vụ', 'AMOLED, Always-on', 'Snapdragon W5 Gen 1', 'Cảm biến nhịp tim, GPS, NFC', '570mAh, sạc nhanh', 'ColorOS Watch', 3, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(159, 'Xiaomi Watch S1 Pro', 'xiaomi-watch-s1-pro-159', NULL, 6990000.00, NULL, 'img/xiaomiwatchs1pro.jpg', 'Miễn phí bảo hành và dịch vụ', 'AMOLED, Always-on', 'Snapdragon Wear 4100+', 'Cảm biến nhịp tim, GPS, NFC', '500mAh, sạc nhanh', 'MIUI Watch', 4, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(160, 'Xiaomi Watch S1', 'xiaomi-watch-s1-160', NULL, 5990000.00, NULL, 'img/xiaomiwatchs1.jpg', 'Miễn phí bảo hành và dịch vụ', 'AMOLED, Always-on', 'Snapdragon Wear 4100', 'Cảm biến nhịp tim, GPS, NFC', '470mAh, sạc nhanh', 'MIUI Watch', 4, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(161, 'Xiaomi Mi Band 8', 'xiaomi-mi-band-8-161', NULL, 1590000.00, NULL, 'img/miband8.jpg', 'Miễn phí bảo hành và dịch vụ', 'AMOLED, Always-on', 'Realtek RTL8763E', 'Cảm biến nhịp tim, bước chân', '190mAh, 14 ngày', 'Realtime OS', 4, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL);
INSERT INTO `products` (`id`, `name`, `slug`, `sku`, `price`, `sale_price`, `image`, `description`, `screen`, `chip`, `camera`, `battery`, `os`, `brand_id`, `category_id`, `stock`, `weight`, `is_featured`, `is_active`, `views`, `created_at`, `updated_at`, `deleted_at`) VALUES
(162, 'Xiaomi Mi Band 7', 'xiaomi-mi-band-7-162', NULL, 1290000.00, NULL, 'img/miband7.jpg', 'Miễn phí bảo hành và dịch vụ', 'AMOLED, Always-on', 'Realtek RTL8762C', 'Cảm biến nhịp tim, bước chân', '180mAh, 14 ngày', 'Realtime OS', 4, 4, 5, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-29 03:05:45', NULL),
(163, 'Xiaomi Mi Band 6', 'xiaomi-mi-band-6-163', NULL, 990000.00, NULL, 'img/miband6.jpg', 'Miễn phí bảo hành và dịch vụ', 'AMOLED, Always-on', 'Realtek RTL8762C', 'Cảm biến nhịp tim, bước chân', '125mAh, 12 ngày', 'Realtime OS', 4, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(164, 'Huawei Band 7', 'huawei-band-7-164', NULL, 1990000.00, NULL, 'img/huaweiband7.jpg', 'Miễn phí bảo hành và dịch vụ', 'AMOLED, Always-on', 'Realtek RTL8762C', 'Cảm biến nhịp tim, bước chân', '180mAh, 14 ngày', 'Realtime OS', 6, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(165, 'Huawei Watch Ultimate', 'huawei-watch-ultimate-165', NULL, 12990000.00, NULL, 'img/huaweiwatchultimate.jpg', 'Miễn phí bảo hành và dịch vụ', 'AMOLED, Always-on', 'Kirin A1', 'Cảm biến nhịp tim, SpO2, GPS', '530mAh, sạc nhanh', 'HarmonyOS', 6, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(166, 'Huawei Watch 3 Pro', 'huawei-watch-3-pro-166', NULL, 10990000.00, NULL, 'img/huaweiwatch3pro.jpg', 'Miễn phí bảo hành và dịch vụ', 'AMOLED, Always-on', 'Kirin 990', 'Cảm biến nhịp tim, SpO2, GPS, NFC', '790mAh, sạc nhanh', 'HarmonyOS', 6, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(167, 'Huawei Watch D', 'huawei-watch-d-167', NULL, 9990000.00, NULL, 'img/huaweiwatchd.jpg', 'Miễn phí bảo hành và dịch vụ', 'AMOLED, Always-on', 'Kirin A1', 'Cảm biến huyết áp, nhịp tim, GPS', '400mAh, sạc nhanh', 'HarmonyOS', 6, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(168, 'Huawei Watch GT Runner', 'huawei-watch-gt-runner-168', NULL, 7990000.00, NULL, 'img/huaweiwatchgtrunner.jpg', 'Miễn phí bảo hành và dịch vụ', 'AMOLED, Always-on', 'Kirin A1', 'Cảm biến nhịp tim, GPS, NFC', '455mAh, sạc nhanh', 'HarmonyOS', 6, 4, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-01 01:02:17', NULL),
(169, 'Sony WF-1000XM4', 'sony-wf-1000xm4-169', NULL, 5490000.00, NULL, 'img/tai-nghe-sony.png', 'Chống ồn chủ động. Âm thanh Hi-Res. Pin lên đến 24 giờ.', NULL, NULL, NULL, NULL, NULL, 7, 6, 0, NULL, 0, 1, 1, '2026-02-28 00:05:17', '2026-03-04 00:02:29', NULL),
(170, 'Apple AirPods Pro 2', 'apple-airpods-pro-2-170', NULL, 5990000.00, NULL, 'img/airpods-pro2.png', 'Chống ồn chủ động. Chip H2. Âm thanh không gian.', NULL, NULL, NULL, NULL, NULL, 2, 6, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:25:22', NULL),
(171, 'Samsung Galaxy Buds2 Pro', 'samsung-galaxy-buds2-pro-171', NULL, 3990000.00, NULL, 'img/galaxy-buds2-pro.png', 'Chống ồn chủ động. Âm thanh 24-bit Hi-Fi.', NULL, NULL, NULL, NULL, NULL, 1, 6, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:25:22', NULL),
(172, 'Tai nghe Bluetooth JBL Live Pro+', 'tai-nghe-bluetooth-jbl-live-pro-172', NULL, 3290000.00, NULL, 'img/jbl-live-pro.png', 'Chống ồn chủ động. Công nghệ âm thanh JBL Signature.', NULL, NULL, NULL, NULL, NULL, 14, 6, 0, NULL, 0, 1, 1, '2026-02-28 00:05:17', '2026-03-04 00:02:38', NULL),
(173, 'Anker Liberty Air 2 Pro', 'anker-liberty-air-2-pro-173', NULL, 2890000.00, NULL, 'img/anker-soundcore.png', 'Chống ồn chủ động. 6 mic giảm ồn khi gọi.', NULL, NULL, NULL, NULL, NULL, 15, 6, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:25:22', NULL),
(174, 'Sạc nhanh Anker 20W', 'sac-nhanh-anker-20w-174', NULL, 350000.00, NULL, 'img/sac-anker.png', 'Sạc nhanh PD 20W. Cổng USB-C.', NULL, NULL, NULL, NULL, NULL, 15, 7, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:46:54', NULL),
(175, 'Sạc nhanh Apple 20W USB-C', 'sac-nhanh-apple-20w-usb-c-175', NULL, 590000.00, NULL, 'img/sac-apple.png', 'Sạc nhanh cho iPhone, iPad.', NULL, NULL, NULL, NULL, NULL, 2, 7, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:46:54', NULL),
(176, 'Sạc nhanh Samsung 25W PD', 'sac-nhanh-samsung-25w-pd-176', NULL, 490000.00, NULL, 'img/sac-samsung.png', 'Hỗ trợ sạc nhanh Super Fast Charging.', NULL, NULL, NULL, NULL, NULL, 1, 7, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:46:54', NULL),
(177, 'Sạc nhanh Xiaomi 33W', 'sac-nhanh-xiaomi-33w-177', NULL, 450000.00, NULL, 'img/sac-xiaomi.png', 'Hỗ trợ sạc nhanh QC 3.0 và PD 3.0.', NULL, NULL, NULL, NULL, NULL, 4, 7, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:46:54', NULL),
(178, 'Sạc nhanh RAVPower 30W', 'sac-nhanh-ravpower-30w-178', NULL, 690000.00, NULL, 'img/sac-ravpower.png', 'Sạc nhanh PD 30W. Hỗ trợ MacBook Air.', NULL, NULL, NULL, NULL, NULL, 16, 7, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:46:54', NULL),
(179, 'Chuột Logitech G502 HERO', 'chuot-logitech-g502-hero-179', NULL, 1290000.00, NULL, 'img/chuot-logitech.png', 'Cảm biến HERO 25K. 11 nút lập trình.', NULL, NULL, NULL, NULL, NULL, 17, 8, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:46:54', NULL),
(180, 'Chuột Razer DeathAdder V2', 'chuot-razer-deathadder-v2-180', NULL, 1490000.00, NULL, 'img/chuot-razer.png', 'Cảm biến quang học 20K DPI.', NULL, NULL, NULL, NULL, NULL, 18, 8, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:46:54', NULL),
(181, 'Chuột không dây Logitech MX Master 3', 'chuot-khong-day-logitech-mx-master-3-181', NULL, 2790000.00, NULL, 'img/mx-master3.png', 'Chuột công thái học. Cuộn siêu nhanh.', NULL, NULL, NULL, NULL, NULL, 17, 8, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:46:54', NULL),
(182, 'Chuột gaming Corsair Dark Core RGB Pro', 'chuot-gaming-corsair-dark-core-rgb-pro-182', NULL, 1990000.00, NULL, 'img/corsair-darkcore.png', 'Kết nối không dây tốc độ cao.', NULL, NULL, NULL, NULL, NULL, 19, 8, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:46:54', NULL),
(183, 'Chuột gaming SteelSeries Rival 5', 'chuot-gaming-steelseries-rival-5-183', NULL, 1590000.00, NULL, 'img/steelseries-rival5.png', 'Cảm biến 18K DPI. 9 nút lập trình.', NULL, NULL, NULL, NULL, NULL, 20, 8, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:46:54', NULL),
(184, 'Bàn phím cơ Razer BlackWidow V3', 'ban-phim-co-razer-blackwidow-v3-184', NULL, 2990000.00, NULL, 'img/ban-phim-razer.png', 'Switch xanh Razer. LED RGB.', NULL, NULL, NULL, NULL, NULL, 18, 9, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:46:54', NULL),
(185, 'Bàn phím cơ Logitech G Pro X', 'ban-phim-co-logitech-g-pro-x-185', NULL, 3200000.00, NULL, 'img/logitech-g-prox.png', 'Switch GX có thể thay thế. LED RGB.', NULL, NULL, NULL, NULL, NULL, 17, 9, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:46:54', NULL),
(186, 'Bàn phím cơ Corsair K95 RGB Platinum', 'ban-phim-co-corsair-k95-rgb-platinum-186', NULL, 4490000.00, NULL, 'img/corsair-k95.png', 'Switch Cherry MX. 6 phím macro.', NULL, NULL, NULL, NULL, NULL, 19, 9, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:46:54', NULL),
(187, 'Bàn phím cơ Akko 3068B', 'ban-phim-co-akko-3068b-187', NULL, 2590000.00, NULL, 'img/akko-3068b.png', 'Kết nối Bluetooth/USB. Switch Akko CS.', NULL, NULL, NULL, NULL, NULL, 21, 9, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:46:54', NULL),
(188, 'Bàn phím cơ Keychron K8', 'ban-phim-co-keychron-k8-188', NULL, 2790000.00, NULL, 'img/keychron-k8.png', 'Kết nối không dây. Switch Gateron.', NULL, NULL, NULL, NULL, NULL, 22, 9, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:46:54', NULL),
(189, 'Sạc dự phòng Anker PowerCore 20.000mAh', 'sac-du-phong-anker-powercore-20000mah-189', NULL, 890000.00, NULL, 'img/sacdp-anker.png', 'Dung lượng 20.000mAh. Hỗ trợ sạc nhanh PD.', NULL, NULL, NULL, NULL, NULL, 15, 10, 14, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-24 21:25:09', NULL),
(190, 'Sạc dự phòng Xiaomi Mi 20.000mAh 18W', 'sac-du-phong-xiaomi-mi-20000mah-18w-190', NULL, 750000.00, NULL, 'img/sacdp-xiaomi.png', 'Sạc nhanh 18W. Hai cổng USB-A.', NULL, NULL, NULL, NULL, NULL, 4, 10, 13, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-24 21:26:36', NULL),
(191, 'Sạc dự phòng Samsung 10.000mAh Wireless', 'sac-du-phong-samsung-10000mah-wireless-191', NULL, 990000.00, NULL, 'img/sacdp-samsung.png', 'Sạc không dây Qi. Cổng USB-C PD.', NULL, NULL, NULL, NULL, NULL, 1, 10, 9, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-24 21:26:36', NULL),
(192, 'Sạc dự phòng Energizer 30.000mAh', 'sac-du-phong-energizer-30000mah-192', NULL, 1190000.00, NULL, 'img/sacdp-energizer.png', 'Hỗ trợ sạc nhanh QC 3.0 & PD.', NULL, NULL, NULL, NULL, NULL, 23, 10, 9, NULL, 0, 1, 1, '2026-02-28 00:05:17', '2026-05-19 20:06:45', NULL),
(193, 'Sạc dự phòng Baseus 22.500mAh 65W', 'sac-du-phong-baseus-22500mah-65w-193', NULL, 1390000.00, NULL, 'img/sacdp-baseus.png', 'Sạc laptop, điện thoại. Công suất 65W.', NULL, NULL, NULL, NULL, NULL, 24, 10, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:46:54', NULL),
(194, 'Loa Bluetooth JBL Flip 6', 'loa-bluetooth-jbl-flip-6-194', NULL, 2390000.00, NULL, 'img/loa-jbl.png', 'Chống nước IP67. Pin 12 giờ.', NULL, NULL, NULL, NULL, NULL, 14, 11, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:25:22', NULL),
(195, 'Loa Bluetooth Sony SRS-XB43', 'loa-bluetooth-sony-srs-xb43-195', NULL, 3990000.00, NULL, 'img/loa-sony.png', 'Âm trầm Extra Bass. Chống nước IP67.', NULL, NULL, NULL, NULL, NULL, 7, 11, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:25:22', NULL),
(196, 'Loa Bluetooth Bose SoundLink Revolve+', 'loa-bluetooth-bose-soundlink-revolve-196', NULL, 4990000.00, NULL, 'img/loa-bose.png', '360 độ âm thanh. Pin 16 giờ.', NULL, NULL, NULL, NULL, NULL, 25, 11, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:25:22', NULL),
(197, 'Loa Bluetooth Marshall Emberton', 'loa-bluetooth-marshall-emberton-197', NULL, 3590000.00, NULL, 'img/loa-marshall.png', 'Thiết kế cổ điển. Âm thanh mạnh mẽ.', NULL, NULL, NULL, NULL, NULL, 26, 11, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:25:22', NULL),
(198, 'Loa Bluetooth Anker Soundcore Motion+', 'loa-bluetooth-anker-soundcore-motion-198', NULL, 2290000.00, NULL, 'img/loa-anker.png', 'Chống nước IPX7. Công suất 30W.', NULL, NULL, NULL, NULL, NULL, 15, 11, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:25:22', NULL),
(199, 'Tai Nghe Bluetooth Soundcore Q20i-A3004', 'tai-nghe-bluetooth-soundcore-q20i-a3004-199', NULL, 755000.00, NULL, 'img/anker-soundcore-1.png', 'Khử tiếng ồn chủ động giảm tới 90% tiếng ồn.', NULL, NULL, NULL, NULL, NULL, 15, 6, 0, NULL, 0, 1, 0, '2026-02-28 00:05:17', '2026-03-03 21:25:22', NULL),
(200, 'Tablet', 'tablet', NULL, 5000000.00, 4499999.00, 'img/01KJVSPBB3RQFDYMM56VEBFXPJ.jpg', 'Bảo hành 6 thàng', 'Ultra Retina XDR', 'Apple M5 9 nhân', ' 108MP chính, hỗ trợ quang học', ' 5000mAh, sạc nhanh', 'Android 13', 6, 13, 0, NULL, 0, 1, 16, '2026-03-03 23:48:46', '2026-05-20 02:10:42', NULL),
(201, 'Máy tính bảng Samsung Galaxy Tab A11 4G 4GB/64GB', 'may-tinh-bang-samsung-galaxy-tab-a11-4g-4gb64gb', NULL, 10000000.00, 9000000.00, 'img/01KJVTY6BMK71KMA8WXWAEHGW6.jpg', 'Bảo hành 3 tháng', 'Ultra Retina XDR', 'Apple M5 8 nhân', ' 108MP chính, hỗ trợ quang học', ' 6000mAh, sạc nhanh', 'os 16', 1, 13, 0, NULL, 0, 1, 2, '2026-03-04 00:10:31', '2026-05-20 00:15:49', NULL),
(202, 'Oppo reno', 'oppo-reno', NULL, 23000000.00, 20000000.00, NULL, 'fsadf', 'dfasfd', 'ádf', 'dsfsa', 'dfsad', 'dsafsf', 8, 1, 0, NULL, 0, 1, 0, '2026-03-23 06:26:43', '2026-03-23 06:27:07', '2026-03-23 06:27:07'),
(203, 'test', 'test', NULL, 54325432523.00, 53432.00, NULL, '532', '435', '54323', '452353', '543543', '353', NULL, 4, 0, NULL, 0, 1, 0, '2026-03-23 21:16:07', '2026-03-23 21:16:58', '2026-03-23 21:16:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `is_hidden` tinyint(1) NOT NULL DEFAULT 0,
  `admin_reply` text DEFAULT NULL,
  `helpful_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order_detail_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `rating`, `comment`, `images`, `is_hidden`, `admin_reply`, `helpful_count`, `created_at`, `updated_at`, `order_detail_id`) VALUES
(1, 4, 200, 4, 'Sản phẩm chất lượng, giả hợp lý, ủng hộ shop', '[\"reviews/815j3FAkj3pX5L9KCadhoXYtBQwiYj5m2ETlb2Cs.png\"]', 0, 'Cảm ơn phản hồi của bạn', 0, '2026-03-16 08:56:09', '2026-06-03 02:16:32', NULL),
(2, 4, 2, 5, 'Xịn', NULL, 0, NULL, 0, '2026-03-21 09:04:53', '2026-03-21 09:04:53', NULL),
(3, 2, 21, 5, 'Hay', NULL, 0, NULL, 0, '2026-03-28 02:39:23', '2026-03-30 01:58:03', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super-admin', 'web', '2026-03-20 03:21:30', '2026-03-20 03:21:30'),
(2, 'Marketing creator', 'web', '2026-03-20 03:58:53', '2026-03-20 06:31:09'),
(3, 'Warehouse Staff', 'web', '2026-03-20 06:32:56', '2026-03-20 06:32:56'),
(4, 'Sales Staff', 'web', '2026-03-20 06:34:35', '2026-03-20 06:34:35'),
(5, 'test', 'web', '2026-03-20 20:15:34', '2026-03-20 20:15:34'),
(6, 'staff', 'web', '2026-03-27 09:10:14', '2026-03-27 09:10:14');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(2, 4),
(2, 5),
(2, 6),
(3, 1),
(3, 4),
(4, 1),
(4, 4),
(5, 1),
(5, 3),
(5, 4),
(5, 5),
(5, 6),
(6, 1),
(6, 3),
(6, 5),
(6, 6),
(7, 1),
(7, 4),
(7, 5),
(8, 1),
(9, 1),
(9, 4),
(9, 5),
(9, 6),
(10, 1),
(10, 4),
(11, 1),
(11, 3),
(12, 1),
(12, 2),
(12, 5),
(13, 1),
(13, 2),
(14, 1),
(14, 3),
(15, 1),
(15, 3),
(15, 5),
(16, 1),
(16, 5),
(17, 1),
(17, 2),
(17, 3),
(17, 4),
(17, 5),
(17, 6),
(18, 1),
(18, 2),
(18, 5),
(19, 1),
(20, 1),
(20, 5),
(21, 1),
(21, 5),
(22, 1),
(23, 1),
(23, 5),
(24, 1),
(24, 5),
(25, 1),
(25, 5),
(25, 6),
(26, 1),
(26, 5),
(26, 6),
(27, 1),
(27, 5),
(27, 6),
(28, 1),
(29, 1),
(29, 5),
(29, 6),
(30, 1),
(30, 5),
(30, 6),
(31, 1),
(31, 5),
(31, 6),
(32, 1),
(32, 5),
(33, 1),
(34, 1),
(34, 5),
(35, 1),
(35, 5),
(36, 1),
(36, 5),
(37, 1),
(37, 5),
(38, 1),
(38, 5),
(39, 1),
(39, 5),
(40, 1),
(40, 5),
(41, 1),
(41, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('TtM2IPtjLnOTmyRcdL38Jv5gvPtxZh7WaLgSyO6p', 7, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiOHhWeDBucjdMQUpUeHFRNmlLOG5QbENNSXhuWFZIejlUMXJFYjFXaSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czoxODoiY2hhdGJvdF9zZXNzaW9uX2lkIjtzOjM2OiI2MWZjMTM0OS1kYTkxLTRiZjYtYTYzNi03YTE0MWQxNzRjNTciO3M6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjc7fQ==', 1784000103),
('TXOZt7nZ1SI4anwGKwrqfl5okLOEU8YQ4dLbwBvF', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoibDNtRUxUSmxIajJTSG04Q1JuQXZ2d3hwMW5EZ0lqQnVCUElXZ2lOSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7czo1OiJyb3V0ZSI7czozMDoiZmlsYW1lbnQuYWRtaW4ucGFnZXMuZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6IjAxNGZiMTgwNzY3NGI5ZjhmMGQyMDNjNjcxMzM4ZDI0ZDZhN2EzNGZiN2UwMDc5NjY0YTk4NTQ3YzA5M2NjODkiO30=', 1784000091);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','banned','unverified') NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `full_name`, `phone`, `avatar`, `gender`, `birthday`, `email`, `email_verified_at`, `password`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Lương Quốc Huy', '0867675025', NULL, 'male', '2026-06-23', 'admin@thegioididong.com', '2026-05-19 21:10:45', '$2y$12$ZGAYIRQwLIMI3SzUExYA.O7D6pOthNE2oKpx889dTW6sL8oRHb1K2', 'active', NULL, '2026-03-01 21:30:47', '2026-06-03 02:11:40'),
(2, 'huyluong', 'Lương Quốc Huy', '0867675025', NULL, 'male', '2006-02-07', 'huymonsterhuman@gmail.com', '2026-05-19 21:10:45', '$2y$12$PpdzruSa4iCobthupNnWMeJcPi9vQw1zA9MOvKCDQ0jCqZDCZFdYm', 'active', '4M2leooYgtxVnicM80OO67BvP4RYX4TbgnMeaZ8fEJu5U42rKTrdWRzVSc1b', '2026-03-02 07:02:30', '2026-06-03 02:45:58'),
(4, 'thao2k5', 'Nguyễn Thu Thảo', '0893083042', NULL, 'female', '2005-06-29', 'annguyenhandsome99@gmail.com', NULL, '$2y$12$u36LiznHJMAu2Nx/adhjAef/wUoHFadDP.apAWnfgbl7IEipRD5L6', 'active', NULL, '2026-03-03 23:08:38', '2026-03-29 01:53:30'),
(5, 'Roll', NULL, NULL, NULL, NULL, NULL, 'rolltothedeath@gmail.com', NULL, '$2y$12$foRyNQg..WkOJs9P3vbHcOgeQXDpKb/9pEHouJagmS8wq62KjOH/S', 'active', NULL, '2026-03-20 03:52:37', '2026-03-20 03:52:37'),
(6, 'Huyền Thương', NULL, NULL, NULL, NULL, NULL, 'huyenthuongpec@gmail.com', NULL, '$2y$12$h9884KlsC2qPBIf2s6jvV.qGNe7Exh2NS5NaSgq1oWa/Iad795W6m', 'active', 'KvQcTIsj4QNHyCXpWvE53Qkirhc0Xx2EljSNy3FW2zVcyH9IXubEflds7uk3', '2026-05-19 19:26:22', '2026-05-19 19:30:45'),
(7, 'huynickphu', NULL, NULL, NULL, NULL, NULL, 'huynickphu@gmail.com', '2026-06-03 02:28:29', '$2y$12$Z7A4OPWLT4.GsxNNmj55G.RkKsc6Sz0CdO9NN00gtn.fGq6Li7uNq', 'active', NULL, '2026-06-03 02:24:05', '2026-06-03 02:43:51'),
(8, 'dungdepzaivcl', NULL, NULL, NULL, NULL, NULL, 'dung123@gmail.com', '2026-06-03 02:43:08', '$2y$12$.CPDgorWdcFm55lRf2AZWuiAuhInWJrHib0wOiAKI4YgIe8U.gEHW', 'active', NULL, '2026-06-03 02:42:06', '2026-06-03 02:43:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_voucher`
--

CREATE TABLE `user_voucher` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `voucher_id` bigint(20) UNSIGNED NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user_voucher`
--

INSERT INTO `user_voucher` (`id`, `user_id`, `voucher_id`, `is_used`, `used_at`, `created_at`, `updated_at`, `order_id`) VALUES
(1, 4, 3, 1, NULL, '2026-03-16 06:29:11', '2026-03-16 06:29:49', NULL),
(2, 4, 1, 1, NULL, '2026-03-21 01:32:36', '2026-03-21 07:24:48', NULL),
(3, 6, 4, 1, NULL, '2026-05-19 21:19:42', '2026-05-19 21:20:30', NULL),
(4, 4, 4, 1, '2026-05-19 21:33:55', '2026-05-19 21:32:19', '2026-05-19 21:33:55', 34),
(5, 2, 4, 1, '2026-06-04 02:13:29', '2026-05-20 04:00:39', '2026-06-04 02:13:29', 42);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vouchers`
--

CREATE TABLE `vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` enum('fixed','percent') NOT NULL,
  `discount_amount` decimal(15,2) NOT NULL,
  `min_order_value` decimal(15,2) NOT NULL DEFAULT 0.00,
  `max_discount` decimal(15,2) DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `name`, `type`, `discount_amount`, `min_order_value`, `max_discount`, `expires_at`, `started_at`, `usage_limit`, `used_count`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'HUYST50K', NULL, 'fixed', 1000000.00, 18000000.00, NULL, '2026-03-25 08:18:55', NULL, 1, 1, 1, '2026-03-15 18:27:48', '2026-03-21 07:24:48'),
(3, '60K', NULL, 'percent', 10.00, 5000000.00, NULL, '2026-03-20 08:58:18', NULL, 5, 2, 1, '2026-03-15 18:58:35', '2026-03-16 06:29:49'),
(4, 'SUMMERTIME', NULL, 'percent', 10.00, 20000000.00, 5000000.00, '2026-06-18 11:19:10', NULL, NULL, 3, 1, '2026-05-19 21:19:20', '2026-06-04 02:13:29');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`),
  ADD KEY `activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`);

--
-- Chỉ mục cho bảng `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `banners_author_id_foreign` (`author_id`);

--
-- Chỉ mục cho bảng `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brands_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Chỉ mục cho bảng `chatbot_messages`
--
ALTER TABLE `chatbot_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chatbot_messages_session_id_created_at_index` (`session_id`,`created_at`),
  ADD KEY `chatbot_messages_created_at_index` (`created_at`),
  ADD KEY `chatbot_messages_role_index` (`role`);

--
-- Chỉ mục cho bảng `chatbot_sessions`
--
ALTER TABLE `chatbot_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chatbot_sessions_session_token_unique` (`session_token`),
  ADD KEY `chatbot_sessions_user_id_foreign` (`user_id`),
  ADD KEY `chatbot_sessions_created_at_index` (`created_at`);

--
-- Chỉ mục cho bảng `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `collections_slug_unique` (`slug`),
  ADD KEY `collections_parent_id_foreign` (`parent_id`);

--
-- Chỉ mục cho bảng `collection_product`
--
ALTER TABLE `collection_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `collection_product_collection_id_foreign` (`collection_id`),
  ADD KEY `collection_product_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `goods_issues`
--
ALTER TABLE `goods_issues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_issues_order_id_foreign` (`order_id`),
  ADD KEY `goods_issues_author_id_foreign` (`author_id`);

--
-- Chỉ mục cho bảng `goods_issue_details`
--
ALTER TABLE `goods_issue_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_issue_details_goods_issue_id_foreign` (`goods_issue_id`),
  ADD KEY `goods_issue_details_product_id_foreign` (`product_id`),
  ADD KEY `goods_issue_details_goods_receipt_detail_id_foreign` (`goods_receipt_detail_id`);

--
-- Chỉ mục cho bảng `goods_receipts`
--
ALTER TABLE `goods_receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_receipts_user_id_foreign` (`user_id`),
  ADD KEY `goods_receipts_supplier_id_foreign` (`supplier_id`);

--
-- Chỉ mục cho bảng `goods_receipt_details`
--
ALTER TABLE `goods_receipt_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_receipt_details_goods_receipt_id_foreign` (`goods_receipt_id`),
  ADD KEY `goods_receipt_details_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Chỉ mục cho bảng `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_voucher_id_foreign` (`voucher_id`),
  ADD KEY `orders_partner_id_foreign` (`partner_id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_order_id_foreign` (`order_id`),
  ADD KEY `order_details_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Chỉ mục cho bảng `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `posts_slug_unique` (`slug`),
  ADD KEY `posts_post_category_id_foreign` (`post_category_id`),
  ADD KEY `posts_author_id_foreign` (`author_id`);

--
-- Chỉ mục cho bảng `post_categories`
--
ALTER TABLE `post_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_categories_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_brand_id_foreign` (`brand_id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Chỉ mục cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviews_user_id_product_id_unique` (`user_id`,`product_id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`),
  ADD KEY `reviews_order_detail_id_foreign` (`order_detail_id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Chỉ mục cho bảng `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Chỉ mục cho bảng `user_voucher`
--
ALTER TABLE `user_voucher`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_voucher_user_id_voucher_id_unique` (`user_id`,`voucher_id`),
  ADD KEY `user_voucher_voucher_id_foreign` (`voucher_id`),
  ADD KEY `user_voucher_order_id_foreign` (`order_id`);

--
-- Chỉ mục cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vouchers_code_unique` (`code`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=264;

--
-- AUTO_INCREMENT cho bảng `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `chatbot_messages`
--
ALTER TABLE `chatbot_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `chatbot_sessions`
--
ALTER TABLE `chatbot_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `collections`
--
ALTER TABLE `collections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `collection_product`
--
ALTER TABLE `collection_product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=205;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `goods_issues`
--
ALTER TABLE `goods_issues`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT cho bảng `goods_issue_details`
--
ALTER TABLE `goods_issue_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT cho bảng `goods_receipts`
--
ALTER TABLE `goods_receipts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `goods_receipt_details`
--
ALTER TABLE `goods_receipt_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT cho bảng `partners`
--
ALTER TABLE `partners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `post_categories`
--
ALTER TABLE `post_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;

--
-- AUTO_INCREMENT cho bảng `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `user_voucher`
--
ALTER TABLE `user_voucher`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `banners`
--
ALTER TABLE `banners`
  ADD CONSTRAINT `banners_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `chatbot_messages`
--
ALTER TABLE `chatbot_messages`
  ADD CONSTRAINT `chatbot_messages_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `chatbot_sessions` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chatbot_sessions`
--
ALTER TABLE `chatbot_sessions`
  ADD CONSTRAINT `chatbot_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `collections`
--
ALTER TABLE `collections`
  ADD CONSTRAINT `collections_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `collections` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `collection_product`
--
ALTER TABLE `collection_product`
  ADD CONSTRAINT `collection_product_collection_id_foreign` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `collection_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `goods_issues`
--
ALTER TABLE `goods_issues`
  ADD CONSTRAINT `goods_issues_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `goods_issues_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `goods_issue_details`
--
ALTER TABLE `goods_issue_details`
  ADD CONSTRAINT `goods_issue_details_goods_issue_id_foreign` FOREIGN KEY (`goods_issue_id`) REFERENCES `goods_issues` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_issue_details_goods_receipt_detail_id_foreign` FOREIGN KEY (`goods_receipt_detail_id`) REFERENCES `goods_receipt_details` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `goods_issue_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `goods_receipts`
--
ALTER TABLE `goods_receipts`
  ADD CONSTRAINT `goods_receipts_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `partners` (`id`),
  ADD CONSTRAINT `goods_receipts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `goods_receipt_details`
--
ALTER TABLE `goods_receipt_details`
  ADD CONSTRAINT `goods_receipt_details_goods_receipt_id_foreign` FOREIGN KEY (`goods_receipt_id`) REFERENCES `goods_receipts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_receipt_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `posts_post_category_id_foreign` FOREIGN KEY (`post_category_id`) REFERENCES `post_categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_order_detail_id_foreign` FOREIGN KEY (`order_detail_id`) REFERENCES `order_details` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `user_voucher`
--
ALTER TABLE `user_voucher`
  ADD CONSTRAINT `user_voucher_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `user_voucher_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_voucher_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
