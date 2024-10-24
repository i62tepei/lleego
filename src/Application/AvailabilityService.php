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
        $xmlContent = $this->flightProvider->fetchFlights($origin, $destination, $date);

        if (strpos($xmlContent, '<soap:Envelope') !== false) {
            return $this->parseSoapXml($xmlContent);
        }

        return $this->parseNoSoapXml($xmlContent);
    }

    private function parseSoapXml(string $xmlContent): array
    {
        $xml = new \SimpleXMLElement($xmlContent);

        $body = $xml->xpath('//soap:Body')[0];

        $airShoppingRS = $body->AirShoppingRS->asXML();

        return $this->parseNoSoapXml($airShoppingRS);
    }

    private function parseNoSoapXml(string $xmlContent): array
    {
        $xml = new \SimpleXMLElement($xmlContent);

        $flightSegments = $xml->DataLists->FlightSegmentList->FlightSegment;

        $segments = [];

        foreach ($flightSegments as $flight) {
            $segment = new Segment();

            $segment->setOriginCode((string) $flight->Departure->AirportCode);
            $segment->setOriginName((string) $flight->Departure->AirportName);
            $segment->setDestinationCode((string) $flight->Arrival->AirportCode);
            $segment->setDestinationName((string) $flight->Arrival->AirportName);
            $segment->setStart(new \DateTime((string) $flight->Departure->Date . ' ' . (string) $flight->Departure->Time));
            $segment->setEnd(new \DateTime((string) $flight->Arrival->Date . ' ' . (string) $flight->Arrival->Time));
            $segment->setTransportNumber((string) $flight->MarketingCarrier->FlightNumber);
            $segment->setCompanyCode((string) $flight->MarketingCarrier->AirlineID);
            $segment->setCompanyName((string) $flight->MarketingCarrier->Name);

            $segments[] = $segment;
        }

        return $segments;
    }


}
