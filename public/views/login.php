<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Goaldle">
    <meta name="author" content="Kacper Moroch">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Goaldle</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sniglet:wght@400;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/styles/style.css">
</head>
<body>
    <div class="overlay">
        <div class="login-container">
            <h1>ZALOGUJ SIĘ</h1>
            <form class="login-form" action="/dashboard" method="POST">
                <input type="text" placeholder="Adres e-mail/numer telefonu" required>
                <input type="password" placeholder="Hasło" required>
                <button type="submit" class="login-button">ZALOGUJ SIĘ</button>
            </form>
            <p>Nie masz jeszcze konta? Zarejestruj się!</p>
            <button class="register-button" onclick="location.href='/register'">ZAREJESTRUJ SIĘ</button>
        </div>
    </div>
</body>
</html>