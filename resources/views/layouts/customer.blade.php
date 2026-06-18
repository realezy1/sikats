<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SiKats') }} - Self Order</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Midtrans Snap JS -->
    @if(config('midtrans.is_production'))
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #aaa;
        }

        .category-tab {
            white-space: nowrap;
            cursor: pointer;
            padding: 8px 16px;
            border-radius: 20px;
            color: #6c757d;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
        }
        .category-tab:hover {
            background-color: #e9ecef;
            color: #212529;
        }
        .category-tab.active {
            background-color: #f8e5e5;
            color: #dc3545;
            font-weight: 600;
        }

        .menu-card {
            border: none;
            border-radius: 12px;
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }
        .menu-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
        }
        .menu-img-wrapper {
            height: 160px;
            border-radius: 12px 12px 0 0;
            overflow: hidden;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .menu-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .btn-add {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            border: 1px solid #dee2e6;
            color: #212529;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: all 0.2s;
        }
        .btn-add:hover {
            background-color: #f8f9fa;
            border-color: #adb5bd;
        }

        /* Cart Sidebar Styles */
        .cart-sidebar {
            background-color: white;
            border-left: 1px solid #dee2e6;
            height: 100vh;
            position: fixed;
            top: 0;
            right: 0;
            width: 350px;
            display: flex;
            flex-direction: column;
            z-index: 1040;
            transition: transform 0.3s ease-in-out;
        }
        
        .main-content {
            margin-right: 350px;
        }

        @media (max-width: 991.98px) {
            .cart-sidebar {
                transform: translateY(100%);
                bottom: 0;
                top: auto;
                height: 80vh;
                width: 100%;
                border-left: none;
                border-top: 1px solid #dee2e6;
                border-radius: 20px 20px 0 0;
                box-shadow: 0 -4px 12px rgba(0,0,0,0.1);
            }
            .cart-sidebar.show {
                transform: translateY(0);
            }
            .main-content {
                margin-right: 0;
                padding-bottom: 120px; /* Extra space for floating button so it doesn't overlap menu cards */
            }
            /* Larger tap targets for quantity buttons on mobile */
            .cart-qty-btn {
                width: 36px !important;
                height: 36px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                font-size: 1.1rem !important;
            }
            .cart-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background-color: rgba(0,0,0,0.5);
                z-index: 1030;
                display: none;
            }
            .cart-backdrop.show {
                display: block;
            }
        }

        .floating-cart-btn {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1020;
            border-radius: 30px;
            padding: 12px 24px;
            box-shadow: 0 4px 12px rgba(220,53,69,0.3);
            display: none; /* Only show on mobile */
        }

        @media (max-width: 991.98px) {
            .floating-cart-btn {
                display: flex;
                align-items: center;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    @yield('content')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
