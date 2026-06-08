<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Код подтверждения входа</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #4A90D9;">Код подтверждения входа</h2>

        <p>Здравствуйте, {{ $user->name ?? $user->login }}!</p>
        <p>Для входа в аккаунт введите этот код:</p>

        <div style="font-size: 32px; letter-spacing: 8px; font-weight: 700; text-align: center; margin: 30px 0; color: #2b2b2b;">
            {{ $code }}
        </div>

        <p style="color: #dc3545; font-weight: 600;">
            Код действителен в течение 10 минут.
        </p>

        <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">

        <p style="color: #666; font-size: 14px;">
            Если вы не пытались войти в аккаунт, просто проигнорируйте это письмо.
        </p>
    </div>
</body>
</html>
