<?php
$host = '127.127.126.39';
$dbname = 'postgres';
$user = 'postgres';
$password = 'password';
try {
    $dsn = "pgsql:host=$host;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to the Postgres SQL database successfully!\n";
    $stmt = $pdo->prepare("SELECT customer_order.id AS order_number,
       customer.name AS customer_name,
       product.name AS product_name,
       quantity,
       price,
       order_date
       FROM customer_order
       LEFT JOIN customer ON customer.id = customer_order.customer_id
       LEFT JOIN product ON product.id = customer_order.product_id");
    $stmt->execute();
    $results = $stmt->fetchAll();
    $table = "<table border=1>
    <tr>
        <th>id</th>
        <th>name</th>
        <th>product</th>
        <th>quantity</th>
        <th>price</th>
        <th>order_date</th>
    </tr>";
    foreach ($results as $row) {
        $table .= "
    <tr>
        <td>" . $row['order_number'] . "</td>
        <td>" . $row['customer_name'] . "</td>
        <td>" . $row['product_name'] . "</td>
        <td>" . $row['quantity'] . "</td>
        <td>" . $row['price'] . "</td>
        <td>" . $row['order_date'] . "</td>
    </tr>";
    }

    $table .= "</table>";
    echo $table;

}catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
