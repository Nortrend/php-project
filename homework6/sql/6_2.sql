-- Задача 2: Получение всех заказов для конкретного покупателя
-- Напишите запрос, который выводит все заказы для покупателя с именем "Jane Smith".
-- Запрос должен вернуть ID заказа, название товара, количество и дату заказа.

SELECT customer_order.id AS order_number,
       product.name AS product_name,
       quantity,
       order_date
FROM   customer_order
LEFT JOIN customer ON customer.id = customer_order.customer_id
LEFT JOIN product ON product.id = customer_order.product_id
WHERE customer.name = 'Jane Smith';

