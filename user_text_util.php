<?php
require __DIR__ .'/vendor/autoload.php';

$config = new \CSV\Config([
    'users_file' => __DIR__ .'/files/people.csv',
    'texts_path' => __DIR__ .'/files/texts',
    'output_texts_path' => __DIR__ .'/files/output_texts',
    'dates_pattern_from' => 'd/m/y',
    'dates_pattern_to' => 'm-d-Y',
    'dates_pattern_regex' => '/\d\d\/\d\d\/\d\d/',
]);

$util = new \CSV\UserTextUtil($config);

try {
    $util->run(...$argv);
} catch (\Throwable $e) {
    // error handler
    echo 'error: '. $e->getMessage() . PHP_EOL;
}
