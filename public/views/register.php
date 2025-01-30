<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="public/styles/login.css">
</head>
<body class="register-page">
    <div class="overlay">
        <div class="login-container">
            <h1>ZAREJESTRUJ SIĘ</h1>
            <?php if (isset($error)) { echo "<p class='error-message'>$error</p>"; } ?>
            <form action="/register" method="POST" class="login-form">
                <input type="email" name="email" placeholder="Adres e-mail" required>
                <input type="tel" name="phone" placeholder="Numer telefonu" required>
                <input type="text" name="nickname" placeholder="Nick" required>
                <input type="password" name="password" placeholder="Hasło" required>
                <input type="password" name="confirm_password" placeholder="Powtórz hasło" required>
                <button type="submit" class="login-button">ZAREJESTRUJ SIĘ</button>
            </form>
            <p>Masz już konto? Zaloguj się!</p>
            <button class="register-button" onclick="location.href='/login'">ZALOGUJ SIĘ</button>
        </div>
    </div>
</body>
</html>
