<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify Your Email</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fa;
            padding: 40px;
            text-align: center;
            color: #333;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            max-width: 500px;
            margin: 0 auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .btn {
            display: inline-block;
            background: #137b70;
            color: #fff;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
        }
        .btn:hover {
            background: #19afa0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Email Verification Required</h2>

        <p>
            Hi <strong>{{ auth()->user()->name }}</strong>,  
            please verify your email address to continue.
        </p>

        <p>
            We have sent a verification link to your email.  
            If you didn’t receive it, you can resend it below.
        </p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button class="btn">Resend Verification Email</button>
        </form>
    </div>
</body>
</html>
