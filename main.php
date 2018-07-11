<?php
declare(strict_types=1);


require __DIR__ . '/vendor/autoload.php';


$cityId = (int) $argv[1];
$from = new DateTime($argv[2]);
$to = new DateTime($argv[3]);
$budget = (float) $argv[4];

$getter = '\GYG\concreteGetter';

$result = \GYG\scheduler($getter, $cityId, $from, $to, $budget);

echo json_encode($result);




