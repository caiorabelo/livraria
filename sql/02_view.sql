-- Relatório obrigatório: dados das três tabelas principais, agrupados por autor.
-- JOIN interno em Autor: livro sem autor não entra (coerente com "agrupar por autor").
-- Livro com dois autores aparece duas vezes (uma linha por autor).

CREATE OR REPLACE VIEW vw_relatorio_livros AS
SELECT
    a.CodAu,
    a.Nome AS Autor,
    l.Codl,
    l.Titulo,
    l.Editora,
    l.Edicao,
    l.AnoPublicacao,
    l.Valor,
    GROUP_CONCAT(DISTINCT s.Descricao ORDER BY s.Descricao SEPARATOR ', ') AS Assuntos
FROM Autor a
INNER JOIN Livro_Autor la ON la.Autor_CodAu = a.CodAu
INNER JOIN Livro l ON l.Codl = la.Livro_Codl
LEFT JOIN Livro_Assunto las ON las.Livro_Codl = l.Codl
LEFT JOIN Assunto s ON s.codAs = las.Assunto_codAs
GROUP BY
    a.CodAu,
    a.Nome,
    l.Codl,
    l.Titulo,
    l.Editora,
    l.Edicao,
    l.AnoPublicacao,
    l.Valor;
