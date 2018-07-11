<?php
declare(strict_types=1);

namespace GYG;

use DateTime;

/**
 * @author Jean Carlo Machado <jean.machado@getyourguide.com>
 */
class FirstTest extends \PHPUnit\Framework\TestCase
{
    public function testFilterByDate()
    {
        $data = [
            [
                'id' => 1,
                'availability' => '2017-11-18 14:00:00',
            ],
            [
                'id' => 2,
                'availability' => '2017-11-19 14:00:00',
            ],
            [
                'id' => 3,
                'availability' => '2017-11-20 14:00:00',
            ],

        ];

        $activities = array_map(
            function ($entry) {
                return activityFactory($entry);
            },
            $data
        );

        $from = new DateTime('2017-11-18 00:00:00');
        $to = new DateTime('2017-11-19 23:00:00');
        $result = filterByDate($activities, $from, $to);

        $this->assertCount(2, $result);
    }
}

