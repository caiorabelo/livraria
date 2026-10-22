# Livraria

Cadastro de livros em **PHP 8.2** e **Symfony 7**, com Twig, Doctrine ORM, Bootstrap 5, Webpack Encore e PHPUnit.

O sistema cobre a manutenção de Livro, Autor e Assunto, além de um relatório agrupado por autor cuja consulta é feita em uma VIEW do MySQL.

## Requisitos

- PHP **8.2** ou superior, com as extensões `openssl`, `mbstring`, `intl`, `pdo_mysql`, `zip`, `curl`, `gd` e `fileinfo`
- Composer 2
- Node.js 18+ e NPM
- MySQL 8

Confirme a versão do PHP com `php -v` antes de instalar as dependências.

## Implantação local

1. Clone o repositório e entre na pasta do projeto.
2. Instale as dependências PHP:

```bash
composer install
```

3. Copie `.env.example` para `.env.local` e ajuste `APP_SECRET` e `DATABASE_URL` (usuário, senha, host e nome do banco).
4. Crie o banco e importe os scripts em ordem:

```sql
CREATE DATABASE livraria CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

```bash
mysql -u root -p livraria < sql/01_schema.sql
mysql -u root -p livraria < sql/02_view.sql
mysql -u root -p livraria < sql/03_seed.sql
```

- `01_schema.sql` — tabelas do modelo (mais o campo `Valor` em Livro)
- `02_view.sql` — `vw_relatorio_livros` (fonte do relatório, agrupada por autor)
- `03_seed.sql` — dados de exemplo, incluindo um livro com dois autores

5. Instale e compile os assets (Bootstrap e Sass via Webpack Encore):

```bash
npm install
npm run build
```

Em desenvolvimento, `npm run watch` recompila ao salvar.

6. Suba o servidor apontando para o diretório `public`:

```bash
php -S localhost:8000 -t public
```

Abra http://localhost:8000

7. Testes:

```bash
php vendor/bin/phpunit
```

Para validar também a configuração e os templates:

```bash
php bin/console lint:container
php bin/console lint:twig templates
```

## Estrutura

- `config/` — configuração do Symfony (Doctrine, Twig, Webpack Encore, rotas)
- `src/Controller/` — controladores HTTP
- `src/Entity/` — mapeamento das tabelas
- `templates/` — views Twig
- `assets/` — JavaScript e Sass; o Encore gera `public/build/`
- `sql/` — scripts de schema, VIEW e carga inicial
- `tests/` — testes PHPUnit
