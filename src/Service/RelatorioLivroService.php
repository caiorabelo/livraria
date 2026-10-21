<?php

namespace App\Service;

use Doctrine\DBAL\Connection;

class RelatorioLivroService
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array<int, array{
     *     codAu: int,
     *     autor: string,
     *     livros: array<int, array<string, mixed>>
     * }>
     */
    public function buscarAgrupadoPorAutor(): array
    {
        $linhas = $this->connection->executeQuery(
            <<<'SQL'
                SELECT
                    CodAu AS codAu,
                    Autor AS autor,
                    Codl AS codl,
                    Titulo AS titulo,
                    Editora AS editora,
                    Edicao AS edicao,
                    AnoPublicacao AS anoPublicacao,
                    Valor AS valor,
                    Assuntos AS assuntos
                FROM vw_relatorio_livros
                ORDER BY Autor, Titulo
            SQL
        )->fetchAllAssociative();

        $autores = [];

        foreach ($linhas as $linha) {
            $codAu = (int) $linha['codAu'];

            if (!isset($autores[$codAu])) {
                $autores[$codAu] = [
                    'codAu' => $codAu,
                    'autor' => $linha['autor'],
                    'livros' => [],
                ];
            }

            $autores[$codAu]['livros'][] = [
                'codl' => (int) $linha['codl'],
                'titulo' => $linha['titulo'],
                'editora' => $linha['editora'],
                'edicao' => (int) $linha['edicao'],
                'anoPublicacao' => $linha['anoPublicacao'],
                'valor' => $linha['valor'],
                'assuntos' => $linha['assuntos'] ?? '',
            ];
        }

        return array_values($autores);
    }
}
