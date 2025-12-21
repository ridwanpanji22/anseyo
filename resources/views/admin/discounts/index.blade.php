@extends('layouts.admin')

@section('title', 'Manage Discounts')

@section('page-title', 'Discounts')
@section('page-subtitle', 'Manage event discounts and promotions')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Discounts</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">Discount List</h4>
        <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary">Add New Discount</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Value (%)</th>
                        <th>Min Purchase</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($discounts as $discount)
                        <tr>
                            <td>{{ $discount->name }}</td>
                            <td>{{ $discount->value }}%</td>
                            <td>Rp {{ number_format($discount->min_purchase, 0, ',', '.') }}</td>
                            <td>{{ $discount->start_date->format('d M Y H:i') }}</td>
                            <td>{{ $discount->end_date->format('d M Y H:i') }}</td>
                            <td>
                                @if($discount->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.discounts.edit', $discount) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No discounts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $discounts->links() }}
    </div>
</div>
@endsection
