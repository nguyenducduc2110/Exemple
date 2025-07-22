<?php
session_start();
require 'config.php';

// Kiểm tra quyền truy cập
if (!isset($_SESSION['user_id']) || $_SESSION['quyen'] !== 'Quản trị') {
    header("Location: login.php");
    exit();
}

// Xử lý thêm, sửa, xóa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $ten_nguoi_dung = $_POST['ten_nguoi_dung'];
        $ten_dang_nhap = $_POST['ten_dang_nhap'];
        $mat_khau = $_POST['mat_khau'];
        $quyen = $_POST['quyen'];
        $stmt = $conn->prepare("INSERT INTO users (ten_nguoi_dung, ten_dang_nhap, mat_khau, quyen) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $ten_nguoi_dung, $ten_dang_nhap, $mat_khau, $quyen);
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['user_id'];
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } elseif (isset($_POST['update'])) {
        $id = $_POST['user_id'];
        $ten_nguoi_dung = $_POST['ten_nguoi_dung'];
        $ten_dang_nhap = $_POST['ten_dang_nhap'];
        $mat_khau = $_POST['mat_khau'];
        $quyen = $_POST['quyen'];
        $stmt = $conn->prepare("UPDATE users SET ten_nguoi_dung=?, ten_dang_nhap=?, mat_khau=?, quyen=? WHERE id=?");
        $stmt->bind_param("ssssi", $ten_nguoi_dung, $ten_dang_nhap, $mat_khau, $quyen, $id);
        $stmt->execute();
    }
}

$users = $conn->query("SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookbuy.vn - Quản trị</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        
        a {
            text-decoration: none;
            color: inherit;
        }
        
        /* Header styles */
        .header {
            background-color: white;
        }
        
        .top-bar {
            background-color: #f8f8f8;
            padding: 8px 20px;
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .top-bar-left a, .top-bar-right a {
            margin: 0 8px;
            color: #666;
        }
        
        .main-header {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .logo {
            display: flex;
            align-items: center;
        }
        
        .logo img {
            height: 40px;
            width: auto;
        }
        
        .search-container {
            flex: 1;
            max-width: 500px;
            margin: 0 30px;
            display: flex;
        }
        
        .search-container input {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #ddd;
            font-size: 14px;
        }
        
        .search-container button {
            padding: 8px 15px;
            border: 1px solid #ff6633;
            background-color: #ff6633;
            color: white;
            cursor: pointer;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .header-actions a {
            color: #333;
            font-size: 14px;
        }
        
        /* Navigation */
        .nav-bar {
            background-color: white;
            padding: 0 20px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .nav-bar ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
            justify-content: space-around;
        }
        
        .nav-bar li {
            flex: 1;
            text-align: center;
        }
        
        .nav-bar a {
            color: #333;
            padding: 15px 10px;
            display: block;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s;
            border-right: 1px solid #e0e0e0;
        }
        
        .nav-bar li:last-child a {
            border-right: none;
        }
        
        .nav-bar a:hover {
            color: #ff6633;
            background-color: #f8f8f8;
        }
        
        /* Main content */
        .main-container {
            max-width: 1200px;
            margin: 15px auto;
            padding: 0 15px;
            display: flex;
            gap: 20px;
        }
        
        /* Sidebar */
        .sidebar {
            width: 220px;
        }
        
        .category-box {
            background: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .category-header {
            background: linear-gradient(135deg, #8A2BE2 0%, #6A5ACD 100%);
            color: white;
            padding: 12px 15px;
            font-weight: bold;
            font-size: 14px;
        }
        
        .category-list {
            padding: 0;
        }
        
        .category-list ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .category-list li {
            border-bottom: 1px solid #f0f0f0;
        }
        
        .category-list a {
            display: block;
            padding: 10px 15px;
            color: #666;
            font-size: 13px;
            transition: color 0.3s;
        }
        
        .category-list a:hover {
            color: #ff6633;
            background-color: #f8f8f8;
        }
        
        /* Main content area */
        .content-area {
            flex: 1;
        }
        
        /* Banner */
        .banner {
            margin-bottom: 25px;
        }
        
        .banner img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        /* Admin sections */
        .admin-section {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }
        
        /* Form styles */
        .add-user-form {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .add-user-form input, .add-user-form select {
            padding: 8px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        
        .add-user-form button {
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 3px;
            cursor: pointer;
        }
        
        .add-user-form button:hover {
            background: #218838;
        }
        
        /* Table styles */
        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .users-table th, .users-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        
        .users-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .users-table input, .users-table select {
            width: 100%;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        
        .btn-update {
            background: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            margin: 2px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            margin: 2px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .btn-update:hover {
            background: #0056b3;
        }
        
        .btn-delete:hover {
            background: #c82333;
        }
        
        /* News section */
        .news-section {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .news-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        
        .news-item {
            text-align: center;
        }
        
        .news-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        
        .news-title {
            font-size: 13px;
            color: #333;
            margin-bottom: 5px;
            line-height: 1.3;
        }
        
        .news-date {
            color: #999;
            font-size: 12px;
        }
        
        /* Footer */
        .footer {
            background: white;
            color: #333;
            padding: 30px 20px 15px;
            margin-top: 40px;
            border-top: 1px solid #e0e0e0;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            margin-bottom: 20px;
        }
        
        .footer-section h4 {
            color: #ff6633;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .footer-section p, .footer-section ul li {
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 5px;
            color: #666;
        }
        
        .footer-section ul {
            list-style: none;
        }
        
        .footer-section ul li a {
            color: #666;
            transition: color 0.3s;
        }
        
        .footer-section ul li a:hover {
            color: #ff6633;
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <!-- Top bar -->
        <div class="top-bar">
            <div class="top-bar-left">
                <a href="#"><i class="far fa-bell"></i> Thông báo</a>
                <a href="#"><i class="far fa-question-circle"></i> Trợ giúp</a>
            </div>
            <div class="top-bar-right">
                <span>Xin chào, <?php echo $_SESSION['ten_nguoi_dung']; ?></span>
                |
                <a href="logout.php">Đăng xuất</a>
            </div>
        </div>
        
        <!-- Main header -->
        <div class="main-header">
            <div class="logo">
                <img src="images/logo.png" alt="Bookbuy.vn">
            </div>
            <div class="search-container">
                <input type="text" placeholder="Tìm kiếm...">
                <button type="submit"><i class="fas fa-search"></i></button>
            </div>
            <div class="header-actions">
                <a href="#" class="cart-icon">
                    <i class="fas fa-shopping-cart"></i> Giỏ hàng
                </a>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="nav-bar">
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="#">Giới thiệu</a></li>
                <li><a href="#">Sản phẩm</a></li>
                <li><a href="#">Đăng ký đại lý</a></li>
                <li><a href="#">Khuyến mại</a></li>
                <li><a href="#">Kiểm tra đơn hàng</a></li>
                <li><a href="#">Tuyển dụng</a></li>
                <li><a href="#">Liên hệ</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main content -->
    <div class="main-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="category-box">
                <div class="category-header">DANH MỤC SÁCH</div>
                <div class="category-list">
                    <ul>
                        <li><a href="#">Sách Thiếu nhi</a></li>
                        <li><a href="#">Sách Kinh tế - Khởi nghiệp</a></li>
                        <li><a href="#">Sách Kỹ năng sống</a></li>
                        <li><a href="#">Sách Ngoại ngữ</a></li>
                        <li><a href="#">Sách Nuôi dạy con</a></li>
                        <li><a href="#">Sách Dinh dưỡng</a></li>
                        <li><a href="#">Sách Ẩm thực</a></li>
                        <li><a href="#">Sách Làm đẹp</a></li>
                    </ul>
                </div>
            </div>
        </aside>

        <!-- Main content area -->
        <main class="content-area">
            <!-- Banner -->
            <div class="banner">
                <img src="images/banner.jpg" alt="Khuyến mại">
            </div>

            <!-- Admin User Management -->
            <section class="admin-section">
                <h2 class="section-title">Danh sách người dùng</h2>
                
                <!-- Add new user button -->
                <div style="text-align: right; margin-bottom: 15px;">
                    <button class="add-user-form button" onclick="toggleAddForm()" style="background: #28a745; color: white; border: none; padding: 8px 15px; border-radius: 3px; cursor: pointer;">
                        <i class="fas fa-plus"></i> Thêm mới
                    </button>
                </div>

                <!-- Add user form (hidden by default) -->
                <div id="addUserForm" class="add-user-form" style="display: none;">
                    <h3>Thêm người dùng mới</h3>
                    <form method="post">
                        <input type="text" name="ten_nguoi_dung" placeholder="Tên người dùng" required>
                        <input type="text" name="ten_dang_nhap" placeholder="Tên đăng nhập" required>
                        <input type="password" name="mat_khau" placeholder="Mật khẩu" required>
                        <select name="quyen">
                            <option value="Khách hàng">Khách hàng</option>
                            <option value="Quản trị">Quản trị</option>
                        </select>
                        <button name="add">Thêm người dùng</button>
                        <button type="button" onclick="toggleAddForm()">Hủy</button>
                    </form>
                </div>

                <!-- Users table -->
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên Người dùng</th>
                            <th>Tên đăng nhập</th>
                            <th>Mật khẩu</th>
                            <th>Quyền</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $counter = 1; while ($row = $users->fetch_assoc()): ?>
                        <tr>
                            <form method="post">
                                <td><?php echo $counter++; ?><input type="hidden" name="user_id" value="<?php echo $row['id']; ?>"></td>
                                <td><input type="text" name="ten_nguoi_dung" value="<?php echo htmlspecialchars($row['ten_nguoi_dung']); ?>"></td>
                                <td><input type="text" name="ten_dang_nhap" value="<?php echo htmlspecialchars($row['ten_dang_nhap']); ?>"></td>
                                <td><input type="text" name="mat_khau" value="<?php echo htmlspecialchars($row['mat_khau']); ?>"></td>
                                <td>
                                    <select name="quyen">
                                        <option value="Khách hàng" <?php if ($row['quyen'] === 'Khách hàng') echo 'selected'; ?>>Khách hàng</option>
                                        <option value="Quản trị" <?php if ($row['quyen'] === 'Quản trị') echo 'selected'; ?>>Quản trị</option>
                                    </select>
                                </td>
                                <td>
                                    <button name="update" class="btn-update">Sửa</button>
                                    <button name="delete" class="btn-delete" onclick="return confirm('Xác nhận xóa?')">Xóa</button>
                                </td>
                            </form>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>

            <!-- Tin tức & Sự kiện -->
            <section class="news-section">
                <h2 class="section-title">Tin tức & Sự kiện</h2>
                <div class="news-grid">
                    <div class="news-item">
                        <img src="images/new1.png" alt="Tin tức 1">
                        <div class="news-title">Sẵn sàng tri thức háo hức tựu trường</div>
                        <div class="news-date">15/08/2023</div>
                    </div>
                    <div class="news-item">
                        <img src="images/new2.png" alt="Tin tức 2">
                        <div class="news-title">Trở lại trường học cùng FAHASA</div>
                        <div class="news-date">16/08/2023</div>
                    </div>
                    <div class="news-item">
                        <img src="images/new3.png" alt="Tin tức 3">
                        <div class="news-title">Cặp sách mới - Kiến thức mới</div>
                        <div class="news-date">15/08/2023</div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>Thông tin liên hệ</h4>
                <p>Công Ty Cổ Phần Phát Hành Sách BUYBOOK</p>
                <p>Lầu 5, 387-389 Hai Bà Trưng Quận 3 TP HCM</p>
                <p>ĐKKD số 0110016683 do Sở kế hoạch và Đầu tư Tp Hà Nội cấp ngày 1/6/2022</p>
                <p>Địa chỉ: 62 Lê Lợi, Quận 1, TP. HCM, Việt Nam</p>
            </div>
            <div class="footer-section">
                <h4>Dịch vụ</h4>
                <ul>
                    <li><a href="#">Điều khoản sử dụng</a></li>
                    <li><a href="#">Chính sách bảo mật</a></li>
                    <li><a href="#">Chính sách đổi trả</a></li>
                    <li><a href="#">Giới thiệu</a></li>
                    <li><a href="#">Hỗ trợ khách hàng</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Hỗ trợ khách hàng</h4>
                <ul>
                    <li><a href="#">Chính sách đổi trả - hoàn tiền</a></li>
                    <li><a href="#">Chính sách bảo hành - bồi hoàn</a></li>
                    <li><a href="#">Giải đáp & xử lý khiếu nại</a></li>
                    <li><a href="#">Quy định Buybook</a></li>
                    <li><a href="#">Phương thức thanh toán và xuất hóa đơn</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2023 Bookbuy.vn. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function toggleAddForm() {
            const form = document.getElementById('addUserForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
    </script>
</body>
</html>