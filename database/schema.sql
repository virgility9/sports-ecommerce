CREATE DATABASE IF NOT EXISTS sports_ecommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sports_ecommerce;

CREATE TABLE IF NOT EXISTS users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('customer','admin') NOT NULL DEFAULT 'customer',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS products (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(180) NOT NULL,
  category VARCHAR(80) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  image_url VARCHAR(1000) NOT NULL DEFAULT '',
  stock INT UNSIGNED NOT NULL DEFAULT 0,
  featured TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_products_category (category),
  INDEX idx_products_name (name)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS orders (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  status ENUM('placed','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'placed',
  recipient_name VARCHAR(120) NOT NULL,
  address VARCHAR(255) NOT NULL,
  city VARCHAR(120) NOT NULL,
  postal_code VARCHAR(30) NOT NULL,
  payment_method VARCHAR(40) NOT NULL DEFAULT 'Pay on delivery',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id),
  INDEX idx_orders_user (user_id, created_at)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_items (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT UNSIGNED NOT NULL,
  product_id BIGINT UNSIGNED NULL,
  product_name VARCHAR(180) NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO products (name, category, description, price, image_url, stock, featured) VALUES
('AeroFlex Training Tee', 'Training', 'Lightweight, breathable performance fabric built for every rep and every run.', 34.00, 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=85', 40, 1),
('Stride 7-inch Running Short', 'Running', 'Four-way stretch with a secure zip pocket and a comfortable inner brief.', 42.00, 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=900&q=85', 32, 1),
('Summit Performance Hoodie', 'Outerwear', 'A soft, warm layer with sweat-wicking comfort for warm-ups and cool-downs.', 78.00, 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?auto=format&fit=crop&w=900&q=85', 18, 1),
('Court Classic Training Tank', 'Training', 'An easy-moving, quick-dry essential for court sessions and strength days.', 29.00, 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=900&q=85', 25, 0),
('Pace High-Rise Legging', 'Running', 'Supportive high-rise fit with a smooth waistband and a stay-put feel.', 64.00, 'https://images.unsplash.com/photo-1506629905607-d9c36b3b8c2b?auto=format&fit=crop&w=900&q=85', 22, 1),
('Everyday Track Jacket', 'Outerwear', 'A clean, versatile layer with a full zip and room to move.', 86.00, 'https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=900&q=85', 14, 0),
('Velocity Long-Sleeve Top', 'Running', 'Lightweight coverage and breathable comfort for changing conditions.', 48.00, 'https://images.unsplash.com/photo-1503341504253-dff4815485f1?auto=format&fit=crop&w=900&q=85', 20, 0),
('FlexForm Sports Bra', 'Training', 'Medium support and a soft, secure fit for training and studio sessions.', 45.00, 'https://images.unsplash.com/photo-1518310383802-640c2de311b2?auto=format&fit=crop&w=900&q=85', 26, 0)
ON DUPLICATE KEY UPDATE name = VALUES(name);
