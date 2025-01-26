<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/public/styles/style2.css">
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
                    <img src="/public/assets/ludek-plus.png" alt="Ikona dodawania znajomego">
                    <span>Dodaj znajomego</span>
                </li>
                <li>
                    <img src="/public/assets/trybik.png" alt="Ikona ustawień">
                    <span>Ustawienia</span>
                </li>
                <!-- Link do strony logowania -->
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
                <h2>Cytaty</h2>
            </div>
            <div class="tile">
                <h2>Emotikony</h2>
            </div>
            <div class="tile">
                <h2>Kariera</h2>
            </div>
            <div class="tile">
                <h2>Momenty</h2>
            </div>
            <div class="tile">
                <h2>Transfer</h2>
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
                        <img src="/public/assets/ludek-plus.png" alt="Dodaj znajomego">
                        <span>Dodaj znajomego</span>
                    </li>
                    <li>
                        <img src="/public/assets/trybik.png" alt="Ustawienia">
                        <span>Ustawienia</span>
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
