<?php
require_once __DIR__ . '/config.php';

$fallbackLeads = [
    [
        'status' => 'Создан',
        'status_color' => 'yellow',
        'registered_at' => '2026-03-10 19:57:00',
        'updated_at' => '2026-03-10 19:58:00',
        'user_login' => 'александр_федярин_312',
        'first_name' => 'Александр',
        'last_name' => 'Федярин',
        'city' => 'Санкт-Петербург',
        'utm_campaign' => '',
        'orders_count' => 0,
        'reward' => '0',
        'days_to_expire' => 0,
    ],
    [
        'status' => 'Активный 25',
        'status_color' => 'green',
        'registered_at' => '2026-02-28 10:02:00',
        'updated_at' => '2026-03-09 05:18:00',
        'user_login' => '44b43bc5baac47c7847516337c8279fb',
        'first_name' => 'Константин',
        'last_name' => 'Мараков',
        'city' => 'Екатеринбург',
        'utm_campaign' => '',
        'orders_count' => 62,
        'reward' => '6200.00',
        'days_to_expire' => 0,
    ],
];

function dbLeads(): array
{
    if (!defined('DB_HOST') || DB_HOST === '') {
        return [];
    }

    try {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo->query('SELECT * FROM leads ORDER BY id ASC')->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}

function ruDate(string $date): string
{
    $months = [1 => 'янв.', 'февр.', 'мар.', 'апр.', 'мая', 'июн.', 'июл.', 'авг.', 'сент.', 'окт.', 'нояб.', 'дек.'];
    $timestamp = strtotime($date);
    return (int) date('j', $timestamp) . ' ' . $months[(int) date('n', $timestamp)] . ', ' . date('H:i', $timestamp);
}

$leads = dbLeads() ?: $fallbackLeads;
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Лиды</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" aria-label="Боковая навигация">
        <div class="logo-slot" title="Логотип 40×40 px"><span class="logo-mark">◎</span></div>
        <nav class="sidebar-nav sidebar-nav--top">
            <a class="side-link" href="#" aria-label="Профиль"><?= icon('user') ?></a>
            <a class="side-link" href="#" aria-label="Добавить"><?= icon('brief-plus') ?></a>
            <a class="side-link" href="#" aria-label="Кошелек"><?= icon('wallet') ?></a>
            <a class="side-link" href="#" aria-label="Статистика"><?= icon('bars') ?></a>
            <a class="side-link" href="#" aria-label="Портфель"><?= icon('lock') ?></a>
            <a class="side-link" href="#" aria-label="Документ"><?= icon('file') ?></a>
            <a class="side-link" href="#" aria-label="Библиотека"><?= icon('books') ?></a>
        </nav>
        <nav class="sidebar-nav sidebar-nav--bottom">
            <a class="side-link side-link--green" href="#" aria-label="Помощь">☘</a>
            <a class="side-link side-link--badge" href="#" aria-label="Уведомления"><?= icon('bell') ?><span>5</span></a>
            <a class="side-link" href="#" aria-label="Настройки"><?= icon('gear') ?></a>
        </nav>
        <span class="version">v.0.37.0</span>
    </aside>

    <main class="content">
        <header class="topbar">
            <div class="brand-row">
                <h1>Лиды</h1>
                <a class="top-link" href="#"><?= icon('copy') ?>Партнёрская ссылка</a>
                <a class="top-link" href="#"><?= icon('check-user') ?>Проверка на лидовость</a>
            </div>
        </header>

        <section class="tabs" aria-label="Фильтр статуса">
            <button class="tab is-active">Все</button>
            <button class="tab">Активные</button>
            <button class="tab">Лиды</button>
            <button class="tab">Не лиды</button>
        </section>

        <section class="toolbar" aria-label="Фильтры таблицы">
            <div class="filters">
                <button class="chip"><?= icon('calendar') ?>Зарегистрирован<?= icon('chevron') ?></button>
                <button class="chip">Статус<?= icon('chevron') ?></button>
                <button class="chip">Пользователь<?= icon('chevron') ?></button>
                <button class="chip chip--plus"><?= icon('plus') ?>Фильтры</button>
            </div>
            <a class="chip download" href="api.php?action=csv"><?= icon('download') ?>Скачать CSV</a>
        </section>

        <section class="table-card" aria-label="Таблица лидов">
            <div class="table-scroll">
                <table>
                    <thead>
                    <tr>
                        <th>Статус</th>
                        <th>Дата регистрации</th>
                        <th>Дата обновления</th>
                        <th>Пользователь</th>
                        <th>Имя</th>
                        <th>Фамилия</th>
                        <th>Город</th>
                        <th>UTM campaign</th>
                        <th>Кол-во заказов</th>
                        <th>Вознаграждение</th>
                        <th>Кол-во дней до конца срока на ЦД</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($leads as $lead): ?>
                        <tr>
                            <td><span class="status"><i class="dot dot--<?= htmlspecialchars($lead['status_color']) ?>"></i><?= htmlspecialchars($lead['status']) ?></span></td>
                            <td><?= ruDate($lead['registered_at']) ?></td>
                            <td><?= ruDate($lead['updated_at']) ?></td>
                            <td><?= htmlspecialchars($lead['user_login']) ?></td>
                            <td><?= htmlspecialchars($lead['first_name']) ?></td>
                            <td><?= htmlspecialchars($lead['last_name']) ?></td>
                            <td><?= htmlspecialchars($lead['city']) ?></td>
                            <td><?= htmlspecialchars($lead['utm_campaign']) ?></td>
                            <td><?= (int) $lead['orders_count'] ?></td>
                            <td><?= htmlspecialchars($lead['reward']) ?></td>
                            <td><?= (int) $lead['days_to_expire'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
<script src="assets/app.js"></script>
</body>
</html>
<?php
function icon(string $name): string
{
    $icons = [
        'user' => '<svg viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"/><path d="M4 20c1.7-4 14.3-4 16 0"/></svg>',
        'brief-plus' => '<svg viewBox="0 0 24 24"><path d="M7 8V6a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v2"/><rect x="4" y="8" width="16" height="12" rx="3"/><path d="M12 12v5M9.5 14.5h5"/></svg>',
        'wallet' => '<svg viewBox="0 0 24 24"><rect x="3" y="6" width="18" height="13" rx="3"/><path d="M17 12h4"/></svg>',
        'bars' => '<svg viewBox="0 0 24 24"><path d="M5 19v-5M12 19V7M19 19V3"/></svg>',
        'lock' => '<svg viewBox="0 0 24 24"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>',
        'file' => '<svg viewBox="0 0 24 24"><path d="M7 3h7l4 4v14H7z"/><path d="M14 3v5h5"/></svg>',
        'books' => '<svg viewBox="0 0 24 24"><path d="M5 20V5M11 20V5M17 20 14 5M3 20h18"/></svg>',
        'bell' => '<svg viewBox="0 0 24 24"><path d="M6 17h12l-1.4-2V10a4.6 4.6 0 0 0-9.2 0v5z"/><path d="M10 19a2 2 0 0 0 4 0"/></svg>',
        'gear' => '<svg viewBox="0 0 24 24"><path d="M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8z"/><path d="m4 13 .1-2 2.2-.8.7-1.7-1-2 1.5-1.4 2 1 1.8-.7L12 3h2l.8 2.3 1.7.7 2-1 1.4 1.5-1 2 .8 1.8L22 11v2l-2.3.8-.7 1.7 1 2-1.5 1.4-2-1-1.8.7L14 21h-2l-.8-2.3-1.7-.7-2 1-1.4-1.5 1-2-.8-1.8z"/></svg>',
        'copy' => '<svg viewBox="0 0 24 24"><rect x="4" y="8" width="12" height="12" rx="3"/><rect x="10" y="3" width="10" height="10" rx="3"/></svg>',
        'check-user' => '<svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="4"/><path d="M2.5 20c1.3-4 11.7-4 13 0"/><path d="m16 10 2 2 4-5"/></svg>',
        'calendar' => '<svg viewBox="0 0 24 24"><rect x="4" y="5" width="16" height="15" rx="3"/><path d="M8 3v4M16 3v4M4 10h16"/></svg>',
        'chevron' => '<svg viewBox="0 0 24 24"><path d="m7 9 5 5 5-5"/></svg>',
        'plus' => '<svg viewBox="0 0 24 24"><path d="M12 4v16M4 12h16"/></svg>',
        'download' => '<svg viewBox="0 0 24 24"><path d="M12 3v11M8 10l4 4 4-4"/><rect x="4" y="13" width="16" height="8" rx="2"/></svg>',
    ];
    return $icons[$name] ?? '';
}
