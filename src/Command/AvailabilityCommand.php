<?php

namespace App\Command;

use App\Application\AvailabilityService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class AvailabilityCommand extends Command
{
    protected static $defaultName = 'lleego:avail';

    private AvailabilityService $availabilityService;

    public function __construct(AvailabilityService $availabilityService)
    {
        parent::__construct();
        $this->availabilityService = $availabilityService;
    }

    protected function configure()
    {
        $this
            ->setDescription('Check flight availability.')
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

        $table = new Table($output);
        $table->setHeaders(['Origin', 'Destination', 'Start', 'End', 'Flight', 'Company']);

        foreach ($flights as $flight) {
            $table->addRow([
                $flight->getOriginCode(),
                $flight->getDestinationCode(),
                $flight->getStart()->format('Y-m-d H:i:s'),
                $flight->getEnd()->format('Y-m-d H:i:s'),
                $flight->getTransportNumber(),
                $flight->getCompanyName(),
            ]);
        }

        $table->render();

        return Command::SUCCESS;
    }
}
