<?php

namespace App\Infrastructure\Provider;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class FlightProvider
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetchFlights(string $origin, string $destination, string $date): string
    {
        $url = "https://testapi.lleego.com/prueba-tecnica/availability-price?origin=$origin&destination=$destination&date=$date";

        $response = $this->client->request('GET', $url);

        return $response->getContent();
    }
}
