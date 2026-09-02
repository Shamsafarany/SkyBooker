<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset Successful</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 0; margin: 0;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table width="600" cellpadding="0" cellspacing="0" style="background: #ffffff; border-radius: 10px; padding: 30px;">
                    
                    <tr>
                        <td style="text-align: center;">
                            <div style="background-color: #4F46E5; width: 70px; height: 70px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <span style="font-size: 32px; color: #ffffff;">🔐</span>
                            </div>
                            <h2 style="color: #333; margin-top: 20px;">Password Reset Successful</h2>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 20px 0; color: #555; font-size: 16px;">
                            Hi {{ $userName }},
                            <br><br>
                            This is a confirmation that your password has been successfully updated.
                            <br><br>
                            If you did not perform this action, please contact support immediately.
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 20px 0; text-align: center;">
                            <a href="{{ route('login') }}"
                                style="background-color: #4F46E5; color: #ffffff; padding: 12px 24px; 
                                text-decoration: none; border-radius: 6px; font-weight: bold; 
                                display: inline-block; font-size: 16px;">
                                Sign In
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td style="color: #999; font-size: 12px; text-align: center; padding-top: 20px;">
                            SkyBooker — Secure Account Notification
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
