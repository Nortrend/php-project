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

    $sql = "SELECT 
    c.id AS customer_id,
    c.name AS customer_name,
    co.id AS order_id,
    SUM(i.quantity) AS total_quantity,
    p.id AS product_id,
    p.name AS product_name,
    (SELECT i.quantity FROM inventory i WHERE i.product_id = p.id) AS available_quantity
FROM 
    customer c
JOIN 
    customer_order co ON c.id = co.customer_id
JOIN 
    inventory i ON co.product_id = i.product_id
JOIN 
    product p ON i.product_id = p.id
GROUP BY 
    c.id, co.id, p.id;";

    // Выполнение запроса
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Получение результатов
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Вывод данных в виде HTML-таблицы
    echo '<table border="1">';
    echo '<tr>';
    echo '<th>Customer ID</th>';
    echo '<th>Customer Name</th>';
    echo '<th>Order ID</th>';
    echo '<th>Total Quantity</th>';
    echo '<th>Product ID</th>';
    echo '<th>Product Name</th>';
    echo '<th>Available Quantity</th>';
    echo '</tr>';

    foreach ($results as $row) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['customer_id'] ?? 'N/A') . '</td>';  // customer_id
        echo '<td>' . htmlspecialchars($row['customer_name'] ?? 'N/A') . '</td>'; // customer_name
        echo '<td>' . htmlspecialchars($row['order_id'] ?? 'N/A') . '</td>';      // order_id
        echo '<td>' . htmlspecialchars($row['total_quantity'] ?? 0) . '</td>';    // total_quantity
        echo '<td>' . htmlspecialchars($row['product_id'] ?? 'N/A') . '</td>';    // product_id
        echo '<td>' . htmlspecialchars($row['product_name'] ?? 'N/A') . '</td>';  // product_name
        echo '<td>' . htmlspecialchars($row['available_quantity'] ?? 0) . '</td>'; // available_quantity
        echo '</tr>';
    }

    echo '</table>';

} catch (PDOException $e) {
    echo 'Ошибка подключения: ' . $e->getMessage();
}
