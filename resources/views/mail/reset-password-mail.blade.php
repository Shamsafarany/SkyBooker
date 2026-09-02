<!DOCTYPE html>
<html>
<head>
    <title>Reset Your Password</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4;">
    <table align="center" cellpadding="0" cellspacing="0" width="100%" 
            style="max-width: 600px; background-color: #ffffff; margin: 20px auto; 
                    padding: 20px; border-radius: 8px; 
                    box-shadow: 0 2px 5px rgba(0,0,0,0.1);">

        <tr>
            <td style="padding: 10px 0;">
                <h1 style="color: #4F46E5; margin: 0; font-size: 24px;">Reset Your Password</h1>
                <hr style="border: none; border-top: 2px solid #4F46E5; margin: 15px 0;">
            </td>
        </tr>

        <tr>
            <td style="padding: 5px 0;">
                <p style="font-size: 16px; line-height: 1.5; color: #333; margin: 10px 0;">
                    Hello,
                </p>
                <p style="font-size: 16px; line-height: 1.5; color: #333; margin: 10px 0;">
                    We received a request to reset your password. Click the button below to continue.
                </p>
            </td>
        </tr>

        <tr>
            <td style="padding: 20px 0; text-align: center;">
                <a href="{{ route('showResetPasswordForm', $token) }}"
                    style="background-color: #4F46E5; color: #ffffff; padding: 12px 24px; 
                    text-decoration: none; border-radius: 6px; font-weight: bold; 
                            display: inline-block; font-size: 16px;">
                    Reset Password
                </a>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px 0 10px 0; text-align: center; border-top: 1px solid #e5e7eb;">
                <p style="color:#6b7280; font-size:14px;">
                    This link expires in 30 minutes.
                </p>
                <p style="font-size: 12px; color: #9CA3AF; margin: 5px 0;">
                    This is an automated message. Please do not reply.
                </p>
            </td>
        </tr>

    </table>
</body>
</html>
