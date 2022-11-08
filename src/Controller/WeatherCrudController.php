<?php

namespace App\Controller;

use App\Entity\Weather;
use App\Form\WeatherType;
use App\Repository\WeatherRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/weather/crud')]
class WeatherCrudController extends AbstractController
{
    #[Route('/', name: 'app_weather_crud_index', methods: ['GET'])]
    #[IsGranted('ROLE_WEATHER_INDEX')]
    public function index(WeatherRepository $weatherRepository): Response
    {
        return $this->render('weather_crud/index.html.twig', [
            'weather' => $weatherRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_weather_crud_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_WEATHER_NEW')]
    public function new(Request $request, WeatherRepository $weatherRepository): Response
    {
        $weather = new Weather();
        $form = $this->createForm(WeatherType::class, $weather);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $weatherRepository->save($weather, true);

            return $this->redirectToRoute('app_weather_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('weather_crud/new.html.twig', [
            'weather' => $weather,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_weather_crud_show', methods: ['GET'])]
    #[IsGranted('ROLE_WEATHER_SHOW')]
    public function show(Weather $weather): Response
    {
        return $this->render('weather_crud/show.html.twig', [
            'weather' => $weather,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_weather_crud_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_WEATHER_EDIT')]
    public function edit(Request $request, Weather $weather, WeatherRepository $weatherRepository): Response
    {
        $form = $this->createForm(WeatherType::class, $weather);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $weatherRepository->save($weather, true);

            return $this->redirectToRoute('app_weather_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('weather_crud/edit.html.twig', [
            'weather' => $weather,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_weather_crud_delete', methods: ['POST'])]
    #[IsGranted('ROLE_WEATHER_DELETE')]
    public function delete(Request $request, Weather $weather, WeatherRepository $weatherRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$weather->getId(), $request->request->get('_token'))) {
            $weatherRepository->remove($weather, true);
        }

        return $this->redirectToRoute('app_weather_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
