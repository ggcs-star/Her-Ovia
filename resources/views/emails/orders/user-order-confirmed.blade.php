<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order Confirmed | Her Ovia</title>

    <style>
        /* reset + base */
        body {
            margin: 0;
            padding: 0;
            background-color: #f9f6f2;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #1e1e2a;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 28px;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            padding: 40px 35px 45px;
            transition: all 0.2s;
        }

        /* header */
        .brand-header {
            text-align: center;
            border-bottom: 1px solid #eee8e0;
            padding-bottom: 20px;
            margin-bottom: 28px;
        }

        .brand-header h1 {
            font-size: 28px;
            font-weight: 400;
            letter-spacing: 2px;
            margin: 0;
            color: #2c2c3a;
        }

        .brand-header h1 span {
            font-weight: 600;
            color: #b48b6b;
        }

        .brand-header .tagline {
            font-size: 13px;
            color: #9b8b7c;
            letter-spacing: 1px;
            margin-top: 4px;
        }

        /* confirmation icon */
        .confirm-badge {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #f4efe9;
            padding: 14px 24px;
            border-radius: 60px;
            margin: 0 auto 25px;
            width: fit-content;
        }

        .confirm-badge svg {
            width: 28px;
            height: 28px;
            fill: none;
            stroke: #2b7a4b;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .confirm-badge strong {
            font-size: 18px;
            font-weight: 500;
            color: #1e3a2a;
        }

        /* greeting */
        .greeting {
            font-size: 18px;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .greeting strong {
            color: #2c2c3a;
        }

        .intro-text {
            color: #3d3d4a;
            margin-top: 0;
            margin-bottom: 26px;
        }

        /* order card */
        .order-card {
            background: #fcfaf7;
            border-radius: 22px;
            padding: 24px 28px;
            margin: 28px 0 30px;
            border: 1px solid #ede7df;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        }

        .order-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed #e5ddd4;
        }

        .order-row:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .order-row .label {
            font-weight: 500;
            color: #5f5548;
            letter-spacing: 0.2px;
        }

        .order-row .value {
            font-weight: 600;
            color: #1e1e2a;
            text-align: right;
        }

        .order-row .value.highlight {
            color: #9d6b4b;
            font-size: 18px;
        }

        /* status chip */
        .status-chip {
            background: #e0eee5;
            color: #1e5e3a;
            padding: 6px 18px;
            border-radius: 40px;
            font-size: 14px;
            font-weight: 600;
            display: inline-block;
            letter-spacing: 0.3px;
            margin-top: 4px;
        }

        /* message area */
        .message-box {
            background: #faf6f1;
            padding: 20px 24px;
            border-radius: 20px;
            margin: 28px 0 22px;
            border-left: 4px solid #b48b6b;
        }

        .message-box p {
            margin: 6px 0;
            color: #2e2e3a;
        }

        .message-box .highlight-text {
            font-weight: 500;
            color: #6d4c34;
        }

        /* button */
        .btn-track {
            display: inline-block;
            background: #1e1e2a;
            color: #ffffff;
            padding: 14px 38px;
            border-radius: 60px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            letter-spacing: 0.5px;
            transition: 0.15s;
            margin: 8px 0 12px;
            border: 1px solid #1e1e2a;
        }

        .btn-track:hover {
            background: #2c2c3e;
            border-color: #2c2c3e;
        }

        .btn-track i {
            margin-right: 8px;
        }

        /* footer */
        .footer-note {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 1px solid #eee6dd;
            text-align: center;
            color: #7b6f62;
            font-size: 14px;
        }

        .footer-note strong {
            color: #3d332a;
            font-weight: 600;
        }

        .footer-note .small-links {
            margin-top: 12px;
            font-size: 13px;
        }

        .footer-note .small-links a {
            color: #7b6f62;
            text-decoration: none;
            margin: 0 10px;
            border-bottom: 1px dotted #d4c9bd;
        }

        .footer-note .small-links a:hover {
            color: #3d332a;
            border-bottom: 1px solid #3d332a;
        }

        /* responsive */
        @media (max-width: 550px) {
            .email-wrapper {
                padding: 30px 20px;
                margin: 15px;
                border-radius: 20px;
            }

            .order-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 2px;
                padding: 12px 0;
            }

            .order-row .value {
                text-align: left;
                width: 100%;
            }

            .brand-header h1 {
                font-size: 24px;
            }

            .btn-track {
                display: block;
                text-align: center;
            }
        }

        .order-id-small {
            font-size: 14px;
            color: #6b6257;
            font-weight: 400;
        }
    </style>
</head>

<body>

<div class="email-wrapper">

    <!-- Brand header -->
    <div class="brand-header">
        <h1>✨ <span>Her</span> Ovia</h1>
        <div class="tagline">timeless elegance, crafted for you</div>
    </div>

    <!-- Confirmed badge -->
    <div class="confirm-badge">
        <svg viewBox="0 0 24 24" width="28" height="28">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <polyline points="22 4 12 14.01 9 11.01" />
        </svg>

        <strong>Order confirmed</strong>
    </div>

    <!-- Greeting -->
    <p class="greeting">
        Hello
        <strong>
            {{ $order->user->name ?? $order->name ?? 'Valued Customer' }}
        </strong>,
    </p>

    <p class="intro-text">
        Thank you for your purchase. Your order has been confirmed successfully.
        We're delighted to have you with us.
    </p>

    <!-- Order details card -->
    <div class="order-card">

        <div class="order-row">
            <span class="label">📦 Order number</span>
            <span class="value">
                {{ $order->order_number ?? 'MHR-2026-001' }}
            </span>
        </div>

        <div class="order-row">
            <span class="label">🆔 Order ID</span>
            <span class="value order-id-small">
                #{{ $order->id ?? '1289' }}
            </span>
        </div>

        <div class="order-row">
            <span class="label">📅 Order date</span>
            <span class="value">
                {{ isset($order->created_at)
                    ? $order->created_at->format('d M Y h:i A')
                    : '30 June 2026 02:30 PM'
                }}
            </span>
        </div>

        <!-- FIXED: Dynamic actual order amount -->
        <div class="order-row">
            <span class="label">💰 Total amount</span>
            <span class="value highlight">
                ₹{{ number_format((float) $order->total, 2) }}
            </span>
        </div>

        <div
            class="order-row"
            style="border-bottom: 0; padding-bottom: 4px; margin-top: 4px;"
        >
            <span class="label">📬 Status</span>

            <span class="value">
                <span class="status-chip">
                    ✓ Confirmed
                </span>
            </span>
        </div>

    </div>

    <!-- Friendly message -->
    <div class="message-box">
        <p>
            <span class="highlight-text">What's next?</span>
        </p>

        <p>
            We have received your order and will notify ✨you once it has been shipped.
        </p>

        <p style="font-size: 15px; margin-top: 8px;">
            You can track your order anytime using the button below.
        </p>
    </div>

    <!-- Action button -->
    <div style="text-align: center;">
        <a href="#" class="btn-track">
            📋 View my order
        </a>
    </div>

    <!-- Additional info -->
    <p
        style="
            font-size: 15px;
            color: #3d3d4a;
            text-align: center;
            margin: 18px 0 6px;
        "
    >
        Need help? Contact our support team — we're here for you.
    </p>

    <!-- Footer -->
    <div class="footer-note">

        <p style="margin-bottom: 6px;">
            <strong>Her Ovia</strong> · crafted with care
        </p>

        <p style="margin: 0;">
            Thank you for shopping with us.
        </p>

        <div class="small-links">
            <a href="#">Help</a>
            <a href="#">Track order</a>
            <a href="#">Contact</a>
        </div>

        <p style="margin-top: 16px; font-size: 12px; color: #a49587;">
            This is a system generated email. Please do not reply directly.
        </p>

    </div>

</div>

</body>
</html>