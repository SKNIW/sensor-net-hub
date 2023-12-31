<?php

declare(strict_types=1);

namespace Tests\Infrastructure\AMQP\Worker;

use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Tests\PausedClock;

class WorkerTest extends TestCase
{
    private TestWorker $testWorker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testWorker = new TestWorker(PausedClock::on(new DateTimeImmutable("2022-07-01")));
    }

    public function testGetters(): void
    {
        $this->assertEquals(1000, $this->testWorker->getMaxIterations());
        $this->assertEquals(
            (new DateTimeImmutable("2022-07-01"))->add(new DateInterval("PT1H")),
            $this->testWorker->getMaxLifeTime(),
        );
    }

    public function testMaxIterationsReached(): void
    {
        $this->assertFalse($this->testWorker->maxIterationsReached());

        for ($i = 0; $i < 998; ++$i) {
            $this->testWorker->maxIterationsReached();
        }
        $this->assertFalse($this->testWorker->maxIterationsReached());
        $this->assertTrue($this->testWorker->maxIterationsReached());
    }
}
