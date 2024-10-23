<?php

namespace App\Tests\Application;

use App\Application\AvailabilityService;
use App\Domain\Entities\Segment;
use App\Infrastructure\Provider\FlightProvider;
use PHPUnit\Framework\TestCase;

class AvailabilityServiceTest extends TestCase
{
    public function testGetAvailability()
    {
        $flightProviderMock = $this->createMock(FlightProvider::class);

        $segment1 = new Segment();
        $segment1->setOriginCode('MAD');
        $segment1->setOriginName('Madrid');
        $segment1->setDestinationCode('BIO');
        $segment1->setDestinationName('Bilbao');
        $segment1->setStart(new \DateTime('2023-06-01T10:00:00'));
        $segment1->setEnd(new \DateTime('2023-06-01T11:00:00'));
        $segment1->setTransportNumber('3975');
        $segment1->setCompanyCode('IB');
        $segment1->setCompanyName('Iberia');

        $flightProviderMock
            ->method('fetchFlights')
            ->willReturn([$segment1, $segment1, $segment1, $segment1, $segment1]);

        $service = new AvailabilityService($flightProviderMock);

        $result = $service->getAvailability('MAD', 'BIO', '2023-06-01');

        $this->assertCount(5, $result);
        $this->assertEquals('MAD', $result[0]->getOriginCode());
        $this->assertEquals('BIO', $result[0]->getDestinationCode());
        $this->assertEquals('Iberia', $result[0]->getCompanyName());
    }
}
