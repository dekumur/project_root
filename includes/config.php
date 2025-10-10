<?php
// === config.php ===

// Физический путь до корня сайта (на сервере)
define('BASE_PATH', dirname(__DIR__));

// Базовый URL (для ссылок в браузере)
// ⚠️ Укажи свой домен и корневую папку — например, если проект открыт как:
// http://localhost/project_root/  → тогда:
define('BASE_URL', '/project_root');

// Если проект находится прямо в корне (http://localhost/), тогда:
// define('BASE_URL', '');
