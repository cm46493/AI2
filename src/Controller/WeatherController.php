<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\WeatherRepository;
use App\Entity\Country;
use App\Entity\City;

class WeatherController extends AbstractController {
    public function cityAction(City $city, WeatherRepository $weatherRepository): Response {
        $measurements = $weatherRepository->findByCity($city);
        
        return $this->render('weather/city.html.twig', [
            'city' => $city,
            'measurements' => $measurements,
        ]);
    }
}
