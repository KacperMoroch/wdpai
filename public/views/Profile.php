<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twój profil</title>
    <link rel="stylesheet" href="/public/styles/profile.css">
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
                <img src="/public/assets/ludek-plus.png" alt="Ikona dodawania znajomego">
                <span>Dodaj znajomego</span>
            </li>
            <li>
                <img src="/public/assets/trybik.png" alt="Ikona ustawień">
                <span>Ustawienia</span>
            </li>
            <li>
                <a href="/logout" style="text-decoration: none; color: inherit;">
                    <img src="/public/assets/wyjscie.png" alt="Ikona wylogowania">
                    <span>Wyloguj się</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Główne okno -->
    <main class="profile-container">
        <section class="profile-info">
            <h2>Witaj, <?= htmlspecialchars($user['login']) ?>!</h2>
            <p>Konto założone: <strong><?= date('d-m-Y', strtotime($user['created_at'])) ?></strong></p>
        </section>

        <section class="profile-points">
            <h3>Twoje punkty: <span><?= htmlspecialchars($points) ?></span></h3>

            <?php if ($points >= 500): ?>
                <div class="achievement">
                    <h4>Osiągnięcia:</h4>
                    <img src="/public/assets/quest1.svg" alt="Osiągnięcie" class="achievement-badge" id="quest1" />
                    <div class="tooltip">Osiągnięcie za 5 dobrych odpowiedzi w trybie 'Zgadnij piłkarza'</div>
                </div>
            <?php else: ?>
            <p class="no-achievement">Brak osiągnięć!</p>
            <?php endif; ?>
        </section>
    </main>

    <!-- Ikona przenosząca na dashboard -->
    <div class="home-icon" onclick="window.location.href='/dashboard';"></div>
</body>
</html>
