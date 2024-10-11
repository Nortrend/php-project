<form action="3.php" method="post">
    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date" name="start_date" required>

    <label for="end_date">End Date:</label>
    <input type="date" id="end_date" name="end_date" required>

    <button type="submit">Submit</button>
</form>

<?php
$host = '127.127.126.39';
$dbname = 'postgres';
$user = 'postgres';
$password = 'password';

$dsn = "pgsql:host=$host;dbname=$dbname";
$pdo = new PDO($dsn, $user, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
echo "Connected to the Postgres SQL database successfully!\n";
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return;
}
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

// Проверяем, что данные были отправлены и не пустые
if (empty($start_date) & empty($end_date)) {
    echo "Please fill in both dates.";
    return;
}
if (!empty($start_date) && !empty($end_date)) {
    echo "Start Date: " . htmlspecialchars($start_date) . "<br>";
    echo "End Date: " . htmlspecialchars($end_date) . "<br>";
    $stmt = $pdo->query("SELECT customer_order.id AS order_number,
       customer.name AS customer_name,
       product.name AS product_name,
       order_date
FROM   customer_order
LEFT JOIN customer ON customer.id = customer_order.customer_id
LEFT JOIN product ON product.id = customer_order.product_id
WHERE order_date BETWEEN '$start_date' AND '$end_date';");

    $table = "<table>
    <tr>
        <th>Start Date</th>
        <th>End Date</th>
    </tr>";
    $table .= "
    <tr>
        <td>" . $start_date . "</td>
        <td>" . $end_date . "</td>
    </tr>";
    $stmt->execute();
    $results = $stmt->fetchAll();
    $table .= "<table>
    <tr>
        <th>id</th>
        <th>name</th>
        <th>product</th>
        <th>order_date</th>
    </tr>";
    foreach ($results as $row) {
        $table .= "
    <tr>
        <td>" . $row['order_number'] . "</td>
        <td>" . $row['customer_name'] . "</td>
        <td>" . $row['product_name'] . "</td>
        <td>" . $row['order_date'] . "</td>
    </tr>";
    }
    $table .= "</table>";
    echo $table;
}

