<div class="row">
    @foreach($tables as $table)
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card {{ $table->status == 'occupied' ? 'border-danger' : 'border-success' }}">
            <div class="card-body text-center">
                <h5 class="card-title">{{ $table->number }}</h5>
                <p class="card-text">
                    <span class="badge bg-{{ $table->status == 'occupied' ? 'danger' : 'success' }}">
                        {{ $table->status == 'occupied' ? 'Terisi' : 'Tersedia' }}
                    </span>
                </p>
                @if($table->orders->count() > 0)
                    <small class="text-muted">
                        {{ $table->orders->count() }} pesanan aktif
                    </small>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div> 