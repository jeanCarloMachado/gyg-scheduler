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
                    'price' => 50
                ],
                [
                    'id' => 2,
                    'startTime' => '2017-11-19 14:00:00',
                    'endTime' => '2017-11-19 15:00:00',
                    'price' => 50
                ],
            ];

            return $this->factoryActivitiesForTest($data);
        };

        $result = scheduler(
            $activitiesGetter,
            $cityId = 10,
            $from = new DateTime('2017-11-18 00:00:00'),
            $to = new DateTime('2017-11-19 23:00:00'),
            $budget = 200
        );

        $this->assertSameIds(
            [1,2],
            $result
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
                ],
                [
                    'id' => 555,
                    'startTime' => '2017-11-18 14:00:00',
                    'endTime' => '2017-11-18 15:00:00',
                ],
            ];
            return $this->factoryActivitiesForTest($data);
        };

        $result = scheduler(
            $activitiesGetter,
            $cityId = 10,
            $from = new DateTime('2017-11-18 00:00:00'),
            $to = new DateTime('2017-11-19 23:00:00'),
            $budget = 200
        );

        $this->assertSameIds(
            [666],
            $result
        );
    }

    // when the schedule proposed is bigger than the money,
    // remove the lowest ranking activity recursively
    public function testScenario3()
    {

        $activitiesGetter = function() {
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

            ];
            return $this->factoryActivitiesForTest($data);
        };

        $result = scheduler(
            $activitiesGetter,
            $cityId = 10,
            $from = new DateTime('2017-11-18 00:00:00'),
            $to = new DateTime('2017-11-19 23:00:00'),
            $budget = 200
        );

        $this->assertSameIds(
            [666, 333],
            $result
        );
    }


    private function assertSameIds($givenIds, $result)
    {
        $ids = array_map(
            function ($entry) {
                return $entry->id;
            },
            $result
        );

        return $this->assertTrue(!array_diff($givenIds, $ids));
    }

    private function factoryActivitiesForTest($activities)
    {
        foreach($activities as $key => $activity) {
            $activities[$key]['price'] = $activity['price'] ?? 50;

        }
        return activitiesFactory($activities);
    }
}

