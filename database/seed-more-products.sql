-- Rally: 45 extra products (15 per category) — run this on your LIVE database.
-- How: phpMyAdmin -> open the sports_ecommerce database -> SQL tab -> paste -> Go.
-- Safe to run more than once: products whose name already exists are skipped,
-- and the unique key below is only added if it is missing.
-- Do NOT re-import schema.sql on a live database; it would duplicate the original 8.
USE sports_ecommerce;

SET @idx_exists = (SELECT COUNT(*) FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = 'products' AND index_name = 'uq_products_name');
SET @ddl = IF(@idx_exists = 0, 'ALTER TABLE products ADD UNIQUE KEY uq_products_name (name)', 'SELECT 1');
PREPARE stmt FROM @ddl; EXECUTE stmt; DEALLOCATE PREPARE stmt;

INSERT INTO products (name, category, description, price, image_url, stock, featured) VALUES
('SwiftKnit Running Tee', 'Running', 'Featherlight knit that moves with you on tempo days and long runs.', 36.00, 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?auto=format&fit=crop&w=900&q=85', 38, 0),
('AeroLite Race Singlet', 'Running', 'A barely-there race singlet with open mesh panels for maximum airflow.', 32.00, 'https://images.unsplash.com/photo-1483721310020-03333e577078?auto=format&fit=crop&w=900&q=85', 30, 0),
('Tempo Long-Sleeve Run Top', 'Running', 'Lightweight long-sleeve coverage with thumbholes for cool morning miles.', 52.00, 'https://images.unsplash.com/photo-1476480862126-209bfaa8edc8?auto=format&fit=crop&w=900&q=85', 24, 1),
('Horizon Run Tee', 'Running', 'Soft everyday run tee with a relaxed fit and sweat-wicking finish.', 38.00, 'https://images.unsplash.com/photo-1552674605-db6ffd4facb5?auto=format&fit=crop&w=900&q=85', 35, 0),
('Sprint Start 5-inch Short', 'Running', 'A featherlight 5-inch short built for the blocks and the final kick.', 40.00, 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&w=900&q=85', 28, 1),
('MotionFlex Running Tight', 'Running', 'Full-length compressive tight with a secure pocket and stay-put waistband.', 58.00, 'https://images.unsplash.com/photo-1538805060514-97d9cc17730c?auto=format&fit=crop&w=900&q=85', 22, 0),
('CloudRide Running Shoe', 'Running', 'Responsive cushioning and a breathable upper for daily road miles.', 120.00, 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=85', 16, 1),
('Velocity Runner Shoe', 'Running', 'A light, springy trainer that rolls smoothly from warm-up to cool-down.', 110.00, 'https://images.unsplash.com/photo-1560769629-975ec94e6a86?auto=format&fit=crop&w=900&q=85', 18, 0),
('PaceFlow Knit Shoe', 'Running', 'Sock-like knit upper with a grippy outsole for tempo runs and city miles.', 95.00, 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?auto=format&fit=crop&w=900&q=85', 20, 0),
('AirFlex Road Shoe', 'Running', 'Clean, cushioned road shoe with breathable mesh and all-day comfort.', 105.00, 'https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=900&q=85', 15, 0),
('UltraDry Run Long-Sleeve', 'Running', 'Quick-dry long sleeve that layers easily under a vest or jacket.', 48.00, 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?auto=format&fit=crop&w=900&q=85', 26, 0),
('FeatherLight Run Tee', 'Running', 'An ultra-light tee that feels like nothing on easy days and long runs.', 34.00, 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=900&q=85', 40, 0),
('NightRun Reflective Tee', 'Running', 'Reflective details that keep you visible on early morning and evening runs.', 42.00, 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?auto=format&fit=crop&w=900&q=85', 25, 0),
('Summit Trail Shoe', 'Running', 'Grippy trail shoe with a protective upper for dirt, gravel and roots.', 115.00, 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?auto=format&fit=crop&w=900&q=85', 14, 0),
('StreetRun Court Shoe', 'Running', 'A clean court-inspired runner for rest days, travel and everyday wear.', 88.00, 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?auto=format&fit=crop&w=900&q=85', 19, 0),
('PowerFlex Training Tee', 'Training', 'A sturdy training tee with four-way stretch for lifts, circuits and classes.', 36.00, 'https://images.unsplash.com/photo-1518459031867-a89b944bffe4?auto=format&fit=crop&w=900&q=85', 34, 1),
('CoreDry Gym Tank', 'Training', 'A sweat-wicking gym tank with a wide arm opening for full range of motion.', 28.00, 'https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?auto=format&fit=crop&w=900&q=85', 30, 0),
('IronWorks Muscle Tank', 'Training', 'A cut-in muscle tank built for pull days and heavy sessions.', 30.00, 'https://images.unsplash.com/photo-1526506118085-60ce8714f8c5?auto=format&fit=crop&w=900&q=85', 27, 0),
('GymCore Lifting Tee', 'Training', 'A durable lifting tee that holds its shape through every heavy set.', 38.00, 'https://images.unsplash.com/photo-1605296867304-46d5465a13f1?auto=format&fit=crop&w=900&q=85', 25, 0),
('StudioFlow Yoga Set', 'Training', 'A soft matching yoga set with a supportive top and flexible fit.', 64.00, 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&w=900&q=85', 20, 1),
('Balance Yoga Legging', 'Training', 'Buttery-soft legging with a high waist for studio sessions and slow flows.', 56.00, 'https://images.unsplash.com/photo-1575052814086-f385e2e2ad1b?auto=format&fit=crop&w=900&q=85', 23, 0),
('FlexForm 2.0 Sports Bra', 'Training', 'Medium support with a smooth band for training, studio and circuits.', 46.00, 'https://images.unsplash.com/photo-1548690312-e3b507d8c110?auto=format&fit=crop&w=900&q=85', 26, 0),
('Elevate Crop Top', 'Training', 'A cropped training top with a snug fit that stays put through every rep.', 34.00, 'https://images.unsplash.com/photo-1552902865-b72c031ac5ea?auto=format&fit=crop&w=900&q=85', 29, 0),
('TrainRight Jogger Pant', 'Training', 'A tapered jogger with deep pockets for warm-ups, travel and rest days.', 62.00, 'https://images.unsplash.com/photo-1506629082955-511b1aa562c8?auto=format&fit=crop&w=900&q=85', 21, 0),
('StudioKnit Jogger', 'Training', 'A soft knit jogger with a relaxed taper for studio days and slow mornings.', 58.00, 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=900&q=85', 24, 0),
('CrossTrain 9-inch Short', 'Training', 'A tough 9-inch short with stretch for sleds, boxes and barbell work.', 44.00, 'https://images.unsplash.com/photo-1533681904393-9ab6eee7e408?auto=format&fit=crop&w=900&q=85', 28, 0),
('Rush Training Tee', 'Training', 'A go-to graphic training tee in soft cotton-blend performance fabric.', 35.00, 'https://images.unsplash.com/photo-1554568218-0f1715e72254?auto=format&fit=crop&w=900&q=85', 36, 0),
('HeavyDuty Lifting Tee', 'Training', 'A heavyweight tee that stands up to chalk, bars and hard sessions.', 37.00, 'https://images.unsplash.com/photo-1599058917212-d750089bc07e?auto=format&fit=crop&w=900&q=85', 22, 0),
('Motion Seamless Bra', 'Training', 'A seamless bra with a smooth second-skin fit for training and studio.', 44.00, 'https://images.unsplash.com/photo-1594381898411-846e7d193883?auto=format&fit=crop&w=900&q=85', 27, 0),
('PrimeKnit Training Tee', 'Training', 'A premium knit training tee with a clean fit for gym and street.', 36.00, 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?auto=format&fit=crop&w=900&q=85', 33, 0),
('StormShield Rain Jacket', 'Outerwear', 'A waterproof shell with a clean fit for rainy runs and wet commutes.', 98.00, 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=900&q=85', 16, 1),
('Trailhead Windbreaker', 'Outerwear', 'A light windbreaker with a packable hood for trailheads and breezy days.', 86.00, 'https://images.unsplash.com/photo-1544022613-e87ca75a784a?auto=format&fit=crop&w=900&q=85', 18, 0),
('CloudPuffer Light Jacket', 'Outerwear', 'A light insulated puffer that packs small and warms fast.', 118.00, 'https://images.unsplash.com/photo-1564557287817-3785e38ec1f5?auto=format&fit=crop&w=900&q=85', 15, 0),
('AeroCycle Wind Jacket', 'Outerwear', 'A close-fitting wind jacket with rear pockets for long rides and runs.', 92.00, 'https://images.unsplash.com/photo-1517649763962-0c623066013b?auto=format&fit=crop&w=900&q=85', 17, 0),
('RidgeLine Quilted Jacket', 'Outerwear', 'A quilted layer with a warm hood for cool mornings and city miles.', 108.00, 'https://images.unsplash.com/photo-1548126032-079a0fb0099d?auto=format&fit=crop&w=900&q=85', 14, 1),
('Everyday Layer Jacket', 'Outerwear', 'A versatile layering jacket that works over tees, tanks and hoodies.', 78.00, 'https://images.unsplash.com/photo-1578932750294-f5075e85f44a?auto=format&fit=crop&w=900&q=85', 20, 0),
('WarmUp Track Jacket', 'Outerwear', 'A classic track jacket for warm-ups, cool-downs and sideline days.', 72.00, 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=900&q=85', 22, 0),
('Summit Training Vest', 'Outerwear', 'A light training vest that keeps your core warm without bulk.', 68.00, 'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?auto=format&fit=crop&w=900&q=85', 19, 0),
('RidgeLine Training Anorak', 'Outerwear', 'A pullover anorak with a half zip for changeable training weather.', 84.00, 'https://images.unsplash.com/photo-1518611012118-696072aa579a?auto=format&fit=crop&w=900&q=85', 16, 0),
('FrostGuard Thermal Hoodie', 'Outerwear', 'A brushed thermal hoodie for cold warm-ups and colder rest days.', 82.00, 'https://images.unsplash.com/photo-1595078475328-1ab05d0a6a0e?auto=format&fit=crop&w=900&q=85', 21, 0),
('BreezeBreaker Training Jacket', 'Outerwear', 'A featherlight breaker that blocks wind without slowing you down.', 76.00, 'https://images.unsplash.com/photo-1571731956672-f2b94d7dd0cb?auto=format&fit=crop&w=900&q=85', 18, 0),
('Alpine Warm-Up Jacket', 'Outerwear', 'A warm zip-up for alpine mornings and pre-session layers.', 88.00, 'https://images.unsplash.com/photo-1550345332-09e3ac987658?auto=format&fit=crop&w=900&q=85', 15, 0),
('Metro Commuter Hoodie', 'Outerwear', 'A heavyweight hoodie with a kangaroo pocket for commutes and recovery.', 74.00, 'https://images.unsplash.com/photo-1532029837206-abbe2b7620e3?auto=format&fit=crop&w=900&q=85', 24, 0),
('Sunset Fleece Pullover', 'Outerwear', 'A soft fleece pullover for golden-hour sessions and slow evenings.', 66.00, 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?auto=format&fit=crop&w=900&q=85', 26, 0),
('Harbor Sideline Jacket', 'Outerwear', 'A durable sideline jacket with room to layer over full kit.', 96.00, 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=900&q=85', 15, 0)
ON DUPLICATE KEY UPDATE name = VALUES(name);
