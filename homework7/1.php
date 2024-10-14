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
    $stmt = $pdo->prepare("SELECT name, email FROM customer");
    $stmt->execute();
    $results = $stmt->fetchAll();
    $table = "<table border=1>
    <tr>
        <th>name</th>
        <th>email</th>
    </tr>";
    foreach ($results as $row) {
        $table .= "
    <tr>
        <td>".$row['name']."</td>
        <td>".$row['email']."</td>
    </tr>";
    }
    $table .= "</table>";
    echo $table;

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


