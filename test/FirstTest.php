<?php
declare(strict_types=1);

namespace GYG;

use DateTime;

/**
 * @author Jean Carlo Machado <jean.machado@getyourguide.com>
 */
class FirstTest extends \PHPUnit\Framework\TestCase
{
    public function testPickActivitiesUntilEndOfMoney()
    {
        $data = [
            [
                'id' => 1,
                'availability' => '2017-11-18 14:00:00',
                'price' => '50',
                'city' => 10
            ],
            [
                'id' => 2,
                'availability' => '2017-11-19 14:00:00',
                'price' => '100',
                'city' => 10
            ],
            [
                'id' => 3,
                'availability' => '2017-11-20 14:00:00',
                'price' => '150',
                'city' => 10
            ],

        ];

        $activities = activitiesFactory($data);
        $activitiesGetter = function($city, $from, $to) use ($activities){
            return $activities;
        };

        $from = new DateTime('2017-11-18 00:00:00');
        $to = new DateTime('2017-11-19 23:00:00');
        $city = 10;
        $budget = 200;

        $result = scheduler($activitiesGetter, $city, $from, $to, $budget);

        $this->assertCount(2, $result);
    }
}

