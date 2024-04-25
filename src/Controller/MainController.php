<?php

namespace App\Controller;

use App\Entity\Sorties;
use App\Repository\SortiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_home')]
    public function home(SortiesRepository $sortiesRepository): Response
    {
        //chercher les sorties sur la base de donnée et les envoyer à la vue
        $sorties = $sortiesRepository->findAll();
        return $this->render('main/home.html.twig', ["sorties" => $sorties]);

    }
}
