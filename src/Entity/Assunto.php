<?php

namespace App\Entity;

use App\Repository\AssuntoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AssuntoRepository::class)]
#[ORM\Table(name: 'Assunto')]
class Assunto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'codAs', type: 'integer')]
    private ?int $codAs = null;

    #[ORM\Column(name: 'Descricao', length: 20)]
    #[Assert\NotBlank(message: 'Informe a descrição do assunto.')]
    #[Assert\Length(max: 20, maxMessage: 'A descrição deve ter no máximo {{ limit }} caracteres.')]
    private ?string $descricao = null;

    public function getCodAs(): ?int
    {
        return $this->codAs;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): static
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function __toString(): string
    {
        return $this->descricao ?? '';
    }
}
