-- Carga mínima para validar o N:N e a VIEW (um livro com dois autores).

INSERT INTO Autor (Nome) VALUES
    ('Machado de Assis'),
    ('Clarice Lispector'),
    ('José de Alencar');

INSERT INTO Assunto (Descricao) VALUES
    ('Romance'),
    ('Contos');

INSERT INTO Livro (Titulo, Editora, Edicao, AnoPublicacao, Valor) VALUES
    ('Contos reunidos', 'Editora Record', 1, '1960', 59.90),
    ('Iracema', 'Martin Claret', 3, '1865', 29.50);

-- Livro 1 com dois autores (aparece duas vezes no relatório).
INSERT INTO Livro_Autor (Livro_Codl, Autor_CodAu) VALUES
    (1, 1),
    (1, 2),
    (2, 3);

INSERT INTO Livro_Assunto (Livro_Codl, Assunto_codAs) VALUES
    (1, 2),
    (2, 1);
