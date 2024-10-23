<?php

namespace App\Application;

use App\Domain\Entities\Segment;
use App\Infrastructure\Provider\FlightProvider;

class AvailabilityService
{
    private FlightProvider $flightProvider;

    public function __construct(FlightProvider $flightProvider)
    {
        $this->flightProvider = $flightProvider;
    }

    public function getAvailability(string $origin, string $destination, string $date): array
    {
        // Llama al adaptador del proveedor para obtener los vuelos
        $flightsData = $this->flightProvider->fetchFlights($origin, $destination, $date);
        
        // Inicializa un array para almacenar los segmentos
        $segments = [];

        // Procesa la respuesta del proveedor y convierte cada vuelo en un objeto Segment
        foreach ($flightsData as $flight) {
            $segment = new Segment();

            // Asigna valores a las propiedades del segmento usando los setters
            $segment->setOriginCode((string) $flight->OriginCode);
            $segment->setOriginName((string) $flight->OriginName);
            $segment->setDestinationCode((string) $flight->DestinationCode);
            $segment->setDestinationName((string) $flight->DestinationName);
            $segment->setStart(new \DateTime((string) $flight->DepartureDateTime));
            $segment->setEnd(new \DateTime((string) $flight->ArrivalDateTime));
            $segment->setTransportNumber((string) $flight->FlightNumber);
            $segment->setCompanyCode((string) $flight->CompanyCode);
            $segment->setCompanyName((string) $flight->CompanyName);
            
            // Agrega el segmento al array
            $segments[] = $segment;
        }

        return $segments;
    }
}
