<?php
declare(strict_types=1);
namespace GYG;

use DateTime;

function activityFactory(array $data) : Activity
{
     $activity = new Activity;
     $activity->id = $data['id'];
     $activity->availability = $data['availability'] ? new DateTime($data['availability']) : null;
     return $activity;
}

function filterByDate($activities, DateTime $from, DateTime $to) :  array
{
    return array_filter(
        $activities,
        function ($entry) use ($from, $to) {
            return (
                $entry->availability->getTimestamp() > $from->getTimestamp()
                && $entry->availability->getTimestamp() < $to->getTimestamp()
            );
        }
    );
}


