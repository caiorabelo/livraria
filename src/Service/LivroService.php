<?php

namespace App\Service;

use App\Entity\Livro;
use Doctrine\ORM\EntityManagerInterface;

class LivroService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function salvar(Livro $livro): void
    {
        $this->entityManager->persist($livro);
        $this->entityManager->flush();
    }

    public function excluir(Livro $livro): void
    {
        $this->entityManager->remove($livro);
        $this->entityManager->flush();
    }
}
