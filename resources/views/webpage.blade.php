<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />

  <title>FarsanHub – Smart Business Management for Farsan Shops</title>
  <meta name="description" content="FarsanHub is a complete business management portal for Farsan shops. Manage orders, products, customers, expenses, and reports — all in one place." />
  <meta name="keywords" content="FarsanHub, Farsan Business Software, Order Management, Customer Management, Expense Tracking, Gujarati Business Portal" />
  <meta name="author" content="FarsanHub" />

  <link rel="icon" type="image/svg+xml" href="{{ asset('images/farsan-favicon.svg') }}" />

  {{-- Google Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Noto+Sans+Gujarati:wght@400;500;600;700&display=swap" rel="stylesheet" />

  {{-- Bootstrap 5 --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  {{-- Bootstrap Icons --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  {{-- AOS --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />

  {{-- Custom CSS --}}
  <link rel="stylesheet" href="{{ asset('css/webpage.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/responsive.css') }}" />
</head>
<body>

{{-- ══════════════════ NAVBAR ══════════════════ --}}
<nav class="fh-navbar" id="fhNav" aria-label="Main navigation">
  <div class="container">
    <div class="fh-nav-inner">

      {{-- Brand --}}
      <a class="fh-brand" href="#hero" style="text-decoration:none;">
        <div class="brand-text-logo" style="font-size: 1.5rem; font-weight: 800; letter-spacing: -1px; line-height: 1.1; display: flex; flex-direction: column;">
            <div><span style="color: #FF9933;">Farsan</span><span style="color: #3d2200;">Hub</span></div>
            <div style="font-size: 0.65rem; color: #6c757d; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-top: 2px;">Business Portal</div>
        </div>
      </a>

      {{-- Desktop Nav --}}
      <ul class="fh-nav-links" id="fhNavLinks">
        <li><a href="#hero"     class="fh-nav-link">Home</a></li>
        <li><a href="#modules"  class="fh-nav-link">Features</a></li>
        <li><a href="#workflow" class="fh-nav-link">How It Works</a></li>
        <li><a href="#stats"    class="fh-nav-link">Why Us</a></li>
        <li><a href="#contact"  class="fh-nav-link">Contact</a></li>
      </ul>

      {{-- CTA --}}
      <div class="fh-nav-actions">
        <a href="{{ route('login') }}" class="fh-btn-login">
          <i class="bi bi-shield-lock-fill me-1"></i>Admin Login
        </a>
        <button class="fh-hamburger" id="fhHamburger" aria-label="Toggle menu" aria-expanded="false">
          <span></span><span></span><span></span>
        </button>
      </div>

    </div>
  </div>

  {{-- Mobile Menu --}}
  <div class="fh-mobile-menu" id="fhMobileMenu" aria-hidden="true">
    <ul>
      <li><a href="#hero"     class="fh-mobile-link">Home</a></li>
      <li><a href="#modules"  class="fh-mobile-link">Features</a></li>
      <li><a href="#workflow" class="fh-mobile-link">How It Works</a></li>
      <li><a href="#stats"    class="fh-mobile-link">Why Us</a></li>
      <li><a href="#contact"  class="fh-mobile-link">Contact</a></li>
      <li class="fh-mobile-cta">
        <a href="{{ route('login') }}" class="fh-btn-login w-100 justify-content-center">
          <i class="bi bi-shield-lock-fill me-2"></i>Admin Login
        </a>
      </li>
    </ul>
  </div>
</nav>

{{-- ══════════════════ HERO ══════════════════ --}}
<section id="hero" class="fh-hero" aria-label="Hero">

  {{-- Animated background particles --}}
  <div class="fh-hero-particles" aria-hidden="true">
    <span class="fh-particle" style="--x:10%;--y:20%;--d:6s;--s:4px"></span>
    <span class="fh-particle" style="--x:25%;--y:60%;--d:8s;--s:3px"></span>
    <span class="fh-particle" style="--x:50%;--y:15%;--d:5s;--s:5px"></span>
    <span class="fh-particle" style="--x:70%;--y:75%;--d:7s;--s:3px"></span>
    <span class="fh-particle" style="--x:85%;--y:35%;--d:9s;--s:4px"></span>
    <span class="fh-particle" style="--x:40%;--y:85%;--d:6.5s;--s:3px"></span>
    <span class="fh-particle" style="--x:92%;--y:10%;--d:8.5s;--s:5px"></span>
    <span class="fh-particle" style="--x:60%;--y:50%;--d:7.5s;--s:2px"></span>
  </div>

  {{-- Decorative orbs --}}
  <div class="fh-hero-orb fh-orb-1" aria-hidden="true"></div>
  <div class="fh-hero-orb fh-orb-2" aria-hidden="true"></div>
  <div class="fh-hero-orb fh-orb-3" aria-hidden="true"></div>
  <div class="fh-hero-grid" aria-hidden="true"></div>

  <div class="container position-relative" style="z-index:2">
    <div class="row align-items-center fh-hero-row">

      {{-- Text Column --}}
      <div class="col-lg-6 fh-hero-text" data-aos="fade-right" data-aos-duration="1000">

        <div class="fh-hero-badge">
          <span class="fh-badge-dot"></span>
          <i class="bi bi-stars me-1"></i>All-in-One Business Portal
        </div>

        <h1 class="fh-hero-title">
          Manage Your<br>
          <span class="fh-hero-gradient">Farsan Business</span><br>
          Smarter &amp; Faster
        </h1>

        <p class="fh-hero-sub">
          FarsanHub gives you complete control &mdash; track orders, manage products, monitor customers, analyse expenses and generate reports. Built for Farsan businesses, powered by simplicity.
        </p>

        <div class="fh-hero-stats-row">
          <div class="fh-hero-stat">
            <span class="fh-hs-num">1,240+</span>
            <span class="fh-hs-label">Orders</span>
          </div>
          <div class="fh-hero-stat-div"></div>
          <div class="fh-hero-stat">
            <span class="fh-hs-num">6</span>
            <span class="fh-hs-label">Modules</span>
          </div>
          <div class="fh-hero-stat-div"></div>
          <div class="fh-hero-stat">
            <span class="fh-hs-num">100%</span>
            <span class="fh-hs-label">Mobile Ready</span>
          </div>
        </div>

        <div class="fh-hero-cta">
          <a href="{{ route('login') }}" class="fh-btn-primary fh-btn-hero">
            <i class="bi bi-rocket-takeoff-fill me-2"></i>Open Portal
            <span class="fh-btn-shine"></span>
          </a>
          <a href="#modules" class="fh-btn-ghost">
            <i class="bi bi-play-circle-fill me-2"></i>Explore Features
          </a>
        </div>

        <div class="fh-hero-trust">
          <div class="fh-trust-item">
            <i class="bi bi-shield-check-fill"></i>
            <span>Secure Login</span>
          </div>
          <div class="fh-trust-item">
            <i class="bi bi-translate"></i>
            <span>Gujarati &amp; English</span>
          </div>
          <div class="fh-trust-item">
            <i class="bi bi-phone-fill"></i>
            <span>Mobile Ready</span>
          </div>
        </div>
      </div>

      {{-- Advanced Visual Column --}}
      <div class="col-lg-6 fh-hero-visual" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
        <div class="fh-hero-visual-wrap">

          <div class="fh-browser-mockup">
            <div class="fh-browser-bar">
              <div class="fh-browser-dots">
                <span class="fh-dot red"></span>
                <span class="fh-dot yellow"></span>
                <span class="fh-dot green"></span>
              </div>
              <div class="fh-browser-url">
                <i class="bi bi-lock-fill" style="color:#4ade80;font-size:.65rem"></i>
                farsan<strong>hub</strong>.com/admin
              </div>
              <div class="fh-browser-actions">
                <i class="bi bi-arrow-clockwise" style="opacity:.4"></i>
              </div>
            </div>
            <div class="fh-browser-content">
              <div class="fh-preview-sidebar">
                <div class="fh-psb-logo"><i class="bi bi-grid-fill"></i></div>
                <div class="fh-psb-item active"><i class="bi bi-speedometer2"></i></div>
                <div class="fh-psb-item"><i class="bi bi-cart3"></i></div>
                <div class="fh-psb-item"><i class="bi bi-box-seam"></i></div>
                <div class="fh-psb-item"><i class="bi bi-people"></i></div>
                <div class="fh-psb-item"><i class="bi bi-wallet2"></i></div>
                <div class="fh-psb-item"><i class="bi bi-bar-chart"></i></div>
              </div>
              <div class="fh-preview-main">
                <div class="fh-pm-header">
                  <span class="fh-pm-title">Dashboard</span>
                  <div class="fh-pm-filters">
                    <span class="fh-pmf active">Today</span>
                    <span class="fh-pmf">Week</span>
                    <span class="fh-pmf">Month</span>
                  </div>
                </div>
                <div class="fh-preview-stats">
                  <div class="fh-pstat fh-pstat-orange">
                    <i class="bi bi-cart-check-fill"></i>
                    <div><strong>1,240</strong><small>Orders</small></div>
                  </div>
                  <div class="fh-pstat fh-pstat-green">
                    <i class="bi bi-people-fill"></i>
                    <div><strong>380</strong><small>Customers</small></div>
                  </div>
                  <div class="fh-pstat fh-pstat-blue">
                    <i class="bi bi-box-seam-fill"></i>
                    <div><strong>24</strong><small>Products</small></div>
                  </div>
                  <div class="fh-pstat fh-pstat-purple">
                    <i class="bi bi-wallet2"></i>
                    <div><strong>&#8377;84K</strong><small>Revenue</small></div>
                  </div>
                </div>
                <div class="fh-preview-chart">
                  <div class="fh-chart-label">Monthly Revenue</div>
                  <div class="fh-chart-bars">
                    <div class="fh-bar" style="height:45%" data-month="Oct"></div>
                    <div class="fh-bar" style="height:60%" data-month="Nov"></div>
                    <div class="fh-bar" style="height:50%" data-month="Dec"></div>
                    <div class="fh-bar" style="height:75%" data-month="Jan"></div>
                    <div class="fh-bar" style="height:65%" data-month="Feb"></div>
                    <div class="fh-bar fh-bar-active" style="height:90%" data-month="Mar"></div>
                  </div>
                </div>
                <div class="fh-preview-list">
                  <div class="fh-list-hd">Recent Orders</div>
                  <div class="fh-list-row">
                    <span class="fh-list-dot fh-dot-sell"></span>
                    <span>Khandavi &mdash; 5 kg</span>
                    <span class="fh-list-badge sell">Sell</span>
                  </div>
                  <div class="fh-list-row">
                    <span class="fh-list-dot fh-dot-buy"></span>
                    <span>Samosa &mdash; 10 kg</span>
                    <span class="fh-list-badge buy">Purchase</span>
                  </div>
                  <div class="fh-list-row">
                    <span class="fh-list-dot fh-dot-sell"></span>
                    <span>Dhokla &mdash; 3 kg</span>
                    <span class="fh-list-badge sell">Sell</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="fh-float-notif fh-fn-1" data-aos="zoom-in" data-aos-delay="700">
            <i class="bi bi-graph-up-arrow" style="color:#4ade80;font-size:1.2rem"></i>
            <div>
              <strong>Revenue Up 24%</strong>
              <small>vs last month</small>
            </div>
          </div>
          <div class="fh-float-notif fh-fn-2" data-aos="zoom-in" data-aos-delay="900">
            <i class="bi bi-cart-check-fill" style="color:var(--saffron);font-size:1.2rem"></i>
            <div>
              <strong>New Order</strong>
              <small>Khandavi &middot; 5 kg &middot; &#8377;600</small>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>

  <a href="#modules" class="fh-scroll-hint" aria-label="Scroll down">
    <i class="bi bi-chevron-double-down"></i>
  </a>
</section>
</section>

{{-- ══════════════════ MODULES (SaaS Sidebar) ══════════════════ --}}
<section id="modules" class="fh-saas-modules" aria-labelledby="modules-heading">
  <div class="container">
    <div class="fh-section-head text-center mb-5" data-aos="fade-up">
      <span class="fh-section-badge">Platform Features</span>
      <h2 class="fh-section-title" id="modules-heading">
        Everything you need to run your<br><span class="fh-text-saffron">Farsan Business</span>
      </h2>
      <p class="fh-section-sub">Powerful, easy-to-use modules designed to give you total control over your daily operations and long-term growth.</p>
    </div>

    <div class="fh-modules-layout" data-aos="fade-up" data-aos-delay="100">
      
      {{-- Mobile Menu Toggle --}}
      <div class="d-lg-none mb-3">
        <label class="form-label text-muted small fw-bold text-uppercase">Select Module</label>
        <button class="fh-mobile-module-toggle" type="button" id="fhMobileModuleToggle" data-bs-toggle="offcanvas" data-bs-target="#modulesOffcanvas" aria-controls="modulesOffcanvas">
          <span id="fhMobileModuleActiveText"><i class="bi bi-speedometer2 me-2"></i>Dashboard</span>
          <i class="bi bi-chevron-down"></i>
        </button>
      </div>

      {{-- Desktop Sidebar --}}
      <div class="fh-modules-sidebar d-none d-lg-flex" id="fhModulesSidebar">
        <button class="fh-ms-btn active" data-target="mod-dashboard">
          <i class="bi bi-speedometer2"></i>
          <span>Dashboard</span>
        </button>
        <button class="fh-ms-btn" data-target="mod-orders">
          <i class="bi bi-cart3"></i>
          <span>Orders</span>
        </button>
        <button class="fh-ms-btn" data-target="mod-products">
          <i class="bi bi-box-seam"></i>
          <span>Products</span>
        </button>
        <button class="fh-ms-btn" data-target="mod-customers">
          <i class="bi bi-people"></i>
          <span>Customers</span>
        </button>
        <button class="fh-ms-btn" data-target="mod-expenses">
          <i class="bi bi-wallet2"></i>
          <span>Expenses</span>
        </button>
        <button class="fh-ms-btn" data-target="mod-reports">
          <i class="bi bi-bar-chart"></i>
          <span>Reports</span>
        </button>
      </div>

      {{-- Content Area --}}
      <div class="fh-modules-content">
        
        {{-- Dashboard --}}
        <div class="fh-mod-panel active" id="mod-dashboard">
          <div class="fh-mod-text">
            <h3>Real-Time Dashboard</h3>
            <p>Get a complete business snapshot at a glance. Track your revenue trends, top products, and top customers in real-time to make informed decisions.</p>
            <ul class="fh-mod-features">
              <li><i class="bi bi-check-circle-fill text-success"></i> Comprehensive revenue & order charts</li>
              <li><i class="bi bi-check-circle-fill text-success"></i> Dynamic period filters (Today, Week, Month)</li>
              <li><i class="bi bi-check-circle-fill text-success"></i> Instant performance metrics & top sellers</li>
            </ul>
          </div>
          <div class="fh-mod-visual">
            <div class="fh-browser-frame">
              <div class="fh-frame-header"><span class="red"></span><span class="yellow"></span><span class="green"></span></div>
              <img src="{{ asset('images/portal_dashboard.png') }}" alt="Dashboard" class="img-fluid" loading="lazy" />
            </div>
          </div>
        </div>

        {{-- Orders --}}
        <div class="fh-mod-panel" id="mod-orders">
          <div class="fh-mod-text">
            <h3>Smart Order Management</h3>
            <p>Effortlessly manage both sell and purchase orders. Streamline your operations and reduce manual errors with intelligent order workflows.</p>
            <ul class="fh-mod-features">
              <li><i class="bi bi-check-circle-fill text-success"></i> Smart duplicate order detection alerts</li>
              <li><i class="bi bi-check-circle-fill text-success"></i> Generate professional PDF Bills & Challans instantly</li>
              <li><i class="bi bi-check-circle-fill text-success"></i> Customer-specific pricing automatically applied</li>
            </ul>
          </div>
          <div class="fh-mod-visual">
            <div class="fh-browser-frame">
              <div class="fh-frame-header"><span class="red"></span><span class="yellow"></span><span class="green"></span></div>
              <img src="{{ asset('images/portal_orders.png') }}" alt="Orders" class="img-fluid" loading="lazy" />
            </div>
          </div>
        </div>

        {{-- Products --}}
        <div class="fh-mod-panel" id="mod-products">
          <div class="fh-mod-text">
            <h3>Product Catalogue</h3>
            <p>Maintain your complete product inventory with ease. Define base prices and tailor custom rates to reward your best customers.</p>
            <ul class="fh-mod-features">
              <li><i class="bi bi-check-circle-fill text-success"></i> Flexible unit support (kg / nang)</li>
              <li><i class="bi bi-check-circle-fill text-success"></i> Advanced customer-specific custom rates</li>
              <li><i class="bi bi-check-circle-fill text-success"></i> Quick product search and editing tools</li>
            </ul>
          </div>
          <div class="fh-mod-visual">
            <div class="fh-browser-frame">
              <div class="fh-frame-header"><span class="red"></span><span class="yellow"></span><span class="green"></span></div>
              <img src="{{ asset('images/portal_products.png') }}" alt="Products" class="img-fluid" loading="lazy" />
            </div>
          </div>
        </div>

        {{-- Customers --}}
        <div class="fh-mod-panel" id="mod-customers">
          <div class="fh-mod-text">
            <h3>Customer CRM & Map</h3>
            <p>Build a detailed customer database with shop names, contact numbers, and complete addresses. Visualize your market reach instantly.</p>
            <ul class="fh-mod-features">
              <li><i class="bi bi-check-circle-fill text-success"></i> Maintain comprehensive customer profiles</li>
              <li><i class="bi bi-check-circle-fill text-success"></i> Interactive location map for delivery planning</li>
              <li><i class="bi bi-check-circle-fill text-success"></i> Robust search, sort, and filtering capabilities</li>
            </ul>
          </div>
          <div class="fh-mod-visual">
            <div class="fh-browser-frame">
              <div class="fh-frame-header"><span class="red"></span><span class="yellow"></span><span class="green"></span></div>
              <img src="{{ asset('images/portal_customers.png') }}" alt="Customers" class="img-fluid" loading="lazy" />
            </div>
          </div>
        </div>

        {{-- Expenses --}}
        <div class="fh-mod-panel" id="mod-expenses">
          <div class="fh-mod-text">
            <h3>Expense Tracking</h3>
            <p>Keep a clear, organized record of where your money goes. Track your business and personal expenses separately to maintain clean accounting.</p>
            <ul class="fh-mod-features">
              <li><i class="bi bi-check-circle-fill text-success"></i> Distinct Business vs Personal expense splitting</li>
              <li><i class="bi bi-check-circle-fill text-success"></i> Intelligent monthly & category-based filtering</li>
              <li><i class="bi bi-check-circle-fill text-success"></i> Attach detailed notes & comments to every entry</li>
            </ul>
          </div>
          <div class="fh-mod-visual">
            <div class="fh-browser-frame">
              <div class="fh-frame-header"><span class="red"></span><span class="yellow"></span><span class="green"></span></div>
              <img src="{{ asset('images/portal_expenses.png') }}" alt="Expenses" class="img-fluid" loading="lazy" />
            </div>
          </div>
        </div>

        {{-- Reports --}}
        <div class="fh-mod-panel" id="mod-reports">
          <div class="fh-mod-text">
            <h3>Comprehensive Reports</h3>
            <p>Generate precise, formatted reports for your daily and monthly operations. Export everything for offline analysis or CA submission.</p>
            <ul class="fh-mod-features">
              <li><i class="bi bi-check-circle-fill text-success"></i> 4 distinct, detailed report types (Orders, etc.)</li>
              <li><i class="bi bi-check-circle-fill text-success"></i> One-click Excel (XLSX) export functionality</li>
              <li><i class="bi bi-check-circle-fill text-success"></i> Custom date range selection for targeted data</li>
            </ul>
          </div>
          <div class="fh-mod-visual">
            <div class="fh-browser-frame">
              <div class="fh-frame-header"><span class="red"></span><span class="yellow"></span><span class="green"></span></div>
              <img src="{{ asset('images/portal_reports.png') }}" alt="Reports" class="img-fluid" loading="lazy" />
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

{{-- Mobile Offcanvas Menu for Modules --}}
<div class="offcanvas offcanvas-bottom fh-modules-offcanvas" tabindex="-1" id="modulesOffcanvas" aria-labelledby="modulesOffcanvasLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title fw-bold" id="modulesOffcanvasLabel">Select a Module</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body small">
    <div class="list-group list-group-flush fh-oc-modules-list" id="fhMobileModulesList">
      <button class="list-group-item list-group-item-action active" data-target="mod-dashboard" data-bs-dismiss="offcanvas">
        <i class="bi bi-speedometer2 me-3 fs-5"></i> Dashboard
      </button>
      <button class="list-group-item list-group-item-action" data-target="mod-orders" data-bs-dismiss="offcanvas">
        <i class="bi bi-cart3 me-3 fs-5"></i> Orders
      </button>
      <button class="list-group-item list-group-item-action" data-target="mod-products" data-bs-dismiss="offcanvas">
        <i class="bi bi-box-seam me-3 fs-5"></i> Products
      </button>
      <button class="list-group-item list-group-item-action" data-target="mod-customers" data-bs-dismiss="offcanvas">
        <i class="bi bi-people me-3 fs-5"></i> Customers
      </button>
      <button class="list-group-item list-group-item-action" data-target="mod-expenses" data-bs-dismiss="offcanvas">
        <i class="bi bi-wallet2 me-3 fs-5"></i> Expenses
      </button>
      <button class="list-group-item list-group-item-action" data-target="mod-reports" data-bs-dismiss="offcanvas">
        <i class="bi bi-bar-chart me-3 fs-5"></i> Reports
      </button>
    </div>
  </div>
</div>


{{-- ══════════════════ STATS ══════════════════ --}}
<section id="stats" class="fh-stats-section" aria-label="Statistics">
  <div class="fh-stats-bg" aria-hidden="true"></div>
  <div class="container position-relative">
    <div class="fh-section-head text-center mb-5" data-aos="fade-up">
      <span class="fh-section-badge light">Why FarsanHub</span>
      <h2 class="fh-section-title text-white">Built For the Way<br><span class="fh-text-yellow">You Do Business</span></h2>
    </div>
    <div class="row g-4">
      <div class="col-lg-3 col-sm-6" data-aos="zoom-in" data-aos-delay="0">
        <div class="fh-stat-card">
          <div class="fh-stat-icon"><i class="bi bi-lightning-charge-fill"></i></div>
          <div class="fh-stat-num" data-target="6">0</div>
          <div class="fh-stat-plus">+</div>
          <div class="fh-stat-label">Powerful Modules</div>
          <p class="fh-stat-desc">Dashboard, Orders, Products, Customers, Expenses & Reports.</p>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6" data-aos="zoom-in" data-aos-delay="80">
        <div class="fh-stat-card">
          <div class="fh-stat-icon"><i class="bi bi-translate"></i></div>
          <div class="fh-stat-num" data-target="2">0</div>
          <div class="fh-stat-plus"></div>
          <div class="fh-stat-label">Languages</div>
          <p class="fh-stat-desc">Full English & Gujarati support with instant language switching.</p>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6" data-aos="zoom-in" data-aos-delay="160">
        <div class="fh-stat-card">
          <div class="fh-stat-icon"><i class="bi bi-phone-fill"></i></div>
          <div class="fh-stat-num" data-target="100">0</div>
          <div class="fh-stat-plus">%</div>
          <div class="fh-stat-label">Mobile Responsive</div>
          <p class="fh-stat-desc">Use it on any device — phone, tablet or desktop seamlessly.</p>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6" data-aos="zoom-in" data-aos-delay="240">
        <div class="fh-stat-card">
          <div class="fh-stat-icon"><i class="bi bi-cloud-check-fill"></i></div>
          <div class="fh-stat-num" data-target="24">0</div>
          <div class="fh-stat-plus">/7</div>
          <div class="fh-stat-label">Always Available</div>
          <p class="fh-stat-desc">Access your data anytime from anywhere with a secure login.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ══════════════════ HOW IT WORKS ══════════════════ --}}
<section id="workflow" class="fh-section fh-workflow-section" aria-labelledby="workflow-heading">
  <div class="container">
    <div class="fh-section-head text-center" data-aos="fade-up">
      <span class="fh-section-badge">Simple Process</span>
      <h2 class="fh-section-title" id="workflow-heading">
        Up & Running in<br><span class="fh-text-saffron">3 Simple Steps</span>
      </h2>
    </div>
    <div class="row g-5 align-items-center mt-2">

      <div class="col-lg-5" data-aos="fade-right">
        <div class="fh-workflow-steps">

          <div class="fh-step" id="wfStep1">
            <div class="fh-step-num">01</div>
            <div class="fh-step-body">
              <h4>Login to Your Portal</h4>
              <p>Sign in securely with your admin credentials or Google account. Your dashboard loads instantly with a full business overview.</p>
            </div>
          </div>

          <div class="fh-step-connector" aria-hidden="true"></div>

          <div class="fh-step" id="wfStep2">
            <div class="fh-step-num">02</div>
            <div class="fh-step-body">
              <h4>Add Products & Customers</h4>
              <p>Build your product catalogue with pricing, then add your customers with their shop details, location and custom product rates.</p>
            </div>
          </div>

          <div class="fh-step-connector" aria-hidden="true"></div>

          <div class="fh-step" id="wfStep3">
            <div class="fh-step-num">03</div>
            <div class="fh-step-body">
              <h4>Manage Orders & Reports</h4>
              <p>Record daily sell and purchase orders, track expenses, and generate monthly reports. Export to Excel whenever you need records.</p>
            </div>
          </div>

        </div>
      </div>

      <div class="col-lg-7" data-aos="fade-left" data-aos-delay="100">
        <div class="fh-workflow-cards">
          <div class="fh-wf-card fh-wfc-login">
            <div class="fh-wfc-icon"><i class="bi bi-shield-lock-fill"></i></div>
            <div>
              <strong>Secure Login</strong>
              <p>Email / Google OAuth</p>
            </div>
          </div>
          <div class="fh-wf-card fh-wfc-product">
            <div class="fh-wfc-icon"><i class="bi bi-box-seam-fill"></i></div>
            <div>
              <strong>Khandavi</strong>
              <p>₹120/kg · 3 customer rates</p>
            </div>
          </div>
          <div class="fh-wf-card fh-wfc-customer">
            <div class="fh-wfc-icon"><i class="bi bi-person-circle"></i></div>
            <div>
              <strong>Rameshbhai Patel</strong>
              <p>Shubhanpura, Vadodara</p>
            </div>
          </div>
          <div class="fh-wf-card fh-wfc-order">
            <div class="fh-wfc-icon"><i class="bi bi-cart-check-fill"></i></div>
            <div>
              <strong>Order #1042</strong>
              <p>Samosa · 8 kg · Sell · ₹960</p>
            </div>
          </div>
          <div class="fh-wf-card fh-wfc-report">
            <div class="fh-wfc-icon"><i class="bi bi-file-earmark-spreadsheet-fill"></i></div>
            <div>
              <strong>March Report</strong>
              <p>Exported to Excel ✓</p>
            </div>
          </div>
          <div class="fh-wf-card fh-wfc-expense">
            <div class="fh-wfc-icon"><i class="bi bi-wallet2"></i></div>
            <div>
              <strong>Business Expense</strong>
              <p>Packaging Material · ₹2,400</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- ══════════════════ HIGHLIGHTS ══════════════════ --}}
<section class="fh-section fh-highlights-section" aria-label="Key highlights">
  <div class="container">
    <div class="fh-section-head text-center mb-5" data-aos="fade-up">
      <span class="fh-section-badge">Platform Highlights</span>
      <h2 class="fh-section-title">
        Everything You Need,<br><span class="fh-text-saffron">Nothing You Don't</span>
      </h2>
    </div>
    <div class="row g-4">

      <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
        <div class="fh-highlight-card">
          <div class="fh-hl-icon"><i class="bi bi-filetype-pdf"></i></div>
          <h5>PDF Bill & Challan</h5>
          <p>Generate professional PDF bills and order challans directly from the orders module. Print or share with customers instantly.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="60">
        <div class="fh-highlight-card">
          <div class="fh-hl-icon"><i class="bi bi-geo-alt-fill"></i></div>
          <h5>Customer Map View</h5>
          <p>See all your customers plotted on an interactive map. Understand your delivery zones and customer distribution at a glance.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="120">
        <div class="fh-highlight-card">
          <div class="fh-hl-icon"><i class="bi bi-arrow-repeat"></i></div>
          <h5>Duplicate Order Check</h5>
          <p>Smart detection alerts you before placing a duplicate order for the same customer and product on the same day.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
        <div class="fh-highlight-card">
          <div class="fh-hl-icon"><i class="bi bi-tag-fill"></i></div>
          <h5>Customer-Specific Pricing</h5>
          <p>Set different prices for different customers per product. FarsanHub automatically applies the right price when creating orders.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="60">
        <div class="fh-highlight-card">
          <div class="fh-hl-icon"><i class="bi bi-google"></i></div>
          <h5>Google Login</h5>
          <p>Sign in with your Google account for fast, secure access. No extra passwords to remember — one click and you're in.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="120">
        <div class="fh-highlight-card">
          <div class="fh-hl-icon"><i class="bi bi-file-earmark-excel-fill"></i></div>
          <h5>Excel Export</h5>
          <p>Export orders, customers, products and expenses to Excel (XLSX) format for offline analysis, accounting or sharing with your CA.</p>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- ══════════════════ CTA BAND ══════════════════ --}}
<section class="fh-cta-section" aria-label="Call to action">
  <div class="fh-cta-bg" aria-hidden="true"></div>
  <div class="container position-relative text-center" data-aos="zoom-in">
    <div class="fh-cta-icon"><i class="bi bi-rocket-takeoff-fill"></i></div>
    <h2 class="fh-cta-title">Ready to Take Control of<br>Your Farsan Business?</h2>
    <p class="fh-cta-sub">Login to your admin portal and start managing orders, products, customers and more — right now.</p>
    <div class="fh-cta-btns">
      <a href="{{ route('login') }}" class="fh-btn-primary fh-btn-lg">
        <i class="bi bi-shield-lock-fill me-2"></i>Open Admin Portal
      </a>
      <a href="https://wa.me/919574659456?text=Hello%2C%20I%20want%20to%20know%20more%20about%20FarsanHub" class="fh-btn-whatsapp fh-btn-lg" target="_blank" rel="noopener noreferrer">
        <i class="bi bi-whatsapp me-2"></i>Chat on WhatsApp
      </a>
    </div>
  </div>
</section>

{{-- ══════════════════ CONTACT ══════════════════ --}}
<section id="contact" class="fh-section fh-contact-section" aria-labelledby="contact-heading">
  <div class="container">
    <div class="fh-section-head text-center" data-aos="fade-up">
      <span class="fh-section-badge">Get In Touch</span>
      <h2 class="fh-section-title" id="contact-heading">
        Need Help or<br><span class="fh-text-saffron">Have a Question?</span>
      </h2>
    </div>
    <div class="row g-5 mt-2 justify-content-center">

      <div class="col-lg-5" data-aos="fade-right">
        <div class="fh-contact-info">
          <div class="fh-ci-item">
            <div class="fh-ci-icon"><i class="bi bi-whatsapp"></i></div>
            <div>
              <strong>WhatsApp Support</strong>
              <p>Chat with us directly for quick help with your portal.</p>
              <a href="https://wa.me/919574659456" class="fh-wa-link" target="_blank" rel="noopener noreferrer">
                +91 95746 59456
              </a>
            </div>
          </div>
          <div class="fh-ci-item">
            <div class="fh-ci-icon"><i class="bi bi-geo-alt-fill"></i></div>
            <div>
              <strong>Based In</strong>
              <p>Shubhanpura, Vadodara, Gujarat – 390023</p>
            </div>
          </div>
          <div class="fh-ci-item">
            <div class="fh-ci-icon"><i class="bi bi-clock-fill"></i></div>
            <div>
              <strong>Support Hours</strong>
              <p>Mon–Sat: 9:00 AM – 7:00 PM</p>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6" data-aos="fade-left">
        <div class="fh-contact-form-card">
          <h4 class="mb-4">Send a Message</h4>
          <form id="fhContactForm" novalidate>
            <input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off" aria-hidden="true" />
            <div class="row g-3">
              <div class="col-sm-6">
                <label class="fh-form-label">Your Name *</label>
                <input type="text" class="fh-form-control" id="fhName" name="name" required minlength="2" maxlength="60" placeholder="Rameshbhai Patel" />
                <div class="fh-invalid">Please enter your name.</div>
              </div>
              <div class="col-sm-6">
                <label class="fh-form-label">Phone Number *</label>
                <input type="tel" class="fh-form-control" id="fhPhone" name="phone" required pattern="[0-9]{10}" maxlength="10" placeholder="9876543210" />
                <div class="fh-invalid">Enter a valid 10-digit number.</div>
              </div>
              <div class="col-12">
                <label class="fh-form-label">Query Type</label>
                <select class="fh-form-control fh-form-select" id="fhSubject" name="subject">
                  <option value="">Select query type...</option>
                  <option value="portal">Portal Access / Login</option>
                  <option value="feature">Feature Question</option>
                  <option value="order">Order Module Help</option>
                  <option value="report">Report & Export Help</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <div class="col-12">
                <label class="fh-form-label">Message *</label>
                <textarea class="fh-form-control" id="fhMessage" name="message" rows="4" required minlength="10" maxlength="500" placeholder="Describe your question or issue..."></textarea>
                <div class="fh-invalid">Please enter a message (min 10 characters).</div>
              </div>
              <div class="col-12">
                <button type="submit" class="fh-btn-primary w-100 fh-btn-lg" id="fhSubmitBtn">
                  <span class="fh-btn-text"><i class="bi bi-send-fill me-2"></i>Send via WhatsApp</span>
                  <span class="fh-btn-loading d-none">
                    <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Sending…
                  </span>
                </button>
              </div>
              <div class="col-12">
                <div id="fhFormMsg" class="fh-form-msg d-none" role="alert" aria-live="polite"></div>
              </div>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- ══════════════════ FOOTER ══════════════════ --}}
<footer class="fh-footer" aria-label="Site footer">
  <div class="fh-footer-top">
    <div class="container">
      <div class="row g-5">

        <div class="col-lg-4 col-md-6">
          <div class="fh-footer-brand mb-3">
            <div class="brand-text-logo" style="font-size: 1.8rem; font-weight: 800; letter-spacing: -1px; line-height: 1;">
                <span style="color: #FF9933;">Farsan</span><span style="color: white;">Hub</span>
            </div>
            <div style="margin-top: 5px;">
              <small>Smart Business Portal</small>
            </div>
          </div>
          <p class="fh-footer-desc">A complete business management portal built for Farsan shops in Gujarat. Manage orders, customers, products, expenses and reports — all in one place.</p>
          <div class="fh-footer-socials">
            <a href="https://wa.me/919574659456" class="fh-social-btn" aria-label="WhatsApp" target="_blank" rel="noopener noreferrer"><i class="bi bi-whatsapp"></i></a>
            <a href="#" class="fh-social-btn" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" class="fh-social-btn" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 col-6">
          <h5 class="fh-footer-heading">Navigation</h5>
          <ul class="fh-footer-list">
            <li><a href="#hero">Home</a></li>
            <li><a href="#modules">Features</a></li>
            <li><a href="#workflow">How It Works</a></li>
            <li><a href="#stats">Why Us</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-3 col-6">
          <h5 class="fh-footer-heading">Modules</h5>
          <ul class="fh-footer-list">
            <li><a href="#modules">Dashboard</a></li>
            <li><a href="#modules">Orders</a></li>
            <li><a href="#modules">Products</a></li>
            <li><a href="#modules">Customers</a></li>
            <li><a href="#modules">Expenses</a></li>
            <li><a href="#modules">Reports</a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-6">
          <h5 class="fh-footer-heading">Admin Portal</h5>
          <p class="fh-footer-desc" style="font-size:.85rem;">Ready to manage your business? Log in to your secure admin portal.</p>
          <a href="{{ route('login') }}" class="fh-btn-login mt-2 d-inline-flex">
            <i class="bi bi-shield-lock-fill me-2"></i>Admin Login
          </a>
          <div class="mt-3">
            <a href="https://wa.me/919574659456" class="fh-wa-inline" target="_blank" rel="noopener noreferrer">
              <i class="bi bi-whatsapp me-1"></i>+91 95746 59456
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>
  <div class="fh-footer-bottom">
    <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
      <p class="mb-0">&copy; <span id="fhYear"></span> FarsanHub. All Rights Reserved.</p>
      <p class="mb-0">Made with <i class="bi bi-heart-fill" style="color:#FF9933"></i> in Vadodara, Gujarat</p>
    </div>
  </div>
</footer>

{{-- ══════════════════ FLOATING BUTTONS ══════════════════ --}}
<a href="https://wa.me/919574659456?text=Hello%2C%20I%20need%20help%20with%20FarsanHub" class="fh-float-wa" target="_blank" rel="noopener noreferrer" aria-label="Chat on WhatsApp">
  <i class="bi bi-whatsapp"></i>
  <span class="fh-float-wa-pulse"></span>
</a>
<button class="fh-back-top" id="fhBackTop" aria-label="Back to top">
  <i class="bi bi-chevron-up"></i>
</button>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js" defer></script>
<script src="{{ asset('js/webpage-main.js') }}" defer></script>

</body>
</html>
