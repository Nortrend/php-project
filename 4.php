<form action="4.php" method="post">
    <label for="Name">Name:</label>
    <input type="text" id="name" name="name" required>

    <label for="email">E-Mail:</label>
    <input type="email" id="email" name="email" required>

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
echo "Connected to the Postgres SQL database successfully!" . "\n";
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return;
}
$name = $_POST['name'];
$email = $_POST['email'];

// Проверяем, что данные были отправлены и не пустые
if (empty($name) & empty($email)) {
    echo "Please fill in the field.";
    return;
}
if (!empty($name) && !empty($email)) {
    echo "Added customer Name: " . htmlspecialchars($name) . " E-Mail: " . htmlspecialchars($email);
    "<br>";
    $stmt = $pdo->query("INSERT INTO customer (name, email) VALUES ('$name', '$email');");
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
}
