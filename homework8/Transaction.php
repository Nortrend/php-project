<?php
//Сценарий: Вы разрабатываете систему для обработки заказов. Требуется добавить логику, которая проверяет наличие товара на складе перед созданием заказа. Если товара недостаточно, заказ не должен быть создан, и все изменения должны быть отменены.
//Создайте новую таблицу inventory для хранения информации о количестве товаров на складе. Таблица должна содержать следующие поля: product_id, quantity.
//Напишите скрипт на PHP, который:
//Начинает транзакцию.
//Проверяет, есть ли достаточное количество товара на складе.
//Если товара достаточно, создает новый заказ и обновляет количество товара в таблице inventory.
//Если товара недостаточно, откатывает транзакцию и выводит сообщение об ошибке.
//Обработайте возможные ошибки с использованием исключений и откатывайте транзакцию в случае возникновения ошибок.
// Подключение к базе данных
$host = '127.127.126.39';
$dbname = 'postgres';
$user = 'postgres';
$password = 'password';
try {
    $dsn = "pgsql:host=$host;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password);
    // Режим обработки ошибок
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Пример данных для заказа
    $productId = 16; // ID товара
    $orderQuantity = 5; // Количество товара в заказе
    // Начало транзакции
    $pdo->beginTransaction();
    // Проверка, что товар есть на складе
    $stmt = $pdo->prepare("SELECT quantity FROM inventory WHERE product_id = :id FOR UPDATE");
    $stmt->execute(['id' => $productId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        throw new Exception("Товар не найден.");
    }
    $currentQuantity = $result['quantity'];
    // Проверка наличия достаточного количества товара
    if ($currentQuantity >= $orderQuantity) {
        // Обновление количества товара
        $stmt = $pdo->prepare("UPDATE inventory SET quantity = quantity - :amount WHERE product_id = :id");
        $stmt->execute(['amount' => $orderQuantity, 'id' => $productId]);

        // Подтверждение транзакции
        $pdo->commit();
        echo "Заказ успешно создан, оставшееся количество товара: " . ($currentQuantity - $orderQuantity);
    } else {
        throw new Exception("Недостаточное количество товара на складе.");
    }
} catch (\PDOException $e) {
    // Откат транзакции в случае ошибки
    $pdo->rollBack();
    echo "Transfer failed: " . $e->getMessage();
}

