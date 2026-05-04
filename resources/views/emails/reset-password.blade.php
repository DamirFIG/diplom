<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Сброс пароля</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #4A90D9;">Сброс пароля</h2>
        
        <p>Здравствуйте, {{ $user->name ?? $user->login }}!</p>
        
        <p>Вы запросили сброс пароля. Нажмите на кнопку ниже, чтобы установить новый пароль:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/reset-password/' . $token) }}" 
               style="background-color: #4A90D9; color: white; padding: 14px 28px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Сбросить пароль
            </a>
        </div>
        
        <p>Или скопируйте эту ссылку в браузер:</p>
        <p style="background: #f4f4f4; padding: 10px; word-break: break-all; border-radius: 5px;">
            {{ url('/reset-password/' . $token) }}
        </p>
        
        <p style="color: #dc3545; font-weight: 600;">
            Ссылка действительна в течение 1 часа.
        </p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
        
        <p style="color: #666; font-size: 14px;">
            Если вы не запрашивали сброс пароля, просто проигнорируйте это письмо.
        </p>
        
        <p style="color: #999; font-size: 12px;">
            Это письмо отправлено автоматически, пожалуйста, не отвечайте на него.
        </p>
    </div>
</body>
</html>
