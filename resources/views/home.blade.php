@extends('layouts.admin')

@section('title', 'Dashboard - Anseyo Restaurant')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang di dashboard admin')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Selamat Datang!</h4>
            </div>
            <div class="card-body">
                <p>Anda telah berhasil login sebagai admin. Silakan gunakan menu di sidebar untuk mengakses fitur-fitur admin.</p>
                
                <div class="row mt-4">
                    <div class="col-md-3">
                        <a href="{{ route('admin.menu.index') }}" class="btn btn-primary w-100">
                            <i class="bi bi-list-ul me-2"></i>
                            Manajemen Menu
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-success w-100">
                            <i class="bi bi-cart-check me-2"></i>
                            Manajemen Pesanan
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.staff.index') }}" class="btn btn-info w-100">
                            <i class="bi bi-people me-2"></i>
                            Manajemen Staf
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-warning w-100">
                            <i class="bi bi-bar-chart me-2"></i>
                            Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
