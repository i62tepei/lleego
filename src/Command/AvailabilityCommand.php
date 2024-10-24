<?php

namespace App\Command;

use App\Application\AvailabilityService;
use App\Application\Hydrator\FlightSegmentHydrator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

#[AsCommand(
    name: 'lleego:avail',
    description: 'Check flight availability.'
)]
class AvailabilityCommand extends Command
{
    private AvailabilityService $availabilityService;

    public function __construct(AvailabilityService $availabilityService, FlightSegmentHydrator $hydrator)
    {
        parent::__construct();
        $this->availabilityService = $availabilityService;
        $this->hydrator = $hydrator;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('origin', InputArgument::REQUIRED, 'Origin code')
            ->addArgument('destination', InputArgument::REQUIRED, 'Destination code')
            ->addArgument('date', InputArgument::REQUIRED, 'Flight date');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $origin = $input->getArgument('origin');
        $destination = $input->getArgument('destination');
        $date = $input->getArgument('date');

        $flights = $this->availabilityService->getAvailability($origin, $destination, $date);

        $hydratedFlights = $this->hydrator->hydrate($flights);

        $table = new Table($output);
        $table->setHeaders(['Origin Code', 'Origin Name', 'Destination Code', 'Destination Name', 'Start', 'End', 'Transport Number', 'Company Code', 'Company Name']);

        foreach ($hydratedFlights as $flight) {
            $table->addRow($flight);
        }

        $table->render();

        return Command::SUCCESS;
    }
}