@extends('admin.layouts.admin_layout')

@section('title', __('messages.dashboard'))
@section('page_title', __('messages.dashboard'))
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.dashboard') }}</li>
@endsection

@section('content')
<div class="row g-3">
    @php
        $cards = [
            ['label' => 'rooms', 'value' => $countRooms, 'icon' => 'bi-door-closed', 'bg' => 'primary'],
            ['label' => 'guests', 'value' => $countGuests, 'icon' => 'bi-person-vcard', 'bg' => 'success'],
            ['label' => 'bookings', 'value' => $countBookings, 'icon' => 'bi-calendar', 'bg' => 'info'],
            ['label' => 'check_in', 'value' => $countCheckedIn, 'icon' => 'bi-arrow-down-circle', 'bg' => 'warning'],
            ['label' => 'invoices', 'value' => number_format($totalInvoices, 2), 'icon' => 'bi-file-text', 'bg' => 'secondary'],
            ['label' => 'payments', 'value' => number_format($totalPayments, 2), 'icon' => 'bi-credit-card', 'bg' => 'dark'],
        ];
    @endphp

    @foreach($cards as $card)
    <div class="col-md-6 col-lg-4 col-xl-2">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-{{ $card['bg'] }} bg-opacity-10 rounded p-3">
                    <i class="bi {{ $card['icon'] }} fs-3 text-{{ $card['bg'] }}"></i>
                </div>
                <div>
                    <div class="text-muted small" data-i18n="{{ $card['label'] }}">{{ __('messages.' . $card['label']) }}</div>
                    <h4 class="mb-0">{{ $card['value'] }}</h4>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row mt-4 g-3">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header">
                <span data-i18n="branches">{{ __('messages.branches') }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @forelse($branches as $branch)
                        <div class="col-md-4">
                            <div class="border rounded p-3 d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $branch->name }}</h6>
                                    <small class="text-muted">{{ $branch->code }}</small>
                                    <p class="mb-0 mt-2 small text-muted">{{ $branch->address }}</p>
                                </div>
                                @if($branch->is_default)
                                    <span class="badge bg-primary">{{ __('messages.is_default') }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted">{{ __('messages.no_record') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
