<?php
namespace App\Application\Hydrator;

use App\Domain\Entities\Segment;

class FlightSegmentHydrator
{
    public function hydrate(array $segments): array
    {
        $result = [];

        foreach ($segments as $segment) {
            $result[] = [
                'originCoce' => $segment->getOriginCode(),
                'originName' => $segment->getOriginName(),
                'destinationCode' => $segment->getDestinationCode(),
                'destinationName' => $segment->getDestinationName(),
                'start' => $segment->getStart()->format('Y-m-d H:i'),
                'end' => $segment->getEnd()->format('Y-m-d'),
                'transportNumber' => $segment->getTransportNumber(),
                'companyCode' => $segment->getCompanyCode(),
                'companyName' => $segment->getCompanyName(),
            ];
        }

        return $result;
    }
}
