<div class="app-sidebar">

    {{-- ─── DESKTOP: Full sidebar with logo + menu ─────────────── --}}
    <div class="main-menu">
        <div class="main-sidemenu text-center pb-3">
            {{-- <img src="{{ asset('images/logo.png') }}" alt="logo" class="dashboard-logo" width="100px"> --}}
            <span class="brand-text-logo">
                <span style="color:#FF9933; font-weight:800; font-size:1.3rem; letter-spacing:0.5px;">Farsan</span><span style="color:#5C3A21; font-weight:800; font-size:1.3rem; letter-spacing:0.5px;"> Hub</span>
            </span>
        </div>
        <div class="pt-2 side-menu">

            <div class="slide animate-right px-2">
                <a @class(['side-menu__item has-link', 'active' => request()->routeIs('admin.dashboard')])
                    data-bs-toggle="slide" href="{{ route('admin.dashboard') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1772 1772">
                        <path d="M384 1152q0-53-37.5-90.5T256 1024t-90.5 37.5T128 1152t37.5 90.5T256 1280t90.5-37.5T384 1152zm192-448q0-53-37.5-90.5T448 576t-90.5 37.5T320 704t37.5 90.5T448 832t90.5-37.5T576 704zm428 481 101-382q6-26-7.5-48.5T1059 725t-48 6.5-30 39.5l-101 382q-60 5-107 43.5t-63 98.5q-20 77 20 146t117 89 146-20 89-117q16-60-6-117t-72-91zm660-33q0-53-37.5-90.5T1536 1024t-90.5 37.5-37.5 90.5 37.5 90.5 90.5 37.5 90.5-37.5 37.5-90.5zm-640-640q0-53-37.5-90.5T896 384t-90.5 37.5T768 512t37.5 90.5T896 640t90.5-37.5T1024 512zm448 192q0-53-37.5-90.5T1344 576t-90.5 37.5T1216 704t37.5 90.5T1344 832t90.5-37.5T1472 704zm320 448q0 261-141 483-19 29-54 29H195q-35 0-54-29Q0 1414 0 1152q0-182 71-348t191-286 286-191 348-71 348 71 286 191 191 286 71 348z"
                            fill="currentColor"></path>
                    </svg>
                    <span class="side-menu__label">{{ __('portal.dashboard') }}</span>
                </a>
            </div>

            <div class="slide animate-right px-2">
                <a @class(['side-menu__item has-link', 'active' => request()->routeIs('admin.expense.*')])
                    data-bs-toggle="slide" href="{{ route('admin.expense.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 8.25H9m6 3H9m3 6-3-3h1.5a3 3 0 1 0 0-6M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span class="side-menu__label">{{ __('portal.expense') }}</span>
                </a>
            </div>

            <div class="slide animate-right px-2">
                <a @class(['side-menu__item has-link', 'active' => request()->routeIs('admin.customer.*')])
                    data-bs-toggle="slide" href="{{ route('admin.customer.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                    <span class="side-menu__label">{{ __('portal.customers') }}</span>
                </a>
            </div>

            <div class="slide animate-right px-2">
                <a @class(['side-menu__item has-link', 'active' => request()->routeIs('admin.product.*')])
                    data-bs-toggle="slide" href="{{ route('admin.product.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>
                    <span class="side-menu__label">{{ __('portal.products') }}</span>
                </a>
            </div>

            <div class="slide animate-right px-2">
                <a @class(['side-menu__item has-link', 'active' => request()->routeIs('admin.order.*')])
                    data-bs-toggle="slide" href="{{ route('admin.order.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                    <span class="side-menu__label">{{ __('portal.orders') }}</span>
                </a>
            </div>

            <div class="slide animate-right px-2">
                <a @class(['side-menu__item has-link', 'active' => request()->routeIs('admin.monthly-report.*')])
                    data-bs-toggle="slide" href="{{ route('admin.monthly-report.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    <span class="side-menu__label">{{ __('portal.monthly-reports') }}</span>
                </a>
            </div>

            @if(auth()->check() && auth()->user()->isSuperAdmin())
            <div class="slide animate-right px-2">
                <a @class(['side-menu__item has-link', 'active' => request()->routeIs('admin.user.*')])
                    data-bs-toggle="slide" href="{{ route('admin.user.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    <span class="side-menu__label">Users</span>
                </a>
            </div>
            @endif

        </div>
    </div>

    {{-- ─── MOBILE / TABLET: Offcanvas panel (opened from header toggle) ── --}}
    <div class="offcanvas offcanvas-start" id="mobile-sidebar" tabindex="-1" aria-hidden="true">
        <div class="offcanvas-header sidebar-offcanvas-header">
            <div class="d-flex align-items-center gap-2">
                {{-- <img src="{{ asset('images/logo.png') }}" alt="logo" width="42px" class="rounded-circle"> --}}
                <span style="display:inline-flex;align-items:center;justify-content:center;width:42px;height:42px;border-radius:50%;background:#FF9933;color:#fff;font-weight:800;font-size:1rem;flex-shrink:0;">F</span>
                <div>
                    <span class="sidebar-brand-name">{{ config('app.name', 'Admin') }}</span>
                    <small class="sidebar-brand-sub d-block">{{ auth()->user()->name ?? '' }}</small>
                </div>
            </div>
            <button class="sidebar-close-btn mt-2" data-bs-dismiss="offcanvas" type="button" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="pt-2 side-menu">

                <div class="slide animate-right px-2">
                    <a @class(['side-menu__item has-link', 'active' => request()->routeIs('admin.dashboard')])
                        data-bs-toggle="slide" href="{{ route('admin.dashboard') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1772 1772">
                            <path d="M384 1152q0-53-37.5-90.5T256 1024t-90.5 37.5T128 1152t37.5 90.5T256 1280t90.5-37.5T384 1152zm192-448q0-53-37.5-90.5T448 576t-90.5 37.5T320 704t37.5 90.5T448 832t90.5-37.5T576 704zm428 481 101-382q6-26-7.5-48.5T1059 725t-48 6.5-30 39.5l-101 382q-60 5-107 43.5t-63 98.5q-20 77 20 146t117 89 146-20 89-117q16-60-6-117t-72-91zm660-33q0-53-37.5-90.5T1536 1024t-90.5 37.5-37.5 90.5 37.5 90.5 90.5 37.5 90.5-37.5 37.5-90.5zm-640-640q0-53-37.5-90.5T896 384t-90.5 37.5T768 512t37.5 90.5T896 640t90.5-37.5T1024 512zm448 192q0-53-37.5-90.5T1344 576t-90.5 37.5T1216 704t37.5 90.5T1344 832t90.5-37.5T1472 704zm320 448q0 261-141 483-19 29-54 29H195q-35 0-54-29Q0 1414 0 1152q0-182 71-348t191-286 286-191 348-71 348 71 286 191 191 286 71 348z"
                                fill="currentColor"></path>
                        </svg>
                        <span class="side-menu__label">{{ __('portal.dashboard') }}</span>
                    </a>
                </div>

                <div class="slide animate-right px-2">
                    <a @class(['side-menu__item has-link', 'active' => request()->routeIs('admin.expense.*')])
                        data-bs-toggle="slide" href="{{ route('admin.expense.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 8.25H9m6 3H9m3 6-3-3h1.5a3 3 0 1 0 0-6M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span class="side-menu__label">{{ __('portal.expense') }}</span>
                    </a>
                </div>

                <div class="slide animate-right px-2">
                    <a @class(['side-menu__item has-link', 'active' => request()->routeIs('admin.customer.*')])
                        data-bs-toggle="slide" href="{{ route('admin.customer.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                        </svg>
                        <span class="side-menu__label">{{ __('portal.customers') }}</span>
                    </a>
                </div>

                <div class="slide animate-right px-2">
                    <a @class(['side-menu__item has-link', 'active' => request()->routeIs('admin.product.*')])
                        data-bs-toggle="slide" href="{{ route('admin.product.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                        </svg>
                        <span class="side-menu__label">{{ __('portal.products') }}</span>
                    </a>
                </div>

                <div class="slide animate-right px-2">
                    <a @class(['side-menu__item has-link', 'active' => request()->routeIs('admin.order.*')])
                        data-bs-toggle="slide" href="{{ route('admin.order.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                        <span class="side-menu__label">{{ __('portal.orders') }}</span>
                    </a>
                </div>

                <div class="slide animate-right px-2">
                    <a @class(['side-menu__item has-link', 'active' => request()->routeIs('admin.monthly-report.*')])
                        data-bs-toggle="slide" href="{{ route('admin.monthly-report.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <span class="side-menu__label">{{ __('portal.monthly-reports') }}</span>
                    </a>
                </div>

                @if(auth()->check() && auth()->user()->isSuperAdmin())
                <div class="slide animate-right px-2">
                    <a @class(['side-menu__item has-link', 'active' => request()->routeIs('admin.user.*')])
                        data-bs-toggle="slide" href="{{ route('admin.user.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <span class="side-menu__label">Users</span>
                    </a>
                </div>
                @endif

            </div>
        </div>
    </div>

</div>
