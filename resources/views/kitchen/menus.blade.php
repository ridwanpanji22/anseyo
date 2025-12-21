@extends('layouts.admin')

@section('title', 'Menu Availability - Kitchen')

@section('page-title', 'Menu Availability')
@section('page-subtitle', 'Manage menu availability status')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('kitchen.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Menu Availability</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Menu Name</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menus as $menu)
                        <tr>
                            <td>{{ $menu->category->name }}</td>
                            <td>{{ $menu->name }}</td>
                            <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                            <td>
                                @if($menu->is_available)
                                    <span class="badge bg-success">Available</span>
                                @else
                                    <span class="badge bg-danger">Unavailable</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('kitchen.menus.toggle', $menu) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $menu->is_available ? 'btn-danger' : 'btn-success' }}">
                                        {{ $menu->is_available ? 'Mark Unavailable' : 'Mark Available' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
