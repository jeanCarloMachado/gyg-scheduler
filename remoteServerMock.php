<?php
declare(strict_types=1);

$cityId = $_GET['cityId'];
$from = new DateTime($_GET['from']);
$to = new DateTime($_GET['to']);
$from->setTime(0,0,0);
$to->setTime(23,59,59);

$data = [
    [
        'id' => 777,
        'startTime' => '2018-11-18 14:00:00',
        'endTime' => '2018-11-18 14:30:00',
        'price' => 100,
        'rating' => 5,
        'reviewsCount' => 20,
        'city' => 10
    ],
    [
        'id' => 666,
        'startTime' => '2018-11-18 14:00:00',
        'endTime' => '2018-11-18 14:30:00',
        'price' => 100,
        'rating' => 5,
        'reviewsCount' => 20,
        'city' => 10
    ],
    [
        'id' => 555,
        'startTime' => '2018-11-18 15:00:00',
        'endTime' => '2018-11-18 16:00:00',
        'price' => 100,
        'rating' => 2.5,
        'reviewsCount' => 1,
        'city' => 10
    ],
    [
        'id' => 554,
        'startTime' => '2018-11-18 17:00:00',
        'endTime' => '2018-11-18 19:00:00',
        'price' => 100,
        'rating' => 2.5,
        'reviewsCount' => 1,
        'city' => 10
    ],
    [
        'id' => 333,
        'startTime' => '2018-11-18 17:00:00',
        'endTime' => '2018-11-18 18:00:00',
        'price' => 100,
        'rating' => 2.5,
        'reviewsCount' => 40,
        'city' => 12
    ],
    [
        'id' => 222,
        'startTime' => '2018-11-18 19:00:00',
        'endTime' => '2018-11-18 21:00:00',
        'price' => 100,
        'rating' => 5,
        'reviewsCount' => 1,
        'city' => 11
    ],
    [
        'id' => 111,
        'startTime' => '2018-11-18 19:00:00',
        'endTime' => '2018-11-18 21:00:00',
        'price' => 100,
        'rating' => 5,
        'reviewsCount' => 1000,
        'city' => 11
    ],

];

$data = array_filter(
    $data,
    function ($entry) use ($cityId, $from, $to) {
        if($entry['city'] != $cityId) {
            return false;
        }

        $entryFrom = new DateTime($entry['startTime']);
        $entryTo = new DateTime($entry['endTime']);

        return (
            $entryFrom->getTimeStamp() >= $from->getTimeStamp()
            && $entryTo->getTimeStamp() <= $to->getTimeStamp()
        );
    }
);
echo json_encode($data);
