<?php

namespace App\Service;

use App\Entity\Assunto;
use App\Exception\AssuntoEmUsoException;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

class AssuntoService
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function salvar(Assunto $assunto): void
    {
        $this->entityManager->persist($assunto);
        $this->entityManager->flush();
    }

    public function excluir(Assunto $assunto): void
    {
        try {
            $this->entityManager->remove($assunto);
            $this->entityManager->flush();
        } catch (ForeignKeyConstraintViolationException $exception) {
            throw new AssuntoEmUsoException(
                'Não é possível excluir este assunto porque há livros vinculados.',
                0,
                $exception
            );
        }
    }
}
