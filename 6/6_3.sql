-- Задача 3: Найти заказы, сделанные в определенный промежуток времени
-- Напишите запрос, который выводит все заказы, сделанные между 1 октября 2023 года и 3 октября 2023 года.
-- Запрос должен вернуть ID заказа, имя покупателя, название товара и дату заказа.

SELECT customer_order.id AS order_number,
       customer.name AS customer_name,
       product.name AS product_name,
       order_date
FROM   customer_order
LEFT JOIN customer ON customer.id = customer_order.customer_id
LEFT JOIN product ON product.id = customer_order.product_id
WHERE order_date BETWEEN '2023-10-01' AND '2023-10-03';

