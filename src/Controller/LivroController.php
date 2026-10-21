<?php

namespace App\Controller;

use App\Entity\Livro;
use App\Form\LivroType;
use App\Repository\LivroRepository;
use App\Service\LivroService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/livros')]
class LivroController extends AbstractController
{
    private LivroService $livroService;

    public function __construct(LivroService $livroService)
    {
        $this->livroService = $livroService;
    }

    #[Route('', name: 'app_livro_index', methods: ['GET'])]
    public function index(LivroRepository $livroRepository): Response
    {
        return $this->render('livro/index.html.twig', [
            'livros' => $livroRepository->buscarTodosOrdenados(),
        ]);
    }

    #[Route('/novo', name: 'app_livro_novo', methods: ['GET', 'POST'])]
    public function novo(Request $request): Response
    {
        $livro = new Livro();
        $form = $this->createForm(LivroType::class, $livro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->livroService->salvar($livro);
            $this->addFlash('success', 'Livro cadastrado com sucesso.');

            return $this->redirectToRoute('app_livro_index');
        }

        return $this->render('livro/form.html.twig', [
            'form' => $form,
            'titulo' => 'Novo livro',
        ]);
    }

    #[Route('/{codl}/editar', name: 'app_livro_editar', requirements: ['codl' => '\d+'], methods: ['GET', 'POST'])]
    public function editar(Request $request, #[MapEntity(id: 'codl')] Livro $livro): Response
    {
        $form = $this->createForm(LivroType::class, $livro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->livroService->salvar($livro);
            $this->addFlash('success', 'Livro atualizado com sucesso.');

            return $this->redirectToRoute('app_livro_index');
        }

        return $this->render('livro/form.html.twig', [
            'form' => $form,
            'titulo' => 'Editar livro',
        ]);
    }

    #[Route('/{codl}/excluir', name: 'app_livro_excluir', requirements: ['codl' => '\d+'], methods: ['POST'])]
    public function excluir(Request $request, #[MapEntity(id: 'codl')] Livro $livro): Response
    {
        if (!$this->isCsrfTokenValid('excluir-livro'.$livro->getCodl(), $request->request->get('_token'))) {
            $this->addFlash('danger', 'Requisição inválida.');

            return $this->redirectToRoute('app_livro_index');
        }

        $this->livroService->excluir($livro);
        $this->addFlash('success', 'Livro excluído com sucesso.');

        return $this->redirectToRoute('app_livro_index');
    }
}
