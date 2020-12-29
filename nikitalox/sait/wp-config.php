<?php
/**
 * Основные параметры WordPress.
 *
 * Этот файл содержит следующие параметры: настройки MySQL, префикс таблиц,
 * секретные ключи, язык WordPress и ABSPATH. Дополнительную информацию можно найти
 * на странице {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Кодекса. Настройки MySQL можно узнать у хостинг-провайдера.
 *
 * Этот файл используется сценарием создания wp-config.php в процессе установки.
 * Необязательно использовать веб-интерфейс, можно скопировать этот файл
 * с именем "wp-config.php" и заполнить значения.
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'trahnine_test1');

/** Имя пользователя MySQL */
define('DB_USER', 'trahnine_test1');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', 'J1mlXRI8gIfb');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется снова авторизоваться.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '!)b#}CJ)b&}LZ7qMDv_N?mbXJufeJhhFZMdr74A%m]Px@sV=,lRA6oV3P!68/+fr');
define('SECURE_AUTH_KEY',  'WN(0]64Q=XB3&zJN,Vv+`<ddnch3!+[9CF=_z%lC+^1:)-mC-WO ck!+M_A!Mvv!');
define('LOGGED_IN_KEY',    '-fmh`+]aP*!ClD{Qk>wjn1|DKw`-IGg,@+.:4-,1HpwB#DE_$G!|7:DJ/=+wBT8Z');
define('NONCE_KEY',        '^&dD/(I1|`qBz]6^ wzhdJ{2*4=<hYbRU+gQ:esz3-.hAt<)J<Fn5^xqNad*XX:P');
define('AUTH_SALT',        '5gZ`v$=RYo[>#+]g/BR&D}5z{FAI{8z=5Te.Bcp-eQ5{S-?-Op[I# T!{-TH1hpw');
define('SECURE_AUTH_SALT', '_*[b2]lO1+.-q@^E`9-+sV{He(%>dAyiq]o}ff79}aL]fQ%TcU3DR*][Dl!8etC6');
define('LOGGED_IN_SALT',   '_!lK@K]:2+U9A]v%j`-ty`vJ9UQ6d3kkcEv!41|gZ/kXk-&-mNTU+SeYvLoXxA[$');
define('NONCE_SALT',       'V$*&b6V6Db)XYN|G(D>8TsUL!`g9*(P3Vu+2`T>x6VpOj{7{!7st^dP}I^`F<y[U');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько блогов в одну базу данных, если вы будете использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'wp_';

/**
 * Язык локализации WordPress, по умолчанию английский.
 *
 * Измените этот параметр, чтобы настроить локализацию. Соответствующий MO-файл
 * для выбранного языка должен быть установлен в wp-content/languages. Например,
 * чтобы включить поддержку русского языка, скопируйте ru_RU.mo в wp-content/languages
 * и присвойте WPLANG значение 'ru_RU'.
 */
define('WPLANG', 'ru_RU');

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Настоятельно рекомендуется, чтобы разработчики плагинов и тем использовали WP_DEBUG
 * в своём рабочем окружении.
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');
