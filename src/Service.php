<?php
declare(strict_types=1);
namespace GYG;

use DateTime;


class Activity
{
    public $id;
    public $duration;
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

function scheduler($activitiesGetter, $city, $from, $to, $budget) : array
{
    $possibleActivities = $activitiesGetter($city, $from, $to);
    $remainingBudget = $budget;

    return array_filter($possibleActivities, function ($activity) use (&$remainingBudget) {
        if ($activity->price < $remainingBudget) {
            $remainingBudget -= $activity->price;

            return true;
        }
        
        return false;
    });
}
