<?php

namespace App\Entity;

use App\Repository\AutorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AutorRepository::class)]
#[ORM\Table(name: 'Autor')]
class Autor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'CodAu', type: 'integer')]
    private ?int $codAu = null;

    #[ORM\Column(name: 'Nome', length: 40)]
    #[Assert\NotBlank(message: 'Informe o nome do autor.')]
    #[Assert\Length(max: 40, maxMessage: 'O nome deve ter no máximo {{ limit }} caracteres.')]
    private ?string $nome = null;

    public function getCodAu(): ?int
    {
        return $this->codAu;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(?string $nome): static
    {
        $this->nome = $nome;

        return $this;
    }

    public function __toString(): string
    {
        return $this->nome ?? '';
    }
}
