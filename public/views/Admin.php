<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora</title>
    <link rel="stylesheet" href="public/styles/admin.css">
</head>
<body>
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
