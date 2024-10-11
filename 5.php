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

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'];
    $newQuantity = $_POST['quantity'];

    // Обновление количества товара в заказе
    $stmt = $pdo->prepare("UPDATE customer_order SET quantity = ? WHERE id = ?");
    if ($stmt->execute([$newQuantity, $orderId])) {
        $message = "Количество товара успешно обновлено.";
    } else {
        $message = "Ошибка при обновлении количества товара.";
    }
}

// Получение списка заказов
$stmt = $pdo->query("SELECT * FROM customer_order ORDER BY id asc");
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Изменение количества товара в заказе</title>
</head>
<body>

<h1>Изменение количества товара в заказе</h1>

<?php if (isset($message)): ?>
    <p><?= $message ?></p>
<?php endif; ?>

<form action="" method="post">
    <label for="order_id">Выберите заказ:</label>
    <select name="order_id" id="order_id" required>
        <?php foreach ($orders as $order): ?>
            <option value="<?= $order['id'] ?>"><?= $order['product_id'] ?> (Текущее количество: <?= $order['quantity'] ?>)</option>
        <?php endforeach; ?>
    </select>

    <label for="quantity">Новое количество:</label>
    <input type="number" name="quantity" id="quantity" min="1" required>

    <button type="submit">Обновить</button>
</form>

<h2>Список заказов</h2>
<ul>
    <?php foreach ($orders as $order): ?>
        <li><?= $order['product_id'] ?>: <?= $order['quantity'] ?></li>
    <?php endforeach; ?>
</ul>

</body>
</html>