<?php
namespace App\Service;

use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Repository\WeatherRepository;

class WeatherUtil
{
    private CityRepository $cityRepository;
    private CountryRepository $countryRepository;
    private WeatherRepository $weatherRepository;

    public function __construct(CityRepository $cityRepository, CountryRepository $countryRepository, WeatherRepository $weatherRepository)
    {
        $this->cityRepository = $cityRepository;
        $this->countryRepository = $countryRepository;
        $this->weatherRepository = $weatherRepository;
    }

    public function getWeatherForCountryAndCity($countryCode, $cityName)
    {
        $countryId = $this->countryRepository->findIdByCode($countryCode);
        $city = $this->cityRepository->findByCountryAndCity($countryId, $cityName);

        $weather = $this->getWeatherForLocation($city);

        return $weather;
    }

    public function getWeatherForLocation($city)
    {
        $weather = $this->weatherRepository->findByCity($city);
        return $weather;
    }

    public function getWeatherForLocationId($cityId)
    {
        $city = $this->cityRepository->find($cityId);
        $weather = $this->getWeatherForLocation($city);
        return $weather;
    }
}
