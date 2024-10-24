<?php

namespace App\Controller;

use App\Application\AvailabilityService;
use App\Application\Hydrator\FlightSegmentHydrator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AvailabilityController
{
    private AvailabilityService $availabilityService;

    public function __construct(AvailabilityService $availabilityService, FlightSegmentHydrator $hydrator)
    {
        $this->availabilityService = $availabilityService;
        $this->hydrator = $hydrator;
    }

    public function getAvailability(Request $request): JsonResponse
    {
        $origin = $request->query->get('origin');
        $destination = $request->query->get('destination');
        $date = $request->query->get('date');

        $flights = $this->availabilityService->getAvailability($origin, $destination, $date);

        $hydratedFlights = $this->hydrator->hydrate($flights);

        return new JsonResponse($hydratedFlights);
    }
}
