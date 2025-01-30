<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zgadnij transfer</title>
    <link rel="stylesheet" href="/public/styles/transfer.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="/public/scripts/script2.js" defer></script>
</head>
<body>
    <!-- Sekcja dla pozostałych prób -->
    <div class="remaining-attempts">
        Pozostałe próby: <span id="remainingAttempts"><?= $remainingAttempts ?? 5 ?></span>
    </div>

    <!-- Napis na samej górze -->
    <div class="top-text">
        Zgadnij transfer w TOP 5 ligach europejskich
    </div>

    <!-- Ikona domku w lewym górnym rogu -->
    <div class="home-icon" onclick="window.location.href='/dashboard';"></div>

    <!-- Kontener gry -->
    <div class="game">
        <h1>Zgadnij transfer</h1>
        <form id="guesstransferForm">
            <input id="playerNameInput" type="text" name="player_name" placeholder="Wpisz nazwisko piłkarza" required>
            <button type="submit" name="guess">Zgadnij</button>
        </form>
    </div>

    <!-- Kontener dla szczegółów transferu -->
    <div class="transfer-details">
        <?php if (isset($transfer_details)): ?>
            <p>Transfer z: <strong><?= htmlspecialchars($transfer_details['from_club']) ?></strong> do <strong><?= htmlspecialchars($transfer_details['to_club']) ?></strong></p>
            <p>Kwota transferu: <strong><?= htmlspecialchars($transfer_details['transfer_amount']) ?> mln €</strong></p>
        <?php endif; ?>
    </div>

    <!-- Kontener dla komunikatów -->
    <div id="message-container" class="message hidden">
        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
    </div>

    <!-- Informacja o zakończeniu gry (ukryta) -->
    <div id="gameOverState" style="display:none;">
        <?php echo json_encode($_SESSION['game_over'] ?? false); ?>
    </div>
</body>
</html>
