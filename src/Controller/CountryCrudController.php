<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/country/crud')]
class CountryCrudController extends AbstractController
{
    #[Route('/', name: 'app_country_crud_index', methods: ['GET'])]
    #[IsGranted('ROLE_COUNTRY_INDEX')]
    public function index(CountryRepository $countryRepository): Response
    {
        return $this->render('country_crud/index.html.twig', [
            'countries' => $countryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_country_crud_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_COUNTRY_NEW')]
    public function new(Request $request, CountryRepository $countryRepository): Response
    {
        $country = new Country();
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $countryRepository->save($country, true);

            return $this->redirectToRoute('app_country_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('country_crud/new.html.twig', [
            'country' => $country,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_country_crud_show', methods: ['GET'])]
    #[IsGranted('ROLE_COUNTRY_SHOW')]
    public function show(Country $country): Response
    {
        return $this->render('country_crud/show.html.twig', [
            'country' => $country,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_country_crud_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_COUNTRY_EDIT')]
    public function edit(Request $request, Country $country, CountryRepository $countryRepository): Response
    {
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $countryRepository->save($country, true);

            return $this->redirectToRoute('app_country_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('country_crud/edit.html.twig', [
            'country' => $country,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_country_crud_delete', methods: ['POST'])]
    #[IsGranted('ROLE_COUNTRY_DELETE')]
    public function delete(Request $request, Country $country, CountryRepository $countryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$country->getId(), $request->request->get('_token'))) {
            $countryRepository->remove($country, true);
        }

        return $this->redirectToRoute('app_country_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
