<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anseyo Restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
            <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .welcome-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }
        .btn-custom {
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
            </style>
    </head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="welcome-card p-5 text-center">
                    <div class="mb-4">
                        <i class="bi bi-shop text-primary" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h1 class="display-4 fw-bold text-primary mb-3">Anseyo Restaurant</h1>
                    <p class="lead text-muted mb-4">Sistem Pemesanan dan Manajemen Restoran</p>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-custom w-100">
                                <i class="bi bi-person-circle me-2"></i>
                                Login Admin
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('order.create', ['table' => 'A1']) }}" class="btn btn-success btn-custom w-100">
                                <i class="bi bi-qr-code me-2"></i>
                                Pesan Menu
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Scan QR Code di meja untuk memesan
                        </small>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="card border-0 bg-light">
                                <div class="card-body p-3">
                                    <i class="bi bi-people text-primary fs-4"></i>
                                    <div class="mt-2">
                                        <small class="text-muted">Manajemen Staf</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 bg-light">
                                <div class="card-body p-3">
                                    <i class="bi bi-list-ul text-success fs-4"></i>
                                    <div class="mt-2">
                                        <small class="text-muted">Manajemen Menu</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 bg-light">
                                <div class="card-body p-3">
                                    <i class="bi bi-cart-check text-warning fs-4"></i>
                                    <div class="mt-2">
                                        <small class="text-muted">Manajemen Pesanan</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 bg-light">
                                <div class="card-body p-3">
                                    <i class="bi bi-bar-chart text-info fs-4"></i>
                                    <div class="mt-2">
                                        <small class="text-muted">Laporan Penjualan</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
