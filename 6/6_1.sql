-- Задача 1: Получение всех заказов с информацией о покупателях и товарах
-- Напишите запрос, который выводит информацию о всех заказах.
-- Запрос должен вернуть следующие поля: ID заказа, имя покупателя, название товара, количество, цена товара, дата заказа.

SELECT customer_order.id AS order_number,
       customer.name AS customer_name,
       product.name AS product_name,
       quantity,
       price,
       order_date
FROM   customer_order
LEFT JOIN customer ON customer.id = customer_order.customer_id
LEFT JOIN product ON product.id = customer_order.product_id;

