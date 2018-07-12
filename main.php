<?php
declare(strict_types=1);


require __DIR__ . '/vendor/autoload.php';


$cityId = (int) $argv[1];
$from = new DateTime($argv[2]);
$to = new DateTime($argv[3]);
$budget = (float) $argv[4];

$result = \GYG\scheduler($getter = '\GYG\concreteGetter', $cityId, $from, $to, $budget);

echo json_encode($result);
