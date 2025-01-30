<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora</title>
    <link rel="stylesheet" href="public/styles/admin.css">
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
    <h1>Panel Administratora</h1>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Login</th>
                    <th>Rola</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user->getId()) ?></td>
                        <td><?= htmlspecialchars($user->getEmail()) ?></td>
                        <td><?= htmlspecialchars($user->getLogin()) ?></td>
                        <td><?= htmlspecialchars($user->getRoleName()) ?></td>
                        <td>
                            <form action="/deleteUser" method="POST">
                                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user->getId()) ?>">
                                <button type="submit">🗑</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
