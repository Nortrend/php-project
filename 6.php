<?php
// Подключение к базе данных
$host = '127.127.126.39';
$dbname = 'postgres';
$user = 'postgres';
$password = 'password';
try {
    $dsn = "pgsql:host=$host;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to the Postgres SQL database successfully!\n";
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Обработка удаления покупателя
if (isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];

    // Удаление заказов покупателя
    $stmt = $pdo->prepare("DELETE FROM customer_order WHERE customer_id = ?");
    $stmt->execute([$deleteId]);

    // Удаление покупателя
    $stmt = $pdo->prepare("DELETE FROM customer WHERE id = ?");
    $stmt->execute([$deleteId]);

    header("Location: " . $_SERVER['PHP_SELF']); // Перезагрузить страницу
    exit;
}

// Получение списка покупателей
$stmt = $pdo->query("SELECT * FROM customer");
$buyers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список покупателей</title>
    <script>
        function confirmDelete(id) {
            return confirm('Вы уверены, что хотите удалить покупателя? Все его заказы будут также удалены.');
        }
    </script>
</head>
<body>
<h1>Список покупателей</h1>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Имя</th>
        <th>Действие</th>
    </tr>
    <?php foreach ($buyers as $buyer): ?>
        <tr>
            <td><?php echo htmlspecialchars($buyer['id']); ?></td>
            <td><?php echo htmlspecialchars($buyer['name']); ?></td>
            <td>
                <form method="post" style="display:inline;" onsubmit="return confirmDelete(<?php echo $buyer['id']; ?>);">
                    <input type="hidden" name="delete_id" value="<?php echo $buyer['id']; ?>">
                    <input type="submit" value="Удалить">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>

