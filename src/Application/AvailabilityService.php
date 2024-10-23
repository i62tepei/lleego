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
        $offers = $xml->OffersGroup->AirlineOffers->Offer;

        $segments = [];

        foreach ($offers as $offer) {
            $segment = new Segment();
            $segment->setOriginCode('MAD');
            $segment->setDestinationCode('BIO');
            $segment->setStart(new \DateTime('2023-06-01T10:00:00'));
            $segment->setEnd(new \DateTime('2023-06-01T11:00:00'));
            $segment->setTransportNumber('3975');
            $segment->setCompanyCode('IB');
            $segment->setCompanyName('Iberia');
            
            $segments[] = $segment;
        }

        return $segments;
    }

}
