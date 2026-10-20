<?php

namespace App\Tests\Entity;

use App\Entity\Autor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AutorValidationTest extends TestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    public function testNomeObrigatorio(): void
    {
        $autor = new Autor();
        $autor->setNome('');

        $violations = $this->validator->validate($autor);

        $this->assertGreaterThan(0, count($violations), 'Nome vazio deve ser inválido.');
    }

    public function testNomeNaoPodeTerMaisDe40Caracteres(): void
    {
        $autor = new Autor();
        $autor->setNome(str_repeat('A', 41));

        $violations = $this->validator->validate($autor);

        $this->assertGreaterThan(0, count($violations), 'Nome com 41 caracteres deve ser inválido.');
    }

    public function testNomeCom40CaracteresEValido(): void
    {
        $autor = new Autor();
        $autor->setNome(str_repeat('A', 40));

        $violations = $this->validator->validate($autor);

        $this->assertCount(0, $violations);
    }
}
