<?php
declare(strict_types=1);
namespace GYG;

use DateTime;


class Activity
{
    public $id;

    public $durationInMinutes;

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

    $remainingBudget = $budget;
    $result = [];

    foreach($possibleActivities as $activity) {
        if ($activity->price < $remainingBudget) {
            $result[] = $activity;
            $remainingBudget -= $activity->price;
        }
    }

    return $result;
}

