<?php

namespace App\Tests\Entity;

use App\Entity\Assunto;
use App\Entity\Autor;
use App\Entity\Livro;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LivroValidationTest extends TestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    public function testLivroValido(): void
    {
        $this->assertCount(0, $this->validator->validate($this->criarLivroValido()));
    }

    public function testTituloObrigatorio(): void
    {
        $livro = $this->criarLivroValido()->setTitulo('');

        $this->assertCampoInvalido('titulo', $this->validator->validate($livro));
    }

    public function testAnoDeveTerQuatroDigitos(): void
    {
        $livro = $this->criarLivroValido()->setAnoPublicacao('20A6');

        $this->assertCampoInvalido('anoPublicacao', $this->validator->validate($livro));
    }

    public function testEdicaoDeveSerPositiva(): void
    {
        $livro = $this->criarLivroValido()->setEdicao(0);

        $this->assertCampoInvalido('edicao', $this->validator->validate($livro));
    }

    public function testValorDeveSerPositivo(): void
    {
        $livro = $this->criarLivroValido()->setValor('0.00');

        $this->assertCampoInvalido('valor', $this->validator->validate($livro));
    }

    public function testDeveTerAoMenosUmAutor(): void
    {
        $livro = $this->criarLivroValido();
        foreach ($livro->getAutores() as $autor) {
            $livro->removeAutor($autor);
        }

        $this->assertCampoInvalido('autores', $this->validator->validate($livro));
    }

    public function testDeveTerAoMenosUmAssunto(): void
    {
        $livro = $this->criarLivroValido();
        foreach ($livro->getAssuntos() as $assunto) {
            $livro->removeAssunto($assunto);
        }

        $this->assertCampoInvalido('assuntos', $this->validator->validate($livro));
    }

    private function criarLivroValido(): Livro
    {
        return (new Livro())
            ->setTitulo('Dom Casmurro')
            ->setEditora('Editora Exemplo')
            ->setEdicao(1)
            ->setAnoPublicacao('1899')
            ->setValor('49.90')
            ->addAutor((new Autor())->setNome('Machado de Assis'))
            ->addAssunto((new Assunto())->setDescricao('Romance'));
    }

    private function assertCampoInvalido(
        string $campo,
        ConstraintViolationListInterface $violations
    ): void {
        foreach ($violations as $violation) {
            if ($violation->getPropertyPath() === $campo) {
                $this->addToAssertionCount(1);

                return;
            }
        }

        $this->fail(sprintf('Era esperada uma violação no campo "%s".', $campo));
    }
}
