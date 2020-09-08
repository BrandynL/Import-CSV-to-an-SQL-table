<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

// load environmenal variables
$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/.env');

// configure your settings
$table_name = $_ENV["php_sql_import_table_name"];
$path_to_file = $_ENV["php_sql_import_path_to_file"];
$delimiter = $_ENV["php_sql_import_delimiter"];

// You can probably store the following as environemental variables
$db_info = $_ENV["php_sql_import_db_connection"];
$db_user = $_ENV["php_sql_import_db_user"];
$db_pass = $_ENV["php_sql_import_db_pass"];

try {
    $db = new PDO($db_info, $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (exception $e) {
    error_log($e->getMessage());
    return false;
}

if ($_ENV["php_sql_import_truncate_table"]) {

    $sql = 'TRUNCATE ' . $table_name;
    $db->query($sql);
}

ini_set('auto_detect_line_endings', TRUE);

$f = fopen($path_to_file, 'r');
$data = [];

while ($d = fgetcsv($f, 1024, $delimiter)) {
    $data[] = $d;
}
fclose($f);

$sql = 'INSERT INTO ' . $table_name . ' (' . implode($data[0], ', ') . ' ) VALUES ';
$i = 1;
$c = count($data);

for ($i = 1; $i < $c; $i++) {
    $sql .= '("' . implode($data[$i], '","');
    if ($i != ($c - 1)) {
        $sql .= '"), ';
    } else {
        $sql .= '") ';
    }
}
$db->query($sql);
