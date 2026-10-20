<?php

namespace App\Tests\Entity;

use App\Entity\Assunto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AssuntoValidationTest extends TestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    public function testDescricaoObrigatoria(): void
    {
        $assunto = new Assunto();
        $assunto->setDescricao('');

        $violations = $this->validator->validate($assunto);

        $this->assertGreaterThan(0, count($violations), 'Descrição vazia deve ser inválida.');
    }

    public function testDescricaoNaoPodeTerMaisDe20Caracteres(): void
    {
        $assunto = new Assunto();
        $assunto->setDescricao(str_repeat('A', 21));

        $violations = $this->validator->validate($assunto);

        $this->assertGreaterThan(0, count($violations), 'Descrição com 21 caracteres deve ser inválida.');
    }

    public function testDescricaoCom20CaracteresEValida(): void
    {
        $assunto = new Assunto();
        $assunto->setDescricao(str_repeat('A', 20));

        $violations = $this->validator->validate($assunto);

        $this->assertCount(0, $violations);
    }
}
