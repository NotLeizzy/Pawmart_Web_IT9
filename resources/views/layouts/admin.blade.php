<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawMart Admin - @yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #ff9f43;
            --secondary-color: #ff6b6b;
            --sidebar-bg: #2d3436;
            --sidebar-hover: #353b42;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f5f6fa;
        }

        /* Sidebar Styles */
        .admin-sidebar {
            width: 280px;
            background-color: var(--sidebar-bg);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: margin-left 0.3s ease;
        }

        .sidebar-brand {
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-decoration: none;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-brand:hover {
            color: var(--primary-color);
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: var(--sidebar-hover);
            color: var(--primary-color);
            padding-left: 25px;
        }

        .sidebar-menu a i {
            width: 20px;
        }

        /* Main Content Area */
        .admin-main {
            margin-left: 280px;
            width: calc(100% - 280px);
            display: flex;
            flex-direction: column;
        }

        /* Top Navigation Bar */
        .admin-navbar {
            background-color: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .admin-navbar-title {
            font-size: 20px;
            font-weight: 600;
            color: #2d3436;
        }

        .admin-navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            background-color: #f5f6fa;
            border-radius: 8px;
        }

        .user-info-dropdown {
            position: relative;
        }

        .user-info-toggle {
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #2d3436;
            font-weight: 500;
        }

        .user-info-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            margin-top: 10px;
        }

        .user-info-menu.show {
            display: block;
        }

        .user-info-menu a,
        .user-info-menu form button {
            display: block;
            width: 100%;
            padding: 10px 15px;
            color: #2d3436;
            text-decoration: none;
            border: none;
            background: none;
            cursor: pointer;
            text-align: left;
            transition: all 0.3s ease;
        }

        .user-info-menu a:hover,
        .user-info-menu form button:hover {
            background-color: #f5f6fa;
            color: var(--primary-color);
        }

        /* Main Content */
        .admin-content {
            flex: 1;
            padding: 30px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                margin-left: -280px;
            }

            .admin-sidebar.show {
                margin-left: 0;
            }

            .admin-main {
                margin-left: 0;
                width: 100%;
            }

            .sidebar-toggle {
                display: block;
            }
        }

        @media (min-width: 769px) {
            .sidebar-toggle {
                display: none;
            }
        }

        /* Alert Styles */
        .alert {
            border-left: 4px solid var(--primary-color);
        }

        /* Button Styles */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #ff8c1f;
            border-color: #ff8c1f;
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-secondary:hover {
            background-color: #ff5252;
            border-color: #ff5252;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar Navigation -->
    <aside class="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <i class="fas fa-paw"></i> PawMart Admin
        </a>

        <ul class="sidebar-menu">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('admin.dashboard') }}" class="@if(request()->routeIs('admin.dashboard')) active @endif">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Products Section -->
            <li class="nav-section">
                <span style="color: rgba(255,255,255,0.4); padding: 15px 20px; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                    <i class="fas fa-box"></i> Inventory
                </span>
            </li>
            <li>
                <a href="{{ route('admin.products.index') }}" class="@if(request()->routeIs('admin.products.*')) active @endif">
                    <i class="fas fa-cube"></i>
                    <span>Products</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories.index') }}" class="@if(request()->routeIs('admin.categories.*')) active @endif">
                    <i class="fas fa-tags"></i>
                    <span>Categories</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.stock-movements.index') }}" class="@if(request()->routeIs('admin.stock-movements.*')) active @endif">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Stock Movements</span>
                </a>
            </li>

            <!-- Orders Section -->
            <li class="nav-section">
                <span style="color: rgba(255,255,255,0.4); padding: 15px 20px; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                    <i class="fas fa-shopping-cart"></i> Orders
                </span>
            </li>
            <li>
                <a href="{{ route('admin.orders.index') }}" class="@if(request()->routeIs('admin.orders.*')) active @endif">
                    <i class="fas fa-receipt"></i>
                    <span>Orders</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.payments.index') }}" class="@if(request()->routeIs('admin.payments.*')) active @endif">
                    <i class="fas fa-credit-card"></i>
                    <span>Payments</span>
                </a>
            </li>

            <!-- Pets Section -->
            <li class="nav-section">
                <span style="color: rgba(255,255,255,0.4); padding: 15px 20px; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                    <i class="fas fa-paw"></i> Pets
                </span>
            </li>
            <li>
                <a href="{{ route('admin.pets.index') }}" class="@if(request()->routeIs('admin.pets.*')) active @endif">
                    <i class="fas fa-paw"></i>
                    <span>All Pets</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.pet-orders.index') }}" class="@if(request()->routeIs('admin.pet-orders.*')) active @endif">
                    <i class="fas fa-heart"></i>
                    <span>Pet Orders</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="admin-main">
        <!-- Top Navigation Bar -->
        <nav class="admin-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-light sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h4 class="admin-navbar-title mb-0">@yield('page-title', 'Dashboard')</h4>
            </div>

            <div class="admin-navbar-right">
                <!-- User Info Dropdown -->
                <div class="user-info-dropdown">
                    <button class="user-info-toggle" id="userInfoToggle">
                        <i class="fas fa-user-circle"></i>
                        <span>{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down" style="font-size: 12px;"></i>
                    </button>

                    <div class="user-info-menu" id="userInfoMenu">
                        <a href="{{ route('profile.edit') }}">
                            <i class="fas fa-user"></i> Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}" style="display: none;" id="logoutForm">
                            @csrf
                        </form>
                        <button form="logoutForm" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="admin-content">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar Toggle for Mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.admin-sidebar');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
        }

        // User Info Dropdown
        const userInfoToggle = document.getElementById('userInfoToggle');
        const userInfoMenu = document.getElementById('userInfoMenu');

        if (userInfoToggle && userInfoMenu) {
            userInfoToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                userInfoMenu.classList.toggle('show');
            });

            document.addEventListener('click', function() {
                userInfoMenu.classList.remove('show');
            });
        }

        // Close dropdown when clicking on menu items
        document.querySelectorAll('.user-info-menu a, .user-info-menu button').forEach(item => {
            item.addEventListener('click', function() {
                userInfoMenu.classList.remove('show');
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
