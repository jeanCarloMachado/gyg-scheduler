<?php
declare(strict_types=1);

namespace GYG;

use DateTime;

/**
 * @author Jean Carlo Machado <jean.machado@getyourguide.com>
 */
class BlackBoxTest extends \PHPUnit\Framework\TestCase
{
    // when we have  fewer activities than money use all of them
    public function testScenario1()
    {
        $activitiesGetter = function() {
            $data = [
                [
                    'id' => 1,
                    'startTime' => '2017-11-18 14:00:00',
                    'endTime' => '2017-11-18 14:30:00',
                    'price' => 50,
                    'city' => 10
                ],
                [
                    'id' => 2,
                    'startTime' => '2017-11-19 14:00:00',
                    'endTime' => '2017-11-19 15:00:00',
                    'price' => 100,
                    'city' => 10
                ],
            ];

            return activitiesFactory($data);
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

    // when facing two conflicting activities select the one with the earliest ending time
    public function testScenario2()
    {

        $activitiesGetter = function() {
            $data = [
                [
                    'id' => 666,
                    'startTime' => '2017-11-18 14:00:00',
                    'endTime' => '2017-11-18 14:30:00',
                    'price' => 50,
                    'city' => 10
                ],
                [
                    'id' => 555,
                    'startTime' => '2017-11-18 14:00:00',
                    'endTime' => '2017-11-18 15:00:00',
                    'price' => 100,
                    'city' => 10
                ],
            ];
            return activitiesFactory($data);
        };

        $result = scheduler(
            $activitiesGetter,
            $cityId = 10,
            $from = new DateTime('2017-11-18 00:00:00'),
            $to = new DateTime('2017-11-19 23:00:00'),
            $budget = 200
        );

        $this->assertEquals(
            [666],
            $this->getIds($result)
        );
    }

    public function testLotsOfData()
    {

        $activitiesGetter = function() {
            $data = [
                [
                    'id' => 666,
                    'startTime' => '2017-11-18 14:00:00',
                    'endTime' => '2017-11-18 14:30:00',
                    'price' => 50,
                    'city' => 10
                ],
                [
                    'id' => 555,
                    'startTime' => '2017-11-18 14:00:00',
                    'endTime' => '2017-11-18 15:00:00',
                    'price' => 100,
                    'city' => 10
                ],
                [
                    'id' => 333,
                    'startTime' => '2017-11-18 16:00:00',
                    'endTime' => '2017-11-18 17:00:00',
                    'price' => 100,
                    'city' => 10
                ],
            ];
            return activitiesFactory($data);
        };

        $result = scheduler(
            $activitiesGetter,
            $cityId = 10,
            $from = new DateTime('2017-11-18 00:00:00'),
            $to = new DateTime('2017-11-19 23:00:00'),
            $budget = 200
        );

        $this->assertEquals(
            [666, 333],
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

