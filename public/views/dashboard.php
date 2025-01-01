<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/public/styles/style2.css">
</head>
<body>
    <div class="dashboard">
        <!-- Lewa sekcja -->
        <aside class="left-panel">
            <ul>
                <li>
                    <img src="/public/assets/ludek.png" alt="Ikona użytkownika">
                    <span>Twój profil</span>
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
                    <a href="/login">
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
                <h2>Zgadnij piłkarza</h2>
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
    </div>
</body>
</html>
