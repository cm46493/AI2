<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\WeatherUtil;
use App\Entity\Country;
use App\Entity\City;

class WeatherController extends AbstractController {
    public function cityAction(City $city, WeatherUtil $weatherUtil): Response {
        $measurements = $weatherUtil->getWeatherForLocation($city);
        
        return $this->render('weather/city.html.twig', [
            'city' => $city,
            'measurements' => $measurements,
        ]);
    }
}
