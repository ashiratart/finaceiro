# 💰 Controle Financeiro – Symfony

Aplicação web para gestão de despesas pessoais, desenvolvida com Symfony 6/7 como projeto de estudo. Permite cadastrar despesas, visualizar gastos do mês, despesas fixas e o total já pago, com login simples.

## Funcionalidades

- Login com usuário e senha (armazenamento com hash MD5 binário – para fins didáticos)
- Dashboard com:
  - Lista de despesas pagas no mês atual
  - Lista de despesas de consumo (não fixas)
  - Gastos fixos (conta de internet, aluguel etc.)
  - Soma total paga no mês
- Inserção de nova despesa via formulário no próprio dashboard
- Redirecionamento após inserção (padrão Post/Redirect/Get)
- Arquitetura baseada em controllers, repositório customizado e templates Twig

## Tecnologias utilizadas

- **PHP 8+**
- **Symfony** (Framework)
- **Doctrine DBAL** (conexão e queries)
- **Twig** (templates)
- **MySQL** (banco de dados)
- (Opcional) Bootstrap para estilização básica

## Pré-requisitos

- PHP 8.1 ou superior
- Composer
- MySQL (ou MariaDB)
- Extensões PHP: pdo_mysql, mbstring

## Como rodar o projeto

1. Clone o repositório:

```bash
git clone https://github.com/ashiratart/finaceiro.git
```

2. Instale as dependências do Symfony:

```bash
composer install
```

3. Configure o arquivo `.env` com as credenciais do banco de dados:

```
DATABASE_URL="mysql://usuario:senha@127.0.0.1:3306/nome_banco?serverVersion=8.0"
```

4. Crie as tabelas no banco (execute os scripts SQL manualmente ou via migrations se configurado). Exemplo mínimo de estrutura:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(100) NOT NULL,
    senha BINARY(16) NOT NULL
);

CREATE TABLE despesas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ds_motivo VARCHAR(255) NOT NULL,
    vl_valor DECIMAL(10,2) NOT NULL,
    dt_consumo DATE,
    dt_pag DATE,
    ds_fixa TINYINT(1) DEFAULT 0
);
```

5. Insira um usuário de teste (a senha deve ser o hash MD5 binário):

```sql
INSERT INTO users (login, senha) VALUES ('admin', UNHEX(MD5('123456')));
```

6. Inicie o servidor embutido do Symfony:

```bash
symfony server:start
```

Acesse: `http://localhost:8000/login`

## Estrutura de pastas (principais)

```
src/
├── Controller/
│   ├── FinancasController.php
│   └── LoginController.php
├── Repository/
│   └── DespesasRepository.php
templates/
├── login/
│   └── index.html.twig
└── financas/
    └── dashboard.html.twig
```

## Próximos passos (aprendizado contínuo)

- Substituir MD5 por bcrypt (Symfony Security)
- Implementar autenticação com o Guard ou formulário do Security Bundle
- Criar migration com Doctrine Migrations
- Adicionar testes unitários e funcionais
- Implementar CRUD para editar/excluir despesas
- Gerar relatórios mensais

---

Desenvolvido como parte do meu processo de migração do PHP puro para o Symfony, aplicando os conceitos na prática e contribuindo para um projeto real na empresa.