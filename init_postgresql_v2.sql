-- ✅ สร้างตาราง users
CREATE TABLE IF NOT EXISTS users (
  id SERIAL PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password TEXT NOT NULL,
  fullname VARCHAR(100),
  address TEXT,
  phone VARCHAR(20),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ✅ เพิ่มบัญชีแอดมิน (user: admin / pass: 123456)
INSERT INTO users (username, password, fullname, address, phone)
VALUES 
  ('admin', '$2y$10$QXQFsj9IlVke5cZhdDbMze7eEwjGVC3rK0W/3rSMg/R1HAsNaUGNa', 'แอดมิน', 'กรุงเทพฯ', '0812345678'),
  ('user2', '$2y$10$QXQFsj9IlVke5cZhdDbMze7eEwjGVC3rK0W/3rSMg/R1HAsNaUGNa', 'นายสมชาย', 'ขอนแก่น', '0834567890'),
  ('user3', '$2y$10$QXQFsj9IlVke5cZhdDbMze7eEwjGVC3rK0W/3rSMg/R1HAsNaUGNa', 'คุณหญิงศรี', 'ภูเก็ต', '0845678901');

-- ✅ สร้างตาราง products
CREATE TABLE IF NOT EXISTS products (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100),
  price INT,
  image VARCHAR(255)
);

-- ✅ สร้างตาราง orders
CREATE TABLE IF NOT EXISTS orders (
  id SERIAL PRIMARY KEY,
  user_id INT,
  customer_name VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status VARCHAR(20) DEFAULT 'รอดำเนินการ',
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ✅ สร้างตาราง order_items
CREATE TABLE IF NOT EXISTS order_items (
  id SERIAL PRIMARY KEY,
  order_id INT,
  product_id INT,
  qty INT,
  price INT,
  CONSTRAINT fk_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_items_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
<<<<<<< HEAD
);


