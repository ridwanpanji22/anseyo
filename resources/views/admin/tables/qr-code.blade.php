@extends('layouts.admin')

@section('title', 'QR Code Meja - Anseyo Restaurant')

@section('page-title', 'QR Code Meja')
@section('page-subtitle', 'QR Code untuk pemesanan di meja ' . $table->number)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.tables.index') }}">Manajemen Meja</a></li>
    <li class="breadcrumb-item active" aria-current="page">QR Code</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header text-center">
                <h4>QR Code Meja {{ $table->number }}</h4>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <div class="qr-code-container bg-light p-4 rounded">
                        <!-- QR Code akan ditampilkan di sini -->
                        <div id="qrcode" class="d-inline-block"></div>
                        <div id="qr-loading" class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Generating QR Code...</p>
                        </div>
                        <div id="qr-error" class="d-none text-center">
                            <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
                            <p class="mt-2 text-muted">QR Code tidak dapat ditampilkan</p>
                            <p class="small">Silakan gunakan URL di bawah untuk akses manual</p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h6>URL Pemesanan:</h6>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ $qrCodeUrl }}" readonly id="qrUrl">
                        <button class="btn btn-outline-secondary" type="button" onclick="copyUrl()">
                            <i class="bi bi-clipboard"></i> Copy
                        </button>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h6>Informasi Meja:</h6>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h5 class="mb-1">{{ $table->number }}</h5>
                                <small class="text-muted">Nomor Meja</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h5 class="mb-1">{{ $table->capacity }}</h5>
                                <small class="text-muted">Kapasitas</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-center gap-2">
                    <button class="btn btn-primary" onclick="printQR()">
                        <i class="bi bi-printer"></i> Print QR Code
                    </button>
                    <a href="{{ route('admin.tables.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const qrContainer = document.getElementById('qrcode');
    const qrLoading = document.getElementById('qr-loading');
    const qrError = document.getElementById('qr-error');
    const qrUrl = '{{ $qrCodeUrl }}';
    
    console.log('Generating QR Code for URL:', qrUrl);
    
    // Flag to prevent multiple QR codes
    let qrGenerated = false;
    
    // Method 1: Try QR Server API (most reliable)
    function generateWithQRServer() {
        if (qrGenerated) return; // Prevent multiple generation
        
        const qrServerUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(qrUrl)}`;
        
        // Test if image loads successfully
        const img = new Image();
        img.onload = function() {
            if (!qrGenerated) {
                qrContainer.innerHTML = `<img src="${qrServerUrl}" alt="QR Code" class="img-fluid" style="max-width: 200px;">`;
                console.log('QR Code generated with QR Server API');
                qrLoading.style.display = 'none';
                qrGenerated = true;
            }
        };
        img.onerror = function() {
            console.log('QR Server API failed, trying QRCode.js library');
            generateWithQRCodeJS();
        };
        img.src = qrServerUrl;
    }
    
    // Method 2: Try QRCode.js library as fallback
    function generateWithQRCodeJS() {
        if (qrGenerated) return; // Prevent multiple generation
        
        // Load QRCode.js dynamically
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js';
        script.onload = function() {
            try {
                if (typeof QRCode !== 'undefined' && !qrGenerated) {
                    new QRCode(qrContainer, {
                        text: qrUrl,
                        width: 200,
                        height: 200,
                        colorDark: "#000000",
                        colorLight: "#FFFFFF",
                        correctLevel: QRCode.CorrectLevel.H
                    });
                    console.log('QR Code generated with QRCode.js library');
                    qrLoading.style.display = 'none';
                    qrGenerated = true;
                } else if (!qrGenerated) {
                    throw new Error('QRCode library not loaded');
                }
            } catch (error) {
                console.error('Error with QRCode.js:', error);
                if (!qrGenerated) {
                    showError();
                }
            }
        };
        script.onerror = function() {
            console.error('Failed to load QRCode.js library');
            if (!qrGenerated) {
                showError();
            }
        };
        document.head.appendChild(script);
    }
    
    function showError() {
        if (qrGenerated) return; // Prevent multiple error states
        
        qrLoading.style.display = 'none';
        qrError.classList.remove('d-none');
        qrGenerated = true;
    }
    
    // Start with QR Server API
    generateWithQRServer();
    
    // Set timeout for fallback (only if QR Server fails)
    setTimeout(() => {
        if (!qrGenerated) {
            generateWithQRCodeJS();
        }
    }, 3000);
});

// Copy URL function
function copyUrl() {
    const urlInput = document.getElementById('qrUrl');
    urlInput.select();
    urlInput.setSelectionRange(0, 99999);
    
    try {
        // Modern clipboard API
        navigator.clipboard.writeText(urlInput.value).then(function() {
            showCopyFeedback(true);
        }).catch(function() {
            // Fallback for older browsers
            document.execCommand('copy');
            showCopyFeedback(true);
        });
    } catch (err) {
        // Final fallback
        document.execCommand('copy');
        showCopyFeedback(true);
    }
}

function showCopyFeedback(success) {
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    
    if (success) {
        button.innerHTML = '<i class="bi bi-check"></i> Copied!';
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-success');
    } else {
        button.innerHTML = '<i class="bi bi-x"></i> Failed!';
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-danger');
    }
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.classList.remove('btn-success', 'btn-danger');
        button.classList.add('btn-outline-secondary');
    }, 2000);
}

// Print QR Code function
function printQR() {
    const printWindow = window.open('', '_blank');
    const qrUrl = '{{ $qrCodeUrl }}';
    const tableNumber = '{{ $table->number }}';
    const tableCapacity = '{{ $table->capacity }}';
    
    printWindow.document.write(`
        <html>
            <head>
                <title>QR Code Meja ${tableNumber}</title>
                <style>
                    body { 
                        font-family: Arial, sans-serif; 
                        text-align: center; 
                        padding: 20px; 
                        margin: 0;
                    }
                    .qr-container { 
                        margin: 20px 0; 
                        padding: 20px;
                        border: 1px solid #ddd;
                        display: inline-block;
                    }
                    .info { 
                        margin: 10px 0; 
                        font-size: 14px;
                    }
                    .title {
                        font-size: 24px;
                        font-weight: bold;
                        margin-bottom: 20px;
                    }
                    @media print { 
                        body { margin: 0; }
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="title">QR Code Meja ${tableNumber}</div>
                <div class="qr-container">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(qrUrl)}" alt="QR Code" style="max-width: 300px;">
                </div>
                <div class="info">
                    <p><strong>Nomor Meja:</strong> ${tableNumber}</p>
                    <p><strong>Kapasitas:</strong> ${tableCapacity} orang</p>
                    <p><strong>URL:</strong> ${qrUrl}</p>
                </div>
                <div class="no-print">
                    <button onclick="window.print()">Print</button>
                    <button onclick="window.close()">Close</button>
                </div>
            </body>
        </html>
    `);
    printWindow.document.close();
}
</script>
@endpush 