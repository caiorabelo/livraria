<?php

namespace App\Controller;

use App\Entity\Assunto;
use App\Exception\AssuntoEmUsoException;
use App\Form\AssuntoType;
use App\Repository\AssuntoRepository;
use App\Service\AssuntoService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/assuntos')]
class AssuntoController extends AbstractController
{
    public function __construct(private readonly AssuntoService $assuntoService)
    {
    }

    #[Route('', name: 'app_assunto_index', methods: ['GET'])]
    public function index(AssuntoRepository $assuntoRepository): Response
    {
        return $this->render('assunto/index.html.twig', [
            'assuntos' => $assuntoRepository->findBy([], ['descricao' => 'ASC']),
        ]);
    }

    #[Route('/novo', name: 'app_assunto_novo', methods: ['GET', 'POST'])]
    public function novo(Request $request): Response
    {
        $assunto = new Assunto();
        $form = $this->createForm(AssuntoType::class, $assunto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->assuntoService->salvar($assunto);
            $this->addFlash('success', 'Assunto cadastrado com sucesso.');

            return $this->redirectToRoute('app_assunto_index');
        }

        return $this->render('assunto/form.html.twig', [
            'assunto' => $assunto,
            'form' => $form,
            'titulo' => 'Novo assunto',
        ]);
    }

    #[Route('/{codAs}/editar', name: 'app_assunto_editar', requirements: ['codAs' => '\d+'], methods: ['GET', 'POST'])]
    public function editar(Request $request, #[MapEntity(id: 'codAs')] Assunto $assunto): Response
    {
        $form = $this->createForm(AssuntoType::class, $assunto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->assuntoService->salvar($assunto);
            $this->addFlash('success', 'Assunto atualizado com sucesso.');

            return $this->redirectToRoute('app_assunto_index');
        }

        return $this->render('assunto/form.html.twig', [
            'assunto' => $assunto,
            'form' => $form,
            'titulo' => 'Editar assunto',
        ]);
    }

    #[Route('/{codAs}/excluir', name: 'app_assunto_excluir', requirements: ['codAs' => '\d+'], methods: ['POST'])]
    public function excluir(Request $request, #[MapEntity(id: 'codAs')] Assunto $assunto): Response
    {
        if (!$this->isCsrfTokenValid('excluir-assunto'.$assunto->getCodAs(), $request->request->get('_token'))) {
            $this->addFlash('danger', 'Requisição inválida.');

            return $this->redirectToRoute('app_assunto_index');
        }

        try {
            $this->assuntoService->excluir($assunto);
            $this->addFlash('success', 'Assunto excluído com sucesso.');
        } catch (AssuntoEmUsoException $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }

        return $this->redirectToRoute('app_assunto_index');
    }
}
