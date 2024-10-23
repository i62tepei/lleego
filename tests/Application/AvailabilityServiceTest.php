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

        $mockResponse = file_get_contents(__DIR__ . '/../Data/MAD_BIO_OW_1PAX_RS_SOAP.xml');

        $flightProviderMock
            ->method('fetchFlights')
            ->willReturn($mockResponse);

        $service = new AvailabilityService($flightProviderMock);

        $flights = $service->getAvailability('MAD', 'BIO', '2023-06-01');

        $this->assertCount(35, $flights);
        $this->assertEquals('MAD', $flights[0]->getOriginCode());
        $this->assertEquals('BIO', $flights[0]->getDestinationCode());
        $this->assertEquals('Iberia', $flights[0]->getCompanyName());
    }
}
