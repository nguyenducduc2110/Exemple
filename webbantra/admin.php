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
    <title>Trà Thái Nguyên - Quản trị</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* General styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5; /* Light grey background */
            color: #333;
            line-height: 1.6;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Top Bar */
        .top-bar {
            background: white;
            border-bottom: 1px solid #e0e0e0;
            color: #666;
            padding: 8px 0;
            font-size: 13px;
        }

        .top-bar-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 15px;
        }

        .top-bar-left a,
        .top-bar-right a {
            color: #666;
            margin: 0 8px;
            transition: color 0.3s;
        }

        .top-bar-left a:hover,
        .top-bar-right a:hover {
            color: #4CAF50;
        }

        .top-bar-right span {
            margin: 0 5px;
        }

        /* Header */
        .header {
            background: white;
            padding: 15px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 15px;
        }

        .logo img {
            height: 50px; /* Adjust based on actual logo size */
        }

        .search-container {
            flex-grow: 1;
            max-width: 500px;
            margin: 0 20px;
            position: relative;
        }

        .search-container input {
            width: 100%;
            border: 1px solid #ddd;
            padding: 10px 40px 10px 15px;
            outline: none;
            font-size: 14px;
            border-radius: 20px; /* More rounded */
            background-color: #f8f8f8;
        }

        .search-container button {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: #4CAF50;
            border: none;
            color: white;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 15px; /* More rounded */
            transition: background 0.3s;
        }

        .search-container button:hover {
            background: #2E7D32;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-actions a {
            color: #4CAF50;
            font-size: 14px;
            font-weight: bold;
        }

        .header-actions .cart-icon {
            font-size: 20px;
            position: relative;
        }

        /* Navigation */
        .nav-bar {
            background: #4CAF50; /* Green */
            padding: 0;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: flex-start; /* Align to start */
            padding: 0 15px;
        }

        .nav-bar a {
            color: white;
            padding: 15px 20px;
            display: block;
            font-weight: 500;
            transition: background 0.3s;
            font-size: 14px;
        }

        .nav-bar a:hover,
        .nav-bar .active {
            background: #2E7D32; /* Darker green */
        }

        /* Banner Section - Full width */
        .banner-section {
            width: 100%;
            height: 350px; /* Adjust height as needed */
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px; /* Space between banner and main content */
            background-color: #f0f2f5; /* Fallback background */
        }

        .banner-section img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Ensures the image covers the area, cropping if necessary */
            object-position: center top; /* Focus on the top part of the image */
            display: block; /* Remove extra space below image */
        }

        /* Main Content Grid - Now a single column */
        .main-container {
            max-width: 1200px;
            margin: 0 auto; /* Remove top margin, keep auto for horizontal centering */
            display: grid;
            grid-template-columns: 1fr; /* Single column */
            gap: 25px; /* Space between sections */
            padding: 0 15px; /* Inner padding */
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
            font-size: 22px;
            color: #333;
            margin-bottom: 25px;
            font-weight: bold;
            text-align: center; /* Center section titles */
            position: relative;
            padding-bottom: 10px; /* Space for underline */
        }

        .section-title::after {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: 0;
            width: 50px;
            height: 3px;
            background-color: #4CAF50;
        }
        
        /* Form styles */
        .add-user-form {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #eee;
        }
        
        .add-user-form h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }

        .add-user-form form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }
        
        .add-user-form input, 
        .add-user-form select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            flex: 1; /* Allow inputs to grow */
            min-width: 150px; /* Minimum width for responsiveness */
        }
        
        .add-user-form button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }
        
        .add-user-form button:hover {
            background: #2E7D32;
        }
        
        /* Table styles */
        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .users-table th, .users-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }
        
        .users-table th {
            background-color: #f8f8f8;
            font-weight: bold;
            color: #555;
        }
        
        .users-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .users-table input, .users-table select {
            width: 100%;
            padding: 6px;
            border: 1px solid #eee;
            border-radius: 3px;
            font-size: 13px;
        }
        
        .users-table td:last-child {
            text-align: center;
            white-space: nowrap; /* Prevent buttons from wrapping */
        }

        .btn-update {
            background: #007bff;
            color: white;
            border: none;
            padding: 6px 10px;
            margin: 0 2px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            transition: background 0.3s;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
            border: none;
            padding: 6px 10px;
            margin: 0 2px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            transition: background 0.3s;
        }
        
        .btn-update:hover {
            background: #0056b3;
        }
        
        .btn-delete:hover {
            background: #c82333;
        }
        
        /* News section (main content) */
        .articles-section { /* Renamed to distinguish from sidebar news */
            background: white;
            border-radius: 8px;
            padding: 25px;
            margin-top: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }
        
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .article-item {
            text-align: center;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            transition: box-shadow 0.3s, transform 0.2s;
        }

        .article-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-3px);
        }
        
        .article-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }
        
        .article-content {
            padding: 15px;
        }

        .article-title {
            font-size: 16px;
            color: #333;
            margin-bottom: 8px;
            line-height: 1.4;
            font-weight: bold;
        }
        
        .article-date {
            color: #777;
            font-size: 13px;
        }
        
        /* Footer */
        .footer {
            background: #f8f8f8;
            color: #666;
            margin-top: 50px;
            padding: 40px 0 20px;
            border-top: 1px solid #e0e0e0;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            padding: 0 15px;
        }

        .footer-section h4 {
            color: #333;
            font-size: 16px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section li {
            margin-bottom: 8px;
        }

        .footer-section a {
            color: #666;
            font-size: 14px;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: #4CAF50;
        }

        .footer-section p {
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 5px;
            color: #666;
        }

        .footer-bottom {
            border-top: 1px solid #ddd;
            margin-top: 30px;
            padding-top: 20px;
            text-align: center;
            color: #666;
            font-size: 13px;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .articles-grid {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            }
            .nav-bar a {
                padding: 12px 15px;
            }
        }

        @media (max-width: 768px) {
            .header-container {
                flex-wrap: wrap; /* Allow wrapping */
                justify-content: center;
                gap: 15px;
            }
            .search-container {
                order: 3; /* Move search below logo/actions */
                flex-basis: 100%; /* Full width */
                margin: 0;
            }
            .header-actions {
                order: 2;
            }
            .logo {
                order: 1;
            }

            .main-container {
                padding: 0 20px; /* Add horizontal padding */
            }
            .articles-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Larger items when single column */
            }
            .footer-container {
                grid-template-columns: 1fr; /* Stack footer sections */
            }
            .nav-container {
                flex-wrap: wrap;
                justify-content: center;
            }

            .add-user-form form {
                flex-direction: column;
                align-items: stretch;
            }

            .add-user-form input,
            .add-user-form select,
            .add-user-form button {
                width: 100%;
                min-width: unset;
            }
        }

        @media (max-width: 480px) {
            .top-bar-container {
                flex-direction: column;
                gap: 5px;
            }
            .top-bar-left, .top-bar-right {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }
            .nav-bar a {
                padding: 10px;
                font-size: 13px;
            }
            .articles-grid {
                grid-template-columns: 1fr; /* Single item per row */
            }
             .users-table th, .users-table td {
                padding: 6px; /* Smaller padding for tables on small screens */
                font-size: 12px;
            }
            .btn-update, .btn-delete {
                padding: 4px 6px;
                font-size: 11px;
            }
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <div class="top-bar-container">
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
    </div>

    <header class="header">
        <div class="header-container">
            <div class="logo">
                <a href="index.php">
                    <img src="images/logo.png" alt="Tân Cương Tea Logo">
                </a>
            </div>
            <div class="search-container">
                <input type="text" placeholder="Tìm kiếm">
                <button type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <div class="header-actions">
                <a href="#" class="cart-icon">
                    <i class="fas fa-shopping-cart"></i> Giỏ hàng
                </a>
            </div>
        </div>
    </header>

    <nav class="nav-bar">
        <div class="nav-container">
            <a href="index.php">Trang chủ</a>
            <a href="#">Giới thiệu</a>
            <a href="#">Sản phẩm</a>
            <a href="#">Mua hàng</a>
            <a href="#">Tin tức & Sự kiện</a>
            <a href="#">Tuyển dụng</a>
            <a href="#">Liên hệ</a>
        </div>
    </nav>

    <section class="banner-section">
        <img src="images/banner.jpg" alt="Banner Trà Thái Nguyên">
    </section>

    <div class="main-container">
        <main class="content-area">
            <section class="admin-section">
                <h2 class="section-title">Danh sách người dùng</h2>
                
                <div style="text-align: right; margin-bottom: 15px;">
                    <button class="add-user-form button" onclick="toggleAddForm()">
                        <i class="fas fa-plus"></i> Thêm mới
                    </button>
                </div>

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

                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên người dùng</th>
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

            <section class="articles-section">
                <h2 class="section-title">Bài viết nổi bật</h2>
                <div class="articles-grid">
                    <div class="article-item">
                        <img src="https://via.placeholder.com/300x150/e8f5e8/4CAF50?text=Noi+mua+tra" alt="Nơi mua trà">
                        <div class="article-content">
                            <h3 class="article-title">Nơi Mua Trà Thái Nguyên Tại TPHCM Chính Gốc 100%</h3>
                        </div>
                    </div>
                    <div class="article-item">
                        <img src="https://via.placeholder.com/300x150/e8f5e8/4CAF50?text=Cay+che" alt="Cây chè">
                        <div class="article-content">
                            <h3 class="article-title">Cây chè Thái Nguyên: Lịch sử, cách trồng & tác dụng</h3>
                        </div>
                    </div>
                    <div class="article-item">
                        <img src="https://via.placeholder.com/300x150/e8f5e8/4CAF50?text=Tho+che" alt="Thơ chè">
                        <div class="article-content">
                            <h3 class="article-title">TOP Những Bài Thơ Về Chè Thái Nguyên Hay Nhất</h3>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h4>VỀ CÔNG TY</h4>
                <ul>
                    <li><a href="#">Giới thiệu</a></li>
                    <li><a href="#">Lịch làm việc</a></li>
                    <li><a href="#">Dịch vụ</a></li>
                    <li><a href="#">Sơ đồ chỉ đường</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>DÀNH CHO NGƯỜI MUA HÀNG</h4>
                <ul>
                    <li><a href="#">Bảo mật thông tin</a></li>
                    <li><a href="#">Chính sách bảo hành</a></li>
                    <li><a href="#">Phương thức thanh toán</a></li>
                    <li><a href="#">Phương thức vận chuyển</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>ĐỊA CHỈ MUA HÀNG</h4>
                <p>CÔNG TY TNHH THƯƠNG MẠI LONG AN</p>
                <p>Số ĐKKD: 010233125</p>
                <p>Điện thoại: 0972.922.120</p>
                <p>Website: tra.ninhbinhsite.com</p>
                <p>Email: info.traxanh.com@gmail.com</p>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; 2025 Công ty TNHH Thương Mại Long An. All rights reserved.
        </div>
    </footer>

    <script>
        function toggleAddForm() {
            const form = document.getElementById('addUserForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'flex'; // Changed to flex to use gap and wrap
                form.style.flexDirection = 'column'; // Ensure vertical stacking on small screens
            } else {
                form.style.display = 'none';
            }
        }
    </script>
</body>
</html>