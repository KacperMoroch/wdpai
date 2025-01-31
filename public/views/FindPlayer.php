<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zgadnij piłkarza</title>
    <link rel="stylesheet" href="/public/styles/findplayer.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="/public/scripts/script.js" defer></script>
</head>
<body>
    <div class="remaining-attempts">
        Pozostałe próby: <span id="remainingAttempts"><?= $remainingAttempts ?? 5 ?></span>
    </div>
    <!-- Napis na samej górze -->
    <div class="top-text">
        Zgadnij piłkarza z TOP 5 lig europejskich
    </div>
    <!-- Ikona domku -->
    <div class="home-icon" onclick="window.location.href='/dashboard';"></div>
    <!-- Kontener dla gry, podzielony na dwie kolumny -->
    <div class="game">
        <h1>Zgadnij piłkarza</h1>
        <form id="guessForm">
            <input id="playerNameInput" type="text" name="player_name" placeholder="Wpisz nazwisko piłkarza" required>
            <button type="submit">Zgadnij</button>
        </form>
    </div>

    <!-- Kontener dla komunikatów -->
    <div id="message-container" class="message hidden"></div>
    <!-- Kontener dla wyników -->
    <div id="results-container">
        <div id="results"></div>
    </div>
    <div id="gameOverState" style="display:none;"><?php echo json_encode($_SESSION['game_over'] ?? false); ?></div>
    
</body>
</html> 