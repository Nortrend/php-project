<?php
$host = '127.127.126.39';
$dbname = 'postgres';
$user = 'postgres';
$password = 'password';
try {
    $dsn = "pgsql:host=$host;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password);
    // Режим обработки ошибок
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Создание индекса и проверка на то существует ли индекс с таким же именем сейчас
    $createIndexSql = "DROP INDEX IF EXISTS idx_customer_id;
    CREATE INDEX idx_customer_id ON customer_order (customer_id);";
    $pdo->exec($createIndexSql);

    // Пример customer_id для поиска
    $customerId = 1;

    // Запрос для получения заказов
    $sql = "SELECT * FROM customer_order WHERE customer_id = :customer_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['customer_id' => $customerId]);

    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Вывод результатов
    if (!$orders) {
        throw new Exception("Нет заказов для данного покупателя.");
    }
        echo "<table border='1'>";
        echo "<tr><th>Order ID</th><th>Customer ID</th><th>Order Date</th></tr>";

        foreach ($orders as $order) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($order['id']) . "</td>";
            echo "<td>" . htmlspecialchars($order['customer_id']) . "</td>";
            echo "<td>" . htmlspecialchars($order['order_date']) . "</td>";
            echo "</tr>";
        }

        echo "</table>";

} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}
?>