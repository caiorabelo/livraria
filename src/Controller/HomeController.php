<?php

namespace App\Controller;

use App\Repository\AssuntoRepository;
use App\Repository\AutorRepository;
use App\Repository\LivroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        LivroRepository $livroRepository,
        AutorRepository $autorRepository,
        AssuntoRepository $assuntoRepository
    ): Response {
        return $this->render('home/index.html.twig', [
            'quantidadeLivros' => $livroRepository->count([]),
            'quantidadeAutores' => $autorRepository->count([]),
            'quantidadeAssuntos' => $assuntoRepository->count([]),
        ]);
    }
}
