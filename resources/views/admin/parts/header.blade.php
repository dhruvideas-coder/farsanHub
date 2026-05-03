<div class="d-flex align-items-center gap-2 animate-left">

    {{-- Mobile/Tablet: hamburger toggle (hidden on desktop) --}}
    <button class="sidebar-toggle-btn d-flex d-lg-none align-items-center justify-content-center flex-shrink-0"
        type="button" data-bs-toggle="offcanvas" data-bs-target="#mobile-sidebar"
        aria-controls="mobile-sidebar" aria-label="Open navigation">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="6" x2="21" y2="6" />
            <line x1="3" y1="12" x2="21" y2="12" />
            <line x1="3" y1="18" x2="21" y2="18" />
        </svg>
    </button>

    {{-- Desktop spacer (keeps right-side content aligned) --}}
    <span class="d-none d-lg-block flex-grow-1"></span>

    {{-- Right side: language selector + profile (half-half on mobile) --}}
    <div class="d-flex align-items-center justify-content-end header-actions gap-2">

        <div class="lang-wrapper">
            <select class="form-select form-select-sm" onchange="handleLanguageChange(this)" id="languageSelect" 
                style="border-radius: 20px; padding-left: 15px; padding-right: 35px; cursor: pointer; border-color: #eee; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>
                    English
                </option>
                <option value="gu" {{ app()->getLocale() == 'gu' ? 'selected' : '' }}>
                    ગુજરાતી
                </option>
            </select>
        </div>

        <div class="dropdown d-flex profile-1 justify-content-end">
            <a href="{{ route('admin.dashboard') }}" data-bs-toggle="dropdown"
                class="leading-none nav-link pe-0 d-flex align-items-center justify-content-end animate">
                <div class="avatar profile-user brround flex-shrink-0 order-2 order-lg-1 d-flex align-items-center justify-content-center shadow-sm"
                     style="background-color:#FF9933; color:white; font-weight:800; font-size:16px; letter-spacing:-0.5px;">
                     @php
                         $name = auth()->user()->name;
                         $words = explode(' ', $name);
                         $initials = '';
                         foreach ($words as $word) {
                             $initials .= strtoupper(substr($word, 0, 1));
                         }
                         echo $initials;
                     @endphp
                </div>
                <div class="p-1 text-center d-flex overflow-hidden order-1 order-lg-2">
                    <h6 class="mb-0 ms-1 text-truncate" id="profile-heading">
                        {{ auth()->user()->name }}
                        <i class="user-angle ms-1 fa fa-angle-down"></i>
                    </h6>
                </div>
            </a>

            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow dropdown">
                <a href="{{ route('admin.changePassword') }}" class="dropdown-item">
                    <i class="fa fa-lock me-3"></i>
                    {{ __('portal.change_password') }}
                </a>
                <a href="{{ route('logout') }}" class="dropdown-item"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out me-3"></i>
                    {{ __('portal.logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        </div>

    </div>

</div>
