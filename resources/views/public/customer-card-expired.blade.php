<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Expired — FarsanHub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Roboto', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background:
                radial-gradient(ellipse at 0% 0%, rgba(255,153,51,.15) 0%, transparent 55%),
                radial-gradient(ellipse at 100% 100%, rgba(92,58,33,.1) 0%, transparent 55%),
                #FFF7EE;
        }
        .brand-bar {
            background: linear-gradient(135deg,#5C3A21 0%,#7a4e2d 100%);
            padding: 12px 20px;
        }
        .brand-bar .brand-name { color: #FF9933; font-weight: 800; font-size: 18px; }
        .brand-bar .brand-tagline { color: rgba(255,255,255,.5); font-size: 11px; margin-top: 2px; }

        .center-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
        }
        .expired-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(92,58,33,.14), 0 0 0 1px rgba(255,153,51,.12);
            padding: 40px 32px;
            text-align: center;
            max-width: 380px;
            width: 100%;
        }
        .icon-circle {
            width: 80px; height: 80px;
            border-radius: 50%;
            background: rgba(220,53,69,.1);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
        }
        .icon-circle i { font-size: 36px; color: #dc3545; }
        h1 { font-size: 22px; font-weight: 800; color: #5C3A21; margin-bottom: 10px; }
        p { font-size: 14px; color: #888; line-height: 1.6; margin-bottom: 0; }
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,153,51,.3), transparent);
            margin: 24px 0;
        }
        .info-note {
            background: rgba(255,153,51,.08);
            border: 1px solid rgba(255,153,51,.2);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 13px;
            color: #e07a1a;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            text-align: left;
        }
        .info-note i { font-size: 18px; flex-shrink: 0; }
    </style>
</head>
<body>
    <div class="brand-bar">
        <div class="brand-name">FarsanHub</div>
        <div class="brand-tagline">Customer Management System</div>
    </div>

    <div class="center-wrapper">
        <div class="expired-card">
            <div class="icon-circle">
                <i class="fa fa-clock-o"></i>
            </div>
            <h1>Link Expired</h1>
            <p>This customer profile link has expired or is no longer valid. Share links are active for <strong>10 minutes</strong> from the time they are generated.</p>
            <div class="divider"></div>
            <div class="info-note">
                <i class="fa fa-info-circle"></i>
                Please ask the sender to share a new link from the FarsanHub dashboard.
            </div>
        </div>
    </div>
</body>
</html>
