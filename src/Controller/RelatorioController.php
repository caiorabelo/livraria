<?php

namespace App\Controller;

use App\Service\RelatorioLivroService;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/relatorio')]
class RelatorioController extends AbstractController
{
    private RelatorioLivroService $relatorioLivroService;

    public function __construct(RelatorioLivroService $relatorioLivroService)
    {
        $this->relatorioLivroService = $relatorioLivroService;
    }

    #[Route('', name: 'app_relatorio_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('relatorio/index.html.twig', [
            'autores' => $this->relatorioLivroService->buscarAgrupadoPorAutor(),
        ]);
    }

    #[Route('/pdf', name: 'app_relatorio_pdf', methods: ['GET'])]
    public function pdf(): Response
    {
        $html = $this->renderView('relatorio/pdf.html.twig', [
            'autores' => $this->relatorioLivroService->buscarAgrupadoPorAutor(),
            'geradoEm' => new \DateTimeImmutable(),
        ]);

        $options = new Options();
        $options->setDefaultFont('DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $response = new Response($dompdf->output());
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'relatorio-livros-por-autor.pdf'
        );
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}
