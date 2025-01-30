<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/public/styles/dashboard.css">
    <script src="/public/scripts/script.js" defer></script> <!-- Załadowanie zewnętrznego skryptu -->
</head>
<body>
    <div class="dashboard">
        <!-- Lewa sekcja -->
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

        <!-- Prawa sekcja -->
        <main class="right-panel">
            <div class="tile">
                <h2>Codzienne wyzwania</h2>
            </div>
            <div class="tile">
                <a href="/startGame">
                    <h2>Zgadnij piłkarza</h2>
                </a>
            </div>
            <div class="tile">
                <a href="/start">
                    <h2>Transfer</h2>
                </a>
            </div>
        </main>

        <!-- Ikona kafele w prawym górnym rogu (widoczna tylko w widoku mobilnym pionowym) -->
        <div class="home-icon"></div>
        <div class="kafele-icon" id="kafeleIcon"></div>

        <!-- Modalne okienko -->
        <div class="modal" id="myModal">
            <div class="modal-content">
                <span class="close-modal" id="closeModal">&times;</span>
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
            </div>
        </div>
    </div>
</body>
</html>
