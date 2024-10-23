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
        // Crea un mock para FlightProvider
        $flightProviderMock = $this->createMock(FlightProvider::class);

        // Configura el mock para que devuelva un array de Segment
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

        // Configura el mock para devolver 5 segmentos
        $flightProviderMock
            ->method('fetchFlights')
            ->willReturn([$segment1, $segment1, $segment1, $segment1, $segment1]); // Simulando 5 segmentos

        // Crea una instancia de AvailabilityService pasando el mock
        $service = new AvailabilityService($flightProviderMock);

        // Llama al método que estás probando
        $result = $service->getAvailability('MAD', 'BIO', '2023-06-01');

        // Verifica que el tamaño del resultado es 5
        $this->assertCount(5, $result);
    }
}
