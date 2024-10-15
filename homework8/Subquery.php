
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
    // SQL-запрос
    $sql = "
    SELECT c.name, c.email
    FROM customer c
    WHERE c.id IN (
        SELECT co.customer_id
        FROM customer_order co
        JOIN product p ON co.product_id = p.id
        GROUP BY co.customer_id
        HAVING SUM(co.quantity * p.price) > 1000
    )
    AND c.id IN (
        SELECT co.customer_id
        FROM customer_order co
        GROUP BY co.customer_id
        HAVING COUNT(co.id) > 1
    )";
    // Выполнение запроса
    $stmt = $pdo->query($sql);
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Проверка наличия результатов
    if (empty($customers))
    {
        echo "Нет покупателей, соответствующих критериям.";
    }
        // Вывод результатов в виде HTML-таблицы
        echo "<table border='1'>";
        echo "<tr><th>Name</th><th>Email</th></tr>";

        foreach ($customers as $customer) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($customer['name']) . "</td>";
            echo "<td>" . htmlspecialchars($customer['email']) . "</td>";
            echo "</tr>";
        }

        echo "</table>";
} catch (PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
}
?>