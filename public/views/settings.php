<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ustawienia</title>
    <link rel="stylesheet" href="public/styles/settings.css">
</head>
<body>
        <!-- Lewy panel -->
        <aside class="left-panel">
        <ul>
            <li>
                <a href="/profile" style="text-decoration: none; color: inherit;">
                    <img src="/public/assets/ludek.png" alt="Ikona użytkownika">
                    <span>Twój profil</span>
                </a>
            </li>
            <li>
                <a href="/admin" style="text-decoration: none; color: inherit;">
                    <img src="/public/assets/admin.svg" alt="Ikona dodawania znajomego">
                    <span>Panel Administratora</span>
                </a>
            </li>
            <li>
                <a href="/settings" style="text-decoration: none; color: inherit;">
                    <img src="/public/assets/trybik.png" alt="Ikona ustawień">
                    <span>Ustawienia</span>
                </a>
            </li>
            <li>
                <a href="/logout" style="text-decoration: none; color: inherit;">
                    <img src="/public/assets/wyjscie.png" alt="Ikona wylogowania">
                    <span>Wyloguj się</span>
                </a>
            </li>
        </ul>
    </aside>
<div class="home-icon" onclick="window.location.href='/dashboard';"></div>
    
<div class="settings-container">
    <h1>Ustawienia konta</h1>
    <p>Witaj, <?= htmlspecialchars($user['login']) ?>!</p>

    <?php if (isset($error)): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="success-message">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/updateAccount">
        <label for="login">Login:</label>
        <input type="text" id="login" name="login" 
            value="<?php echo isset($user['login']) ? htmlspecialchars($user['login']) : ''; ?>" 
            placeholder="Wpisz swój login" required autocomplete="off">

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" 
            value="<?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?>" 
            placeholder="Wpisz swój e-mail" required autocomplete="off">

        <button type="submit">Zaktualizuj</button>
    </form>

    <form action="/deleteAccount" method="post">
        <button type="submit" class="delete-button">Usuń konto</button>
    </form>
</div>

</body>
</html>



