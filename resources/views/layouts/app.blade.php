<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawMart - @yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;700&family=Fredoka:wght@400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #ff9f43;
            --secondary-color: #ff6b6b;
            --accent-color: #ffa502;
            --light-bg: #fff8f3;
            --dark-text: #2d3436;
        }

        * {
            font-family: 'Fredoka', sans-serif;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Comfortaa', cursive;
        }

        body {
            background-color: var(--light-bg);
            color: var(--dark-text);
        }

        /* Customer Navbar Styles */
        .customer-navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 12px 0;
        }

        .customer-navbar .navbar-brand {
            font-family: 'Comfortaa', cursive;
            font-size: 24px;
            font-weight: 700;
            color: white !important;
            margin-right: 30px;
        }

        .customer-navbar .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 8px 12px !important;
            border-radius: 6px;
            margin: 0 4px;
        }

        .customer-navbar .nav-link:hover,
        .customer-navbar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white !important;
        }

        .customer-navbar .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.5);
        }

        .customer-navbar .navbar-toggler:focus {
            box-shadow: none;
            border-color: white;
        }

        /* User Dropdown */
        .user-dropdown {
            position: relative;
        }

        .user-dropdown-toggle {
            color: white !important;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px !important;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .user-dropdown-toggle:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .user-dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            background-color: white;
            border: none;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            min-width: 200px;
            z-index: 1000;
            overflow: hidden;
        }

        .user-dropdown-menu.show {
            display: block;
        }

        .user-dropdown-menu a,
        .user-dropdown-menu form button {
            display: block;
            width: 100%;
            padding: 12px 16px;
            color: var(--dark-text);
            text-decoration: none;
            border: none;
            background: none;
            cursor: pointer;
            text-align: left;
            transition: all 0.3s ease;
        }

        .user-dropdown-menu a:hover,
        .user-dropdown-menu form button:hover {
            background-color: var(--light-bg);
            color: var(--primary-color);
        }

        .user-dropdown-menu a i,
        .user-dropdown-menu form button i {
            margin-right: 10px;
            width: 16px;
        }

        /* Content Area */
        .customer-content {
            padding: 30px 0;
        }

        /* Alert Styles */
        .alert {
            border-left: 4px solid var(--primary-color);
            border-radius: 8px;
        }

        .alert-success {
            background-color: #d4edda;
            border-left-color: #28a745;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-left-color: #dc3545;
        }

        /* Button Styles */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            box-shadow: 0 4px 12px rgba(255, 159, 67, 0.4);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .customer-navbar .nav-link {
                padding: 10px 8px !important;
            }

            .user-dropdown-menu {
                right: auto;
                left: 0;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Customer Navigation Bar -->
    <nav class="navbar navbar-expand-lg customer-navbar sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-paw"></i> PawMart
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('products.index')) active @endif" href="{{ route('products.index') }}">
                            <i class="fas fa-cube"></i> Products
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('pets.index')) active @endif" href="{{ route('pets.index') }}">
                            <i class="fas fa-paw"></i> Pets
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('orders.index')) active @endif" href="{{ route('orders.index') }}">
                            <i class="fas fa-shopping-bag"></i> My Orders
                        </a>
                    </li>
                </ul>

                <!-- User Dropdown -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item user-dropdown">
                        <a class="nav-link user-dropdown-toggle" href="javascript:void(0);" id="userDropdownToggle">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down" style="font-size: 12px;"></i>
                        </a>

                        <div class="user-dropdown-menu" id="userDropdownMenu">
                            <a href="{{ route('profile.edit') }}">
                                <i class="fas fa-user"></i> My Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}" style="display: none;" id="logoutForm">
                                @csrf
                            </form>
                            <button form="logoutForm" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content Area -->
    <div class="container customer-content">
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-exclamation-circle"></i> Error!</strong>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // User Dropdown Toggle
        const userDropdownToggle = document.getElementById('userDropdownToggle');
        const userDropdownMenu = document.getElementById('userDropdownMenu');

        if (userDropdownToggle && userDropdownMenu) {
            userDropdownToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                userDropdownMenu.classList.toggle('show');
            });

            document.addEventListener('click', function() {
                userDropdownMenu.classList.remove('show');
            });

            userDropdownMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>