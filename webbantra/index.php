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
    <title>Trà Thái Nguyên - Trà Xanh Chất Lượng</title>
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

        /* Main Content Grid */
        .main-container {
            max-width: 1200px;
            margin: 0 auto; /* Remove top margin, keep auto for horizontal centering */
            display: grid;
            /* New grid: Left sidebar and a larger main content area */
            grid-template-columns: 280px 1fr;
            gap: 25px; /* Space between columns */
            padding: 0 15px; /* Inner padding */
        }

        /* Sidebar Styling */
        .sidebar {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            height: fit-content; /* Only take content height */
            overflow: hidden; /* For rounded corners */
        }

        .sidebar-header {
            background: #4CAF50;
            color: white;
            padding: 15px;
            font-weight: bold;
            font-size: 16px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }

        .news-list {
            padding: 15px;
        }

        .news-item {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
            align-items: flex-start;
        }

        .news-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .news-image {
            width: 80px;
            height: 60px;
            border-radius: 5px;
            margin-right: 12px;
            flex-shrink: 0;
            background-size: cover;
            background-position: center;
            background-color: #f0f0f0;
        }

        .news-content h4 {
            font-size: 13px;
            color: #333;
            line-height: 1.4;
            font-weight: normal;
            transition: color 0.3s;
        }

        .news-content h4:hover {
            color: #4CAF50;
        }

        /* Central Content Area (Products & Articles) */
        .content-area {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 25px;
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


        /* Products Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* Đã thay đổi để cố định 3 cột */
            gap: 20px;
            margin-bottom: 40px;
        }

        .product-item {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            transition: box-shadow 0.3s, transform 0.2s;
            text-align: center;
        }

        .product-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-3px);
        }

        .product-image {
            width: 100%;
            height: 150px; /* Product image height */
            background-size: cover;
            background-position: center;
            background-color: #f8f8f8;
            border-bottom: 1px solid #eee;
        }

        .product-info {
            padding: 12px;
        }

        .product-name {
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
            line-height: 1.3;
        }

        .product-price {
            color: #e53e3e; /* Red price */
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .btn-order {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .btn-order:hover {
            background: #2E7D32;
        }

        /* Articles Section */
        .articles-section {
            margin-top: 40px;
        }

        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .article-item {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            transition: box-shadow 0.3s, transform 0.2s;
        }

        .article-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-3px);
        }

        .article-image {
            width: 100%;
            height: 120px; /* Article image height */
            background-size: cover;
            background-position: center;
            background-color: #f8f8f8;
            border-bottom: 1px solid #eee;
        }

        .article-content {
            padding: 12px;
        }

        .article-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            line-height: 1.4;
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
            .main-container {
                /* Adjust grid for smaller screens if needed */
                grid-template-columns: 200px 1fr;
                gap: 20px;
            }
            /* Giữ nguyên 3 cột cho products-grid trên màn hình lớn nếu muốn */
            /* .products-grid {
                grid-template-columns: repeat(3, 1fr);
            } */
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
                grid-template-columns: 1fr; /* Single column layout */
                padding: 0 20px; /* Add horizontal padding */
            }
            .sidebar {
                margin-bottom: 25px; /* Space between stacked sections */
            }
            .products-grid,
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
            .products-grid,
            .articles-grid {
                grid-template-columns: 1fr; /* Single item per row */
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
                <a href="register.php">Đăng ký</a>
                <span>|</span>
                <a href="login.php">Đăng nhập</a>
            </div>
        </div>
    </div>

    <header class="header">
        <div class="header-container">
            <div class="logo">
                <a href="index.php">
                    <img src="https://via.placeholder.com/150x50/4CAF50/FFFFFF?text=TAN+CUONG+TEA" alt="Tân Cương Tea Logo">
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
            <a href="index.php" class="active">Trang chủ</a>
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
        <aside class="sidebar">
            <div class="sidebar-header">Tin tức & Sự kiện</div>
            <div class="news-list">
                <div class="news-item">
                    <div class="news-image" style="background-image: url('https://via.placeholder.com/80x60/f0f0f0/666?text=News1')"></div>
                    <div class="news-content">
                        <h4>7+ Tác dụng của trà shan tuyết không phải ai cũng biết</h4>
                    </div>
                </div>
                <div class="news-item">
                    <div class="news-image" style="background-image: url('https://via.placeholder.com/80x60/f0f0f0/666?text=News2')"></div>
                    <div class="news-content">
                        <h4>Cách Ướp Trà Hoa Nhài & Cách Làm Trà Hoa Nhài Khô</h4>
                    </div>
                </div>
                <div class="news-item">
                    <div class="news-image" style="background-image: url('https://via.placeholder.com/80x60/f0f0f0/666?text=News3')"></div>
                    <div class="news-content">
                        <h4>Cách pha trà shan tuyết cổ thụ đơn giản mà chuẩn vị</h4>
                    </div>
                </div>
                <div class="news-item">
                    <div class="news-image" style="background-image: url('https://via.placeholder.com/80x60/f0f0f0/666?text=News4')"></div>
                    <div class="news-content">
                        <h4>12 Tác Dụng Của Trà Sen Mà Trước Khi Mua Bạn Phải Biết</h4>
                    </div>
                </div>
                <div class="news-item">
                    <div class="news-image" style="background-image: url('https://via.placeholder.com/80x60/f0f0f0/666?text=News5')"></div>
                    <div class="news-content">
                        <h4>Cách ướp trà với hoa cúc</h4>
                    </div>
                </div>
            </div>
        </aside>

        <main class="content-area">
            <section>
                <h2 class="section-title">Sản phẩm nổi bật</h2>
                <div class="products-grid">
                    <div class="product-item">
                        <div class="product-image" style="background-image: url('https://via.placeholder.com/200x180/e8f5e8/4CAF50?text=Bot+tra+xanh')"></div>
                        <div class="product-info">
                            <div class="product-name">Bột trà xanh Thái Nguyên</div>
                            <div class="product-price">100.000đ</div>
                            <button class="btn-order">Đặt hàng</button>
                        </div>
                    </div>
                    <div class="product-item">
                        <div class="product-image" style="background-image: url('https://via.placeholder.com/200x180/e8f5e8/4CAF50?text=Tra+o+long')"></div>
                        <div class="product-info">
                            <div class="product-name">Trà ô long Đài Loan</div>
                            <div class="product-price">125.000đ</div>
                            <button class="btn-order">Đặt hàng</button>
                        </div>
                    </div>
                    <div class="product-item">
                        <div class="product-image" style="background-image: url('https://via.placeholder.com/200x180/e8f5e8/4CAF50?text=Tra+non+tom')"></div>
                        <div class="product-info">
                            <div class="product-name">Trà nõn tôm Tân Cương</div>
                            <div class="product-price">200.000đ</div>
                            <button class="btn-order">Đặt hàng</button>
                        </div>
                    </div>
                    <div class="product-item">
                        <div class="product-image" style="background-image: url('https://via.placeholder.com/200x180/e8f5e8/4CAF50?text=Bot+tra')"></div>
                        <div class="product-info">
                            <div class="product-name">Bột trà xanh</div>
                            <div class="product-price">250.000đ</div>
                            <button class="btn-order">Đặt hàng</button>
                        </div>
                    </div>
                    <div class="product-item">
                        <div class="product-image" style="background-image: url('https://via.placeholder.com/200x180/e8f5e8/4CAF50?text=Thiet+quan+am')"></div>
                        <div class="product-info">
                            <div class="product-name">Trà thiết quan âm</div>
                            <div class="product-price">350.000đ</div>
                            <button class="btn-order">Đặt hàng</button>
                        </div>
                    </div>
                    <div class="product-item">
                        <div class="product-image" style="background-image: url('https://via.placeholder.com/200x180/e8f5e8/4CAF50?text=O+Long+trang')"></div>
                        <div class="product-info">
                            <div class="product-name">Trà Ô Long trắng</div>
                            <div class="product-price">250.000đ</div>
                            <button class="btn-order">Đặt hàng</button>
                        </div>
                    </div>
                </div>
            </section>

            <section class="articles-section">
                <h2 class="section-title">Bài viết nổi bật</h2>
                <div class="articles-grid">
                    <div class="article-item">
                        <div class="article-image" style="background-image: url('https://via.placeholder.com/300x150/e8f5e8/4CAF50?text=Noi+mua+tra')"></div>
                        <div class="article-content">
                            <h3 class="article-title">Nơi Mua Trà Thái Nguyên Tại TPHCM Chính Gốc 100%</h3>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-image" style="background-image: url('https://via.placeholder.com/300x150/e8f5e8/4CAF50?text=Cay+che')"></div>
                        <div class="article-content">
                            <h3 class="article-title">Cây chè Thái Nguyên: Lịch sử, cách trồng & tác dụng</h3>
                        </div>
                    </div>
                    <div class="article-item">
                        <div class="article-image" style="background-image: url('https://via.placeholder.com/300x150/e8f5e8/4CAF50?text=Tho+che')"></div>
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
</body>

</html>