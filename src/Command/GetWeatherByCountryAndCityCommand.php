<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Service\WeatherUtil;

#[AsCommand(
    name: 'weather:by-country-and-city',
    description: 'Add a short description for your command',
)]
class GetWeatherByCountryAndCityCommand extends Command
{
    public function __construct(WeatherUtil $weatherUtil, string $name = null)
    {
        $this->weatherUtil = $weatherUtil;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('countryCode', InputArgument::REQUIRED, 'Country code')
            ->addArgument('cityName', InputArgument::REQUIRED, 'Name of city')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $countryCode = $input->getArgument('countryCode');
        $cityName = $input->getArgument('cityName');

        $weather = $this->weatherUtil->getWeatherForCountryAndCity($countryCode, $cityName);

        foreach ($weather as $w) {
            $io->text($w);
        }

        return Command::SUCCESS;
    }
}
