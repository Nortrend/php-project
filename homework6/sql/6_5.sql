-- Задача 5: Найти самые популярные товары по количеству заказов
-- Напишите запрос, который выводит название каждого товара и количество заказов, в которых этот товар присутствует.
-- Отсортируйте результат по количеству заказов в порядке убывания

SELECT product.name,
       SUM(quantity) AS sum_quantity
FROM   customer_order
           LEFT JOIN customer ON customer.id = customer_order.customer_id
           LEFT JOIN product ON product.id = customer_order.product_id
group by product.name
order by SUM(quantity) DESC;



