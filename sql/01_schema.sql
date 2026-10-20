-- Modelo do enunciado (Livro, Autor, Assunto e tabelas N:N).
-- Ajuste extra permitido: Valor em Livro (DECIMAL). PKs com AUTO_INCREMENT
-- para o CRUD não exigir código manual.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS Livro_Assunto;
DROP TABLE IF EXISTS Livro_Autor;
DROP TABLE IF EXISTS Livro;
DROP TABLE IF EXISTS Autor;
DROP TABLE IF EXISTS Assunto;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE Autor (
    CodAu INT NOT NULL AUTO_INCREMENT,
    Nome VARCHAR(40) NOT NULL,
    PRIMARY KEY (CodAu)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Assunto (
    codAs INT NOT NULL AUTO_INCREMENT,
    Descricao VARCHAR(20) NOT NULL,
    PRIMARY KEY (codAs)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Livro (
    Codl INT NOT NULL AUTO_INCREMENT,
    Titulo VARCHAR(40) NOT NULL,
    Editora VARCHAR(40) NOT NULL,
    Edicao INT NOT NULL,
    AnoPublicacao VARCHAR(4) NOT NULL,
    Valor DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY (Codl)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Livro_Autor (
    Livro_Codl INT NOT NULL,
    Autor_CodAu INT NOT NULL,
    PRIMARY KEY (Livro_Codl, Autor_CodAu),
    KEY Livro_Autor_FKIndex1 (Livro_Codl),
    KEY Livro_Autor_FKIndex2 (Autor_CodAu),
    CONSTRAINT Rel_01 FOREIGN KEY (Livro_Codl) REFERENCES Livro (Codl)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT Rel_02 FOREIGN KEY (Autor_CodAu) REFERENCES Autor (CodAu)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Livro_Assunto (
    Livro_Codl INT NOT NULL,
    Assunto_codAs INT NOT NULL,
    PRIMARY KEY (Livro_Codl, Assunto_codAs),
    KEY Livro_Assunto_FKIndex1 (Livro_Codl),
    KEY Livro_Assunto_FKIndex2 (Assunto_codAs),
    CONSTRAINT Rel_03 FOREIGN KEY (Livro_Codl) REFERENCES Livro (Codl)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT Rel_04 FOREIGN KEY (Assunto_codAs) REFERENCES Assunto (codAs)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
