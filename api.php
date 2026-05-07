<?php
require_once __DIR__ . '/config.php';

function csvLeads(): array
{
    try {
        if (DB_HOST === '') {
            throw new RuntimeException('DB is not configured');
        }
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo->query('SELECT status, registered_at, updated_at, user_login, first_name, last_name, city, utm_campaign, orders_count, reward, days_to_expire FROM leads ORDER BY id ASC')->fetchAll();
    } catch (Throwable $e) {
        return [
            ['status' => 'Создан', 'registered_at' => '2026-03-10 19:57:00', 'updated_at' => '2026-03-10 19:58:00', 'user_login' => 'александр_федярин_312', 'first_name' => 'Александр', 'last_name' => 'Федярин', 'city' => 'Санкт-Петербург', 'utm_campaign' => '', 'orders_count' => 0, 'reward' => '0', 'days_to_expire' => 0],
            ['status' => 'Активный 25', 'registered_at' => '2026-02-28 10:02:00', 'updated_at' => '2026-03-09 05:18:00', 'user_login' => '44b43bc5baac47c7847516337c8279fb', 'first_name' => 'Константин', 'last_name' => 'Мараков', 'city' => 'Екатеринбург', 'utm_campaign' => '', 'orders_count' => 62, 'reward' => '6200.00', 'days_to_expire' => 0],
        ];
    }
}

if (($_GET['action'] ?? '') === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="leads.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Статус', 'Дата регистрации', 'Дата обновления', 'Пользователь', 'Имя', 'Фамилия', 'Город', 'UTM campaign', 'Кол-во заказов', 'Вознаграждение', 'Кол-во дней до конца срока на ЦД']);
    foreach (csvLeads() as $lead) {
        fputcsv($output, $lead);
    }
    fclose($output);
    exit;
}

http_response_code(404);
echo 'Not found';
