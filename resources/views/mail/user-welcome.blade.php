<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to SkyBooker</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #4F46E5;
            margin: 0;
            font-size: 28px;
        }
        .btn {
            display: inline-block;
            background: #4F46E5;
            color: #ffffff;
            padding: 12px 28px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
        }
        .btn:hover {
            background: #4338CA;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6B7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to SkyBooker!</h1>
        </div>

        <p>Hi <strong>{{ $user->getFUllNameAttribute() }}</strong>,</p>

        <p>Thank you for registering with SkyBooker! We're excited to have you on board.</p>

        <h3>What you can do next:</h3>
        <ul>
            <li>✈️ Browse flights and book your next trip</li>
            <li>🔔 Manage your notifications</li>
            <li>👤 Complete your profile</li>
        </ul>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('admin.dashboard') }}" class="btn">
                Go to Dashboard
            </a>
        </div>

        <p style="color: #6B7280; font-size: 14px;">
            If you have any questions, please contact our support team.
        </p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} SkyBooker. All rights reserved.</p>
        </div>
    </div>
</body>
</html>