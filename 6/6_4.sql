-- Задача 4: Подсчитать общее количество заказов и сумму заказанных товаров для каждого покупателя
-- Напишите запрос, который выводит имя каждого покупателя, количество сделанных им заказов и общую сумму всех его заказов.

SELECT customer.name,
       SUM(quantity) AS sum_quantity,
       SUM(price) AS sum_price
FROM   customer_order
LEFT JOIN customer ON customer.id = customer_order.customer_id
LEFT JOIN product ON product.id = customer_order.product_id
group by customer.name;

