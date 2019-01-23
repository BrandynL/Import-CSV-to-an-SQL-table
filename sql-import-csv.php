<?php
/**
 * Takes a CSV from somewhere on the current server and imports it into the specified database using PDO after truncating the table.
 * by Brandyn L
 * https://the-dev.ninja/
 */

// configure your settings
$table_name = 'sql_table_name';
$path_to_file = '/path_to_file.csv';
$delimiter = ',';

// You can probably store the following as environemental variables
$db_info = 'mysql:host=127.0.0.1;dbname=database-name;';
$db_user = 'user';
$db_pass ='pass';

try{
    $db = new PDO($db_info, $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (exception $e) {
    echo 'could not connect to database';
    echo $e->getMessage();
}

$sql = 'TRUNCATE '. $table_name;
$db->query($sql);

ini_set('auto_detect_line_endings',TRUE);

$f = fopen($path_to_file, 'r');
$data = [];

while($d = fgetcsv($f, 1024, $delimiter)) {    
    $data[] = $d;
}

$sql = 'INSERT INTO '.$table_name.' ('.implode($data[0], ', ').' ) VALUES ';
$i = 1;
$c = count($data);

for ($i = 1;$i < $c; $i++) {
    $sql .= '("'.implode($data[$i], '","');
    if ($i != ($c - 1) ){
        $sql .= '"), ';
    } else {
        $sql .= '") ';
    }
}
$db->query($sql);
