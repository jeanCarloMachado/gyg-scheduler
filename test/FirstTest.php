<?php
declare(strict_types=1);

namespace GYG;

use DateTime;

/**
 * @author Jean Carlo Machado <jean.machado@getyourguide.com>
 */
class FirstTest extends \PHPUnit\Framework\TestCase
{
    // when we have  fewer activities than money use all of them
    public function testScenario1()
    {
        $data = [
            [
                'id' => 1,
                'availability' => '2017-11-18 14:00:00',
                'duration' => 30,
                'price' => 50,
                'city' => 10
            ],
            [
                'id' => 2,
                'availability' => '2017-11-19 14:00:00',
                'duration' => 60,
                'price' => 100,
                'city' => 10
            ],
        ];

        $activities = activitiesFactory($data);
        $activitiesGetter = function($city, $from, $to) use ($activities){
            return $activities;
        };

        $result = scheduler(
            $activitiesGetter,
            $cityId = 10,
            $from = new DateTime('2017-11-18 00:00:00'),
            $to = new DateTime('2017-11-19 23:00:00'),
            $budget = 200
        );

        $this->assertEquals(
            [1,2],
            $this->getIds($result)
        );
    }

    private function getIds($activities)
    {
        return array_map(
                function ($entry) {
                    return $entry->id;
                },
                $activities
        );
    }
}

