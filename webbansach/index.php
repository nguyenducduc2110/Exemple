<?php
session_start();
// Nếu người dùng đã đăng nhập và có quyền quản trị, chuyển hướng đến admin.php
if (isset($_SESSION['user_id']) && $_SESSION['quyen'] === 'Quản trị') {
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookbuy.vn</title>
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
        
        /* Navigation - Updated to white background with black text */
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
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        /* Product sections */
        .product-section {
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
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        
        .product-item {
            text-align: center;
        }
        
        .product-item img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border: 1px solid #eee;
            margin-bottom: 10px;
        }
        
        .product-title {
            font-size: 13px;
            color: #333;
            margin-bottom: 8px;
            height: 32px;
            overflow: hidden;
            line-height: 1.3;
        }
        
        .product-price {
            color: #ff6633;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .buy-button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 6px 20px;
            border-radius: 15px;
            cursor: pointer;
            font-size: 12px;
            transition: background 0.3s;
        }
        
        .buy-button:hover {
            background: #45a049;
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
        
        /* Footer - Updated to white background */
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
        
        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
            
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .news-grid {
                grid-template-columns: 1fr;
            }
            
            .main-header {
                flex-direction: column;
                gap: 10px;
            }
            
            .search-container {
                margin: 0;
                max-width: 100%;
            }
            
            .nav-bar ul {
                flex-direction: column;
            }
            
            .nav-bar li {
                text-align: left;
            }
            
            .nav-bar a {
                border-right: none;
                border-bottom: 1px solid #e0e0e0;
            }
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
                <a href="register.php">Đăng ký</a>
                |
                <a href="login.php">Đăng nhập</a>
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

            <!-- Sách Kinh tế - Khởi nghiệp -->
            <section class="product-section">
                <h2 class="section-title">Sách Kinh tế - Khởi nghiệp</h2>
                <div class="product-grid">
                    <div class="product-item">
                        <img src="images/book1.jpg" alt="Cơn Lốc Quản Trị">
                        <div class="product-title">Cơn Lốc Quản Trị - Bà Tư Cốt Của Vạn Hóa Doanh Nghiệp</div>
                        <div class="product-price">140.000đ</div>
                        <button class="buy-button">Mua hàng</button>
                    </div>
                    <div class="product-item">
                        <img src="images/book2.jpg" alt="Thiên Tài Tập Thể">
                        <div class="product-title">Thiên Tài Tập Thể - Lãnh Đạo Khác Biệt Để Đối Mới Và Bứt Phá</div>
                        <div class="product-price">198.000đ</div>
                        <button class="buy-button">Mua hàng</button>
                    </div>
                    <div class="product-item">
                        <img src="images/book3.jpg" alt="Thuật Dùng Người">
                        <div class="product-title">Thuật Dùng Người - Bí Quyết Để Trở Thành Nhà Quản Trị Tài Ba</div>
                        <div class="product-price">200.000đ</div>
                        <button class="buy-button">Mua hàng</button>
                    </div>
                    <div class="product-item">
                        <img src="images/book4.jpg" alt="Metaverse">
                        <div class="product-title">Metaverse - Cuộc Cách Mạng Tiếp Nối Blockchain, NFT Và Thế Giới Điện Tử</div>
                        <div class="product-price">500.000đ</div>
                        <button class="buy-button">Mua hàng</button>
                    </div>
                </div>
            </section>

            <!-- Sách Thiếu nhi -->
            <section class="product-section">
                <h2 class="section-title">Sách Thiếu nhi</h2>
                <div class="product-grid">
                    <div class="product-item">
                        <img src="images/book5.jpg" alt="Em tập vẽ - Động vật">
                        <div class="product-title">Em tập vẽ và tô màu - Động vật</div>
                        <div class="product-price">40.000đ</div>
                        <button class="buy-button">Mua hàng</button>
                    </div>
                    <div class="product-item">
                        <img src="images/book6.jpg" alt="Em tập vẽ - Phong cảnh">
                        <div class="product-title">Em tập vẽ và tô màu - Phong cảnh</div>
                        <div class="product-price">50.000đ</div>
                        <button class="buy-button">Mua hàng</button>
                    </div>
                    <div class="product-item">
                        <img src="images/book7.jpg" alt="Gấu con thông minh">
                        <div class="product-title">Gấu con thông minh</div>
                        <div class="product-price">200.000đ</div>
                        <button class="buy-button">Mua hàng</button>
                    </div>
                    <div class="product-item">
                        <img src="images/book8.jpg" alt="Vệ sỹ tốt nhất">
                        <div class="product-title">Vệ sỹ tốt nhất</div>
                        <div class="product-price">500.000đ</div>
                        <button class="buy-button">Mua hàng</button>
                    </div>
                </div>
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
</body>
</html>