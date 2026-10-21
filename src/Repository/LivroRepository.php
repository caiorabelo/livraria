<?php

namespace App\Repository;

use App\Entity\Livro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use SortDirection;

/**
 * @extends ServiceEntityRepository<Livro>
 */
class LivroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livro::class);
    }

    /** @return Livro[] */
    public function buscarTodosOrdenados(): array
    {
        return $this->createQueryBuilder('livro')
            ->addSelect('autores', 'assuntos')
            ->leftJoin('livro.autores', 'autores')
            ->leftJoin('livro.assuntos', 'assuntos')
            ->orderBy('livro.titulo', SortDirection::Ascending)
            ->getQuery()
            ->getResult();
    }
}
