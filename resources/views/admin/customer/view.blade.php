<div class="row">
    @if ($customers->isEmpty())
        <div class="col-12 text-center text-danger">
            {{ @trans('messages.no_customer') }}
        </div>
    @else
        @foreach ($customers as $customer)
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <div class="card customer-card shadow-md h-100 border-0 mb-0">
                    <div class="card-body text-center pb-0">
                        <div class="d-flex justify-content-center gap-3 mb-3">
                            <div class="position-relative">
                                @if ($customer->customer_image)
                                    <img src="{{ Storage::url($customer->customer_image) }}" alt="Customer"
                                        class="rounded-circle clickable-image shadow" width="80" height="80"
                                        data-bs-toggle="modal" data-bs-target="#imageModal"
                                        data-image="{{ Storage::url($customer->customer_image) }}">
                                    <small class="d-block text-muted mt-1">{{ __('portal.owner') }}</small>
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm" style="width:80px; height:80px; background-color:#FF9933; color:white; font-weight:800; font-size:28px; letter-spacing:-1px;">{{ mb_strtoupper(mb_substr($customer->customer_name ?? 'C', 0, 1)) }}</div>
                                    <small class="d-block text-muted mt-1">{{ __('portal.owner') }}</small>
                                @endif
                            </div>
                            <div class="position-relative">
                                @if ($customer->shop_image)
                                    <img src="{{ Storage::url($customer->shop_image) }}" alt="Shop"
                                        class="rounded clickable-image shadow" width="80" height="80"
                                        data-bs-toggle="modal" data-bs-target="#imageModal"
                                        data-image="{{ Storage::url($customer->shop_image) }}">
                                    <small class="d-block text-muted mt-1">{{ __('portal.shop') }}</small>
                                @else
                                    <div class="rounded d-flex align-items-center justify-content-center mx-auto shadow-sm" style="width:80px; height:80px; background-color:#33CCFF; color:white; font-weight:800; font-size:28px; letter-spacing:-1px;">{{ mb_strtoupper(mb_substr($customer->shop_name ?? 'S', 0, 1)) }}</div>
                                    <small class="d-block text-muted mt-1">{{ __('portal.shop') }}</small>
                                @endif
                            </div>
                        </div>

                        {{-- Title: Shop Name --}}
                        <h5 class="mt-3 text-danger fw-bold">{{ ucfirst($customer->shop_name ?? $customer->customer_name ?? '-') }}</h5>

                        <ul class="list-unstyled small text-start mt-2 text-secondary">
                            {{-- Owner Name --}}
                            <li class="mb-2 d-flex align-items-start">
                                <i class="fa fa-user me-2 text-primary mt-1"></i>
                                <span class="fw-bolder">{{ ucfirst($customer->customer_name ?? '-') }}</span>
                            </li>
                            {{-- Location / Address with OpenStreetMap link --}}
                            @php
                                $rawAddr  = $customer->shop_address ?? $customer->city ?? '-';
                                $parts    = array_values(array_filter(array_map('trim', explode(',', $rawAddr))));
                                $shortAddr = count($parts) > 3
                                    ? implode(', ', array_slice($parts, 0, 3))
                                    : $rawAddr;
                            @endphp
                            <li class="mb-2 d-flex align-items-start">
                                <i class="fa fa-map-marker me-2 text-danger mt-1 flex-shrink-0"></i>
                                <span class="fw-bolder">
                                    @if($customer->latitude && $customer->longitude)
                                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $customer->latitude }},{{ $customer->longitude }}"
                                           target="_blank" class="text-secondary text-decoration-none"
                                           title="{{ $rawAddr }}">
                                            {{ $shortAddr }}
                                            <i class="fa fa-map-o text-danger ps-1" style="font-size:13px;"></i>
                                        </a>
                                    @else
                                        <span title="{{ $rawAddr }}">{{ $shortAddr }}</span>
                                    @endif
                                </span>
                            </li>
                            {{-- City --}}
                            @if($customer->city && $customer->city !== $customer->shop_address)
                            <li class="mb-2 d-flex align-items-start">
                                <i class="fa fa-home me-2 text-success mt-1"></i>
                                <span class="fw-bolder">{{ $customer->city }}</span>
                            </li>
                            @endif
                            {{-- Mobile --}}
                            <li class="mb-2">
                                <i class="fa fa-phone me-2 text-success"></i>
                                @if($customer->customer_number)
                                <a href="tel:+91{{ $customer->customer_number }}" class="fw-bolder text-secondary text-decoration-none">
                                    +91 {{ substr($customer->customer_number, 0, 5) }} {{ substr($customer->customer_number, 5) }}
                                </a>
                                @else
                                <span class="fw-bolder">-</span>
                                @endif
                            </li>
                            {{-- Status --}}
                            {{-- <li class="mb-2 pb-1">
                                <i class="fa fa-info-circle me-2 text-warning"></i>
                                <span class="badge {{ $customer->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $customer->status ?? '-' }}
                                </span>
                            </li> --}}
                        </ul>

                        <div class="mt-3 d-flex justify-content-center gap-2">
                            <a class="btn btn-secondary" href="{{ route('admin.customer.edit', [$customer->id, 'page' => $customers->currentPage()]) }}">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a class="btn btn-primary"
                                href="{{ route('admin.order.index', ['customer_id' => $customer->id]) }}">
                                <i class="fa fa-shopping-cart"></i>
                            </a>
                            <button type="button" class="btn btn-success share-customer-btn"
                                data-name="{{ $customer->customer_name ?? '' }}"
                                data-shop="{{ $customer->shop_name ?? '' }}"
                                data-address="{{ $customer->shop_address ?? '' }}"
                                data-city="{{ $customer->city ?? '' }}"
                                data-phone="{{ $customer->customer_number ?? '' }}"
                                data-lat="{{ $customer->latitude ?? '' }}"
                                data-lng="{{ $customer->longitude ?? '' }}"
                                title="Share">
                                <i class="fa fa-share-alt"></i>
                            </button>
                            <a class="btn btn-secondary user-delete-btn" data-bs-toggle="modal"
                                data-bs-target="#user-delete" data-customer-id="{{ $customer->id }}">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

<div class="d-md-flex justify-content-center">
    {{ $customers->links('admin.parts.pagination') }}
</div>
