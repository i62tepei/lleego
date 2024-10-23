<?php

namespace App\Infrastructure\Provider;

use App\Domain\Entities\Segment;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FlightProvider
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetchFlights(string $origin, string $destination, string $date): array
    {
        // Construye la URL para la solicitud
        $url = "https://testapi.lleego.com/prueba-tecnica/availability-price?origin=$origin&destination=$destination&date=$date";
        
        // Realiza la solicitud GET
        $response = $this->client->request('GET', $url);
        $xmlContent = $response->getContent();

        // Carga el XML y procesa los FlightSegment
        $xml = new \SimpleXMLElement($xmlContent);
        $flights = $xml->xpath('//FlightSegment');

        // Inicializa un array para los segmentos
        $segments = [];

        // Procesa cada FlightSegment y lo convierte en un objeto Segment
        foreach ($flights as $flight) {
            $segment = new Segment();
            $segment->setOriginCode((string) $flight->OriginCode);
            $segment->setOriginName((string) $flight->OriginName);
            $segment->setDestinationCode((string) $flight->DestinationCode);
            $segment->setDestinationName((string) $flight->DestinationName);
            $segment->setStart(new \DateTime((string) $flight->DepartureDateTime));
            $segment->setEnd(new \DateTime((string) $flight->ArrivalDateTime));
            $segment->setTransportNumber((string) $flight->FlightNumber);
            $segment->setCompanyCode((string) $flight->CompanyCode);
            $segment->setCompanyName((string) $flight->CompanyName);
            
            $segments[] = $segment;
        }

        return $segments;
    }
}
