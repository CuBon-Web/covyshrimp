<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content="Đặt hàng thành công" />
    <title>Đặt hàng thành công</title>
    <link rel="shortcut icon" href="{{ $setting->favicon }}" type="image/x-icon" />
    <style>
        :root {
            --success: #1f9d55;
            --success-soft: #eaf8f0;
            --primary: #1435c3;
            --text-main: #1f2937;
            --text-sub: #6b7280;
            --line: #e5e7eb;
            --bg: #f3f6fb;
            --white: #ffffff;
            --shadow: 0 20px 45px rgba(15, 23, 42, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            color: var(--text-main);
            background: radial-gradient(circle at top right, #dbe7ff 0%, var(--bg) 40%, #eef2f8 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .success-card {
            width: 100%;
            max-width: 760px;
            background: var(--white);
            border: 1px solid var(--line);
            border-radius: 24px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .success-header {
            padding: 20px 28px;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(180deg, #ffffff 0%, #fbfcff 100%);
        }

        .success-header img {
            max-height: 46px;
            width: auto;
            object-fit: contain;
        }

        .badge-success {
            font-size: 13px;
            font-weight: 700;
            color: var(--success);
            background: var(--success-soft);
            border: 1px solid #bce8cf;
            padding: 6px 12px;
            border-radius: 999px;
        }

        .success-body {
            padding: 34px 28px 28px;
            text-align: center;
        }

        .icon-wrap {
            width: 92px;
            height: 92px;
            margin: 0 auto 18px;
            border-radius: 50%;
            background: var(--success-soft);
            border: 1px solid #bce8cf;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-wrap svg {
            width: 54px;
            height: 54px;
        }

        h1 {
            margin: 0;
            font-size: 32px;
            line-height: 1.25;
            letter-spacing: -0.02em;
        }

        .subtitle {
            margin: 10px auto 0;
            max-width: 520px;
            color: var(--text-sub);
            font-size: 16px;
            line-height: 1.65;
        }

        .info-strip {
            margin: 24px auto 0;
            max-width: 560px;
            border: 1px dashed #cfd8e3;
            border-radius: 14px;
            background: #fafcff;
            padding: 12px 14px;
            color: #475569;
            font-size: 14px;
        }

        .actions {
            margin-top: 28px;
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            min-width: 180px;
            height: 46px;
            border-radius: 12px;
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all .2s ease;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 10px 24px rgba(20, 53, 195, 0.28);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(20, 53, 195, 0.3);
        }

        .btn-outline {
            background: #fff;
            color: var(--text-main);
            border-color: var(--line);
        }

        .btn-outline:hover {
            border-color: #c6cfdd;
            background: #f8fafc;
        }

        .footer-note {
            margin-top: 18px;
            color: #94a3b8;
            font-size: 13px;
        }

        @media (max-width: 640px) {
            body {
                padding: 14px;
            }

            .success-card {
                border-radius: 18px;
            }

            .success-header,
            .success-body {
                padding-left: 16px;
                padding-right: 16px;
            }

            h1 {
                font-size: 25px;
            }

            .subtitle {
                font-size: 15px;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <section class="success-card">
        <header class="success-header">
            <a href="{{ route('home') }}">
                <img src="{{ $setting->logo }}" alt="{{ config('app.name') }}">
            </a>
            <span class="badge-success">ORDER SUCCESS</span>
        </header>

        <div class="success-body">
            <div class="icon-wrap">
                <svg viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="11" stroke="#1f9d55" stroke-width="1.5"></circle>
                    <path d="M7 12.5L10.2 15.7L17 8.8" stroke="#1f9d55" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>

            <h1>Đặt hàng thành công</h1>
            <p class="subtitle">
                Cảm ơn bạn đã tin tưởng và đặt hàng tại cửa hàng của chúng tôi.
                Đơn hàng đã được ghi nhận, bộ phận chăm sóc khách hàng sẽ liên hệ xác nhận trong thời gian sớm nhất.
            </p>

            <div class="info-strip">
                Bạn có thể theo dõi đơn hàng trong mục tài khoản hoặc kiểm tra email/số điện thoại để nhận thông tin cập nhật.
            </div>

            <div class="actions">
                <a href="{{ route('home') }}" class="btn btn-primary">Tiếp tục mua hàng</a>
                <button type="button" onclick="window.print()" class="btn btn-outline">In hóa đơn</button>
            </div>

            <div class="footer-note">Chúc bạn một ngày mua sắm vui vẻ.</div>
        </div>
    </section>
</body>
</html>