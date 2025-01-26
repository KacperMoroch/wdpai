<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zgadnij piłkarza</title>
    <link rel="stylesheet" href="/public/styles/style3.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="/public/scripts/script.js" defer></script>
</head>
<body>
    <!-- Napis na samej górze -->
    <div class="top-text">
        Zgadnij piłkarza z TOP 5 lig europejskich
    </div>
    <!-- Ikona domku w lewym górnym rogu -->
    <div class="home-icon" onclick="window.location.href='/dashboard';"></div>
    <!-- Kontener dla gry, podzielony na dwie kolumny -->
    <div class="game">
        <h1>Zgadnij piłkarza</h1>
        <form id="guessForm">
            <input id="playerNameInput" type="text" name="player_name" placeholder="Podaj imię i nazwisko" required>
            <button type="submit">Zgadnij</button>
        </form>
    </div>

    <!-- Kontener dla wyników -->
    <div id="results-container">
        <div id="results"></div>
    </div>
    
    <div id="gameOverState" style="display:none;"><?php echo json_encode($_SESSION['game_over'] ?? false); ?></div>
</body>
</html>
