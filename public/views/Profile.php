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

    <!-- Główne okno -->
    <main class="profile-container">
        <section class="profile-info">
            <h2>Witaj, <?= htmlspecialchars($user->getLogin()) ?>!</h2>
            <?php if ($user->getCreatedAt()): ?>
                <p>Konto założone: <strong><?= $user->getCreatedAt()->format('d-m-Y') ?></strong></p>
            <?php else: ?>
                <p>Konto założone: <strong>Brak danych</strong></p>
            <?php endif; ?>
        </section>

        <section class="profile-points">
                <h3>Twoje punkty: <span><?= htmlspecialchars($totalPoints) ?></span> 
                    (<?= htmlspecialchars($pointsFromUserGuessLog) ?> za Zgadnij piłkarza, 
                    <?= htmlspecialchars($pointsFromUserGuessLogTransfer) ?> za Zgadnij transfer)
                </h3>

                <h3>Osiągnięcia:</h3> 

                <div class="achievements">
                    <!-- Osiągnięcia za Zgadnij piłkarza -->
                    <?php if ($pointsFromUserGuessLog >= 100 && $pointsFromUserGuessLog <= 200): ?>
                        <div class="achievement">
                            <img src="/public/assets/quest1.svg" alt="Osiągnięcie" class="achievement-badge" id="quest1" />
                            <div class="tooltip">Osiągnięcie za 100 punktów w trybie 'Zgadnij piłkarza'</div>
                        </div>
                    <?php elseif ($pointsFromUserGuessLog >= 300 && $pointsFromUserGuessLog <= 400): ?>
                        <div class="achievement">
                            <img src="/public/assets/quest12.svg" alt="Osiągnięcie" class="achievement-badge" id="quest12" />
                            <div class="tooltip">Osiągnięcie za 300 punktów w trybie 'Zgadnij piłkarza'</div>
                        </div>
                    <?php elseif ($pointsFromUserGuessLog >= 500): ?>
                        <div class="achievement">
                            <img src="/public/assets/quest123.svg" alt="Osiągnięcie" class="achievement-badge" id="quest123" />
                            <div class="tooltip">Osiągnięcie za 500 punktów w trybie 'Zgadnij piłkarza'</div>
                        </div>
                    <?php endif; ?>

                    <!-- Osiągnięcia za Zgadnij transfer -->
                    <?php if ($pointsFromUserGuessLogTransfer >= 100 && $pointsFromUserGuessLogTransfer <= 200): ?>
                        <div class="achievement">
                            <img src="/public/assets/quest2.svg" alt="Osiągnięcie" class="achievement-badge" id="quest2" />
                            <div class="tooltip">Osiągnięcie za 100 punktów w trybie 'Zgadnij transfer'</div>
                        </div>
                    <?php elseif ($pointsFromUserGuessLogTransfer >= 300 && $pointsFromUserGuessLogTransfer <= 400): ?>
                        <div class="achievement">
                            <img src="/public/assets/quest23.svg" alt="Osiągnięcie" class="achievement-badge" id="quest23" />
                            <div class="tooltip">Osiągnięcie za 300 punktów w trybie 'Zgadnij transfer'</div>
                        </div>
                    <?php elseif ($pointsFromUserGuessLogTransfer >= 500): ?>
                        <div class="achievement">
                            <img src="/public/assets/quest234.svg" alt="Osiągnięcie" class="achievement-badge" id="quest234" />
                            <div class="tooltip">Osiągnięcie za 500 punktów w trybie 'Zgadnij transfer'</div>
                        </div>
                    <?php endif; ?>

                    <!-- Komunikat, jeśli brak osiągnięć -->
                    <?php if ($pointsFromUserGuessLog < 100 && $pointsFromUserGuessLogTransfer < 100): ?>
                        <p class="no-achievement">Brak osiągnięć!</p>
                    <?php endif; ?>
                </div>
        </section>
    </main>

    <!-- Ikona przenosząca na dashboard -->
    <div class="home-icon" onclick="window.location.href='/dashboard';"></div>
</body>
</html>
