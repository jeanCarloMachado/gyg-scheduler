<?php
declare(strict_types=1);

namespace GYG;

use DateTime;

/**
 * @author Jean Carlo Machado <jean.machado@getyourguide.com>
 */
class UnitTest extends \PHPUnit\Framework\TestCase
{
    public function testConflict()
    {
        $a = new Activity;
        $a->startTime = new DateTime('2017-11-18 14:00:00');
        $a->endTime = new DateTime('2017-11-18 15:00:00');

        $b = new Activity;
        $b->startTime = new DateTime('2017-11-18 14:00:00');
        $b->endTime = new DateTime('2017-11-18 14:30:00');

        $this->assertTrue(conflict($a, $b));


        $a = new Activity;
        $a->startTime = new DateTime('2017-11-18 14:00:00');
        $a->endTime = new DateTime('2017-11-18 15:00:00');

        $b = new Activity;
        $b->startTime = new DateTime('2017-11-18 14:25:00');
        $b->endTime = new DateTime('2017-11-18 14:40:00');

        $this->assertTrue(conflict($a, $b));

    }

    public function testConflictFree()
    {
        $a = new Activity;
        $a->startTime = new DateTime('2017-11-18 13:00:00');
        $a->endTime = new DateTime('2017-11-18 13:30:00');


        $b = new Activity;
        $b->startTime = new DateTime('2017-11-18 14:00:00');
        $b->endTime = new DateTime('2017-11-18 15:00:00');

        $this->assertFalse(conflict($a, $b));
    }

}

