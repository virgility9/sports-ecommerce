-- Rally: sale prices ("was" price for the −% badge on product cards).
-- Run ONCE in phpMyAdmin → sports_ecommerce database → SQL tab → Go.
-- The column is optional: leave compare_price empty (NULL) and the product
-- shows its normal price with no sale badge.
-- To put a product on sale, set its compare_price HIGHER than its price,
-- e.g. in the admin panel (Products → Edit → Was price) or with:
--   UPDATE products SET compare_price = 49.00 WHERE id = 1;
-- The shop, admin save and sort all keep working if this has not been run
-- yet; only the Biggest-discount sort needs the column.
ALTER TABLE products ADD COLUMN compare_price DECIMAL(10,2) NULL DEFAULT NULL AFTER price;
