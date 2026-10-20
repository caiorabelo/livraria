<?php

namespace App\Controller;

use App\Entity\Autor;
use App\Exception\AutorEmUsoException;
use App\Form\AutorType;
use App\Repository\AutorRepository;
use App\Service\AutorService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/autores')]
class AutorController extends AbstractController
{
    public function __construct(private readonly AutorService $autorService)
    {
    }

    #[Route('', name: 'app_autor_index', methods: ['GET'])]
    public function index(AutorRepository $autorRepository): Response
    {
        return $this->render('autor/index.html.twig', [
            'autores' => $autorRepository->findBy([], ['nome' => 'ASC']),
        ]);
    }

    #[Route('/novo', name: 'app_autor_novo', methods: ['GET', 'POST'])]
    public function novo(Request $request): Response
    {
        $autor = new Autor();
        $form = $this->createForm(AutorType::class, $autor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->autorService->salvar($autor);
            $this->addFlash('success', 'Autor cadastrado com sucesso.');

            return $this->redirectToRoute('app_autor_index');
        }

        return $this->render('autor/form.html.twig', [
            'autor' => $autor,
            'form' => $form,
            'titulo' => 'Novo autor',
        ]);
    }

    #[Route('/{codAu}/editar', name: 'app_autor_editar', requirements: ['codAu' => '\d+'], methods: ['GET', 'POST'])]
    public function editar(Request $request, #[MapEntity(id: 'codAu')] Autor $autor): Response
    {
        $form = $this->createForm(AutorType::class, $autor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->autorService->salvar($autor);
            $this->addFlash('success', 'Autor atualizado com sucesso.');

            return $this->redirectToRoute('app_autor_index');
        }

        return $this->render('autor/form.html.twig', [
            'autor' => $autor,
            'form' => $form,
            'titulo' => 'Editar autor',
        ]);
    }

    #[Route('/{codAu}/excluir', name: 'app_autor_excluir', requirements: ['codAu' => '\d+'], methods: ['POST'])]
    public function excluir(Request $request, #[MapEntity(id: 'codAu')] Autor $autor): Response
    {
        if (!$this->isCsrfTokenValid('excluir-autor'.$autor->getCodAu(), $request->request->get('_token'))) {
            $this->addFlash('danger', 'Requisição inválida.');

            return $this->redirectToRoute('app_autor_index');
        }

        try {
            $this->autorService->excluir($autor);
            $this->addFlash('success', 'Autor excluído com sucesso.');
        } catch (AutorEmUsoException $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }

        return $this->redirectToRoute('app_autor_index');
    }
}
