<?php

namespace App\Service;

use App\Entity\Autor;
use App\Exception\AutorEmUsoException;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

class AutorService
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function salvar(Autor $autor): void
    {
        $this->entityManager->persist($autor);
        $this->entityManager->flush();
    }

    public function excluir(Autor $autor): void
    {
        try {
            $this->entityManager->remove($autor);
            $this->entityManager->flush();
        } catch (ForeignKeyConstraintViolationException $exception) {
            throw new AutorEmUsoException(
                'Não é possível excluir este autor porque há livros vinculados.',
                0,
                $exception
            );
        }
    }
}
