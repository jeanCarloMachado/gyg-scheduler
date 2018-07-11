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
}


function activityFactory(array $data) : Activity
{
    $activity = new Activity;
    $activity->id = $data['id'];
    $activity->price = $data['price'];
    $activity->startTime = new DateTime($data['startTime']);
    $activity->endTime = new DateTime($data['endTime']);
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

//activities getter assumes that the data is filtered by city and date
//which are the constraints that cannot be changed, the rest the script deals with
function scheduler($activitiesGetter, $city, $from, $to, $budget) : array
{
    $possibleActivities = $activitiesGetter($city, $from, $to);

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

    /* $remainingBudget = $budget; */
    /* $result = []; */
    /* foreach($possibleActivities as $activity) { */
    /*     if ($activity->price < $remainingBudget) { */
    /*         $result[] = $activity; */
    /*         $remainingBudget -= $activity->price; */
    /*     } */
    /* } */

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


