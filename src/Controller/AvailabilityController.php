<?php

namespace App\Controller;

use App\Application\AvailabilityService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AvailabilityController
{
    private AvailabilityService $availabilityService;

    public function __construct(AvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    #[Route('/api/avail', methods: ['GET'])]
    public function getAvailability(Request $request): JsonResponse
    {
        $origin = $request->query->get('origin');
        $destination = $request->query->get('destination');
        $date = $request->query->get('date');

        $flights = $this->availabilityService->getAvailability($origin, $destination, $date);

        return new JsonResponse($flights);
    }
}
