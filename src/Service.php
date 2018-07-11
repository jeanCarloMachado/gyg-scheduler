<?php
declare(strict_types=1);
namespace GYG;

use DateTime;
use f\Either;


class Activity
{
    public $id;
    public $startTime;
    public $endTime;
    public $cost;
    public $reviewsCount;
    public $rating;
    public $availability;
    public $price;
}


//activities getter assumes that the data is filtered by city and date
//which are the constraints that cannot be changed, the rest the script deals with
function scheduler($activitiesGetter, int $cityId, DateTime $from, DateTime $to, float $budget) : array
{
    $possibleActivities = $activitiesGetter($cityId, $from, $to);

    $result = maximizedSchedule($possibleActivities);

    $result = removeOverspent($budget, ...$result);


    return $result;
}


function concreteGetter() {

    $data = [
        [
            'id' => 666,
            'startTime' => '2017-11-18 14:00:00',
            'endTime' => '2017-11-18 14:30:00',
            'price' => 100,
            'rating' => 5,
            'reviewsCount' => 20,
        ],
        [
            'id' => 555,
            'startTime' => '2017-11-18 15:00:00',
            'endTime' => '2017-11-18 16:00:00',
            'price' => 100,
            'rating' => 2.5,
            'reviewsCount' => 1,
        ],
        [
            'id' => 333,
            'startTime' => '2017-11-18 17:00:00',
            'endTime' => '2017-11-18 18:00:00',
            'price' => 100,
            'rating' => 2.5,
            'reviewsCount' => 40,
        ],
        [
            'id' => 222,
            'startTime' => '2017-11-18 19:00:00',
            'endTime' => '2017-11-18 21:00:00',
            'price' => 100,
            'rating' => 5,
            'reviewsCount' => 1,
        ],
        [
            'id' => 111,
            'startTime' => '2017-11-18 19:00:00',
            'endTime' => '2017-11-18 21:00:00',
            'price' => 100,
            'rating' => 5,
            'reviewsCount' => 1000,
        ],

    ];

    return activitiesFactory($data);
}


function removeOverspent($budget, Activity ...$activities) {

    $totalPrice = array_reduce($activities, function($carry, $entry) {
        return $carry + $entry->price; 
    }, 0);

    if ($totalPrice <= $budget) {
        return $activities;
    }


    $lowestScore = array_reduce(\f\tail($activities), function($carry, $val) {
        if (score($carry) < score($val))
            return $carry;

        return $val;
    }, \f\head($activities));


    $withoutLowestScore = array_filter(
        $activities,
        function ($entry) use ($lowestScore) {
            return $entry != $lowestScore;
        }
    );

    return removeOverspent($budget, ...$withoutLowestScore);
}


function score($activity) {
    return $activity->rating * $activity->reviewsCount;
}


function maximizedSchedule($possibleActivities) {

    while ($possibleActivities) {
        //find earliest ending activity
        $earliest = array_reduce(\f\tail($possibleActivities), function($carry, $val) {
            if (endsEarlier($carry, $val)->isLeft()) {
                return $carry;
            }
            return $val;

        }, \f\head($possibleActivities));

        //remove conflicting
        $possibleActivities = array_filter(
            $possibleActivities,
            function ($entry) use ($earliest) {
                return !conflict($entry, $earliest);
            }
        );

        $result [] = $earliest;
    }

    return $result;
}

function endsEarlier(Activity $a, Activity $b) : Either
{
    $aEnd = $a->endTime->getTimeStamp();
    $bEnd = $b->endTime->getTimeStamp();

    if ($aEnd < $bEnd) {
        return Either::left($a);
    }

    return Either::right($b);
}


function conflict(Activity $a, Activity $b) : bool
{
    $aStart = $a->startTime->getTimeStamp();
    $aEnd = $a->endTime->getTimeStamp();
    $bStart = $b->startTime->getTimeStamp();
    $bEnd = $b->endTime->getTimeStamp();

    //invert the not conflicting logic
    return !(
        $aEnd < $bStart
        || $aStart > $bEnd
    );
}


function activityFactory(array $data) : Activity
{
    $activity = new Activity;
    $activity->id = $data['id'];
    $activity->price = $data['price'];
    $activity->startTime = new DateTime($data['startTime']);
    $activity->endTime = new DateTime($data['endTime']);
    $activity->rating = $data['rating'] ?? 0;
    $activity->reviewsCount = $data['reviewsCount'] ?? 0;
    return $activity;
}

function activitiesFactory($data) : array
{
    return array_map(
        function ($entry) {
            return activityFactory($entry);
        },
        $data
    );
}
