CREATE DATABASE IF NOT EXISTS leads_demo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE leads_demo;

DROP TABLE IF EXISTS leads;

CREATE TABLE leads (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    status VARCHAR(80) NOT NULL,
    status_color ENUM('yellow', 'green') NOT NULL DEFAULT 'yellow',
    registered_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    user_login VARCHAR(255) NOT NULL,
    first_name VARCHAR(120) NOT NULL,
    last_name VARCHAR(120) NOT NULL,
    city VARCHAR(120) NOT NULL,
    utm_campaign VARCHAR(255) NOT NULL DEFAULT '',
    orders_count INT UNSIGNED NOT NULL DEFAULT 0,
    reward DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    days_to_expire INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO leads
    (status, status_color, registered_at, updated_at, user_login, first_name, last_name, city, utm_campaign, orders_count, reward, days_to_expire)
VALUES
    ('Создан', 'yellow', '2026-03-10 19:57:00', '2026-03-10 19:58:00', 'александр_федярин_312', 'Александр', 'Федярин', 'Санкт-Петербург', '', 0, 0.00, 0),
    ('Активный 25', 'green', '2026-02-28 10:02:00', '2026-03-09 05:18:00', '44b43bc5baac47c7847516337c8279fb', 'Константин', 'Мараков', 'Екатеринбург', '', 62, 6200.00, 0);
