<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verify Your AgroTransit Account</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #F8F3E1;
            margin: 0;
            padding: 40px 0;
            color: #262713;
        }
        .container {
            max-width: 580px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(65, 67, 27, 0.08);
            border: 1px solid rgba(65, 67, 27, 0.08);
        }
        .header {
            background-color: #41431B;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #F8F3E1;
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }
        .content {
            padding: 40px;
            text-align: center;
        }
        .content p {
            font-size: 16px;
            line-height: 1.6;
            color: rgba(38, 39, 19, 0.8);
            margin-bottom: 30px;
        }
        .otp-code {
            display: inline-block;
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 6px;
            color: #41431B;
            background-color: #F8F3E1;
            padding: 15px 30px;
            border-radius: 12px;
            margin: 20px 0;
            border: 1px solid rgba(174, 183, 132, 0.4);
        }
        .footer {
            background-color: #FAF8F0;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: rgba(38, 39, 19, 0.6);
            border-top: 1px solid rgba(65, 67, 27, 0.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>AgroTransit</h1>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>Thank you for signing up with AgroTransit. Please use the following One-Time Password (OTP) to verify your account and access your dashboard. This code is valid for 10 minutes.</p>
            <div class="otp-code">{{ $otp }}</div>
            <p style="font-size: 14px; margin-top: 30px;">If you did not request this verification, please ignore this email.</p>
        </div>
        <div class="footer">
            &copy; 2026 AgroTransit. Smart agricultural transportation logistics.
        </div>
    </div>
</body>
</html>
