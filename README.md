<p align="center"><a href="https://liven.tech" target="_blank"><img src="https://liven.tech/_next/image?url=%2Fimages%2Flogo-2x.png&w=256&q=75" width="300" alt="Liven Logo"></a></p>

# Liven API

A **Liven API** é uma aplicação RESTful desenvolvida com o framework Laravel, que permite o cadastro e controle de usuários e seus endereços. A API implementa autenticação usando JWT (JSON Web Tokens) e oferece endpoints para criação, leitura, atualização e exclusão (CRUD) de usuários e endereços. 

## Funcionalidades

✔️ **Autenticação JWT**: Permite login seguro e gera tokens JWT para autenticação.

✔️ **CRUD de Usuários**: Criação, leitura, atualização e exclusão de contas de usuários.

✔️ **CRUD de Endereços**: Gerenciamento dos endereços associados aos usuários, incluindo filtragem por parâmetros específicos.

✔️ **Documentação Swagger**: Documentação detalhada da API usando Swagger, acessível via interface web.

## Tecnologias Utilizadas

- [**Laravel**](https://laravel.com/docs/11.x): Framework PHP para desenvolvimento web.
- [**darkaonline/l5-swagger**](https://github.com/DarkaOnLine/L5-Swagger): Pacote para integração do Swagger com Laravel, permitindo a geração de documentação da API.
- [**PHP-Open-Source-Saver/jwt-auth**](https://github.com/PHP-Open-Source-Saver/jwt-auth): Biblioteca para implementação de autenticação via JWT (JSON Web Tokens) no Laravel.

## Requisitos

Antes de começar, certifique-se de ter os seguintes componentes instalados no seu ambiente:

- **Composer** (para gerenciar dependências PHP)
- **PHP 8.1** ou superior
- **XAMPP** (opcional) - Ferramenta para gerenciar o MySQL. Alternativamente, você pode usar outras soluções como WAMP, Laragon ou configurar manualmente o MySQL.
- **MySQL Workbench** (opcional) - Ferramenta gráfica para administração do MySQL. Alternativamente, você pode usar o MySQL Shell ou qualquer outro cliente MySQL de sua preferência.

## Instalação 🛠️

### Passo 1: Clonar o Repositório

Clone o projeto para sua máquina local:
```bash
git clone https://github.com/lucasalvesa/liven-api.git
```
Acesse a pasta baixada:
```bash
cd liven-api
```

### Passo 2: Configurar o Ambiente

Renomeie o arquivo `.env.example` para `.env`
Edite o arquivo .env e configure as variáveis de ambiente, especialmente as configurações do banco de dados:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_seu_banco_de_dados
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### Passo 3: Ativar o MySQL
Se estiver usando o XAMPP, abra-o e inicie o serviço MySQL. Se estiver usando outra solução, inicie o serviço de acordo com sua configuração.

### Passo 4: Instalar as Dependências
Execute o Composer para instalar todas as dependências do projeto:
```
composer install
```

### Passo 5: Gerar o JWT Secret
Execute o comando para gerar a chave secreta JWT:
```
php artisan jwt:secret
```

### Passo 6: Executar as Migrações
Execute as migrações para criar as tabelas no banco de dados:
```
php artisan migrate
```
**Obs.:** Se o banco de dados especificado no arquivo `.env` ainda não tiver sido criado, o Laravel oferecerá a opção de criá-lo automaticamente com o nome fornecido.

### Passo 7: Gerar a Key do Aplicativo
Execute o comando para gerar a chave da aplicação Laravel:
```
php artisan key:generate
```
**Obs.:** Caso pule esse passo, no momento em que iniciar o servidor uma tela no seu localhost será exibida com a mensagem `Your app key is missing` . Aqui você pode apenas clicar no botão contendo o texto `GENERATE APP KEY` e pronto.

### Passo 8: Iniciar o Servidor
Inicie o servidor local do Laravel:
```
php artisan serve
```
A aplicação estará disponível em `http://localhost:8000` .

## Endpoints 🌐 

### Autenticação
- Registrar: POST /api/register
- Login: POST /api/login

### Usuários
- Obter Usuário Logado: GET /api/user
- Atualizar Usuário: PUT /api/user
- Deletar Usuário: DELETE /api/user

### Endereços
- Listar Endereços: GET /api/addresses
    - Parâmetros opcionais: country, city, state
- Obter Endereço por ID: GET /api/address/{id}
- Criar Endereço: POST /api/address
- Atualizar Endereço: PUT /api/address/{id}
- Deletar Endereço: DELETE /api/address/{id}

## Documentação da API 📝

A documentação completa da API está disponível via Swagger. Após iniciar o servidor, acesse:
```
http://localhost:8000/api/documentation
```
![image](https://github.com/user-attachments/assets/df7f398b-62d1-43c5-b94e-ef67f352d99b)


## Testes Automatizados 🧪

O projeto inclui testes automatizados para garantir a qualidade do código. Para executar os testes, use:
```
php artisan test
```

## Contribuição

A metodologia utilizada para contribuição prevê:
- Criação de branches no formato `descrição-do-que-esta-sendo-feito`, sempre começando com um verbo no infinitivo.
- Abertura de Pull Requests com um breve descritivo do que será introduzido. Casos de testes quando necessário.
- Commits respeitando a organização dos arquivos. Exemplos semânticos das mensagens adotadas:
```JS
"fix: Fix bug on onboarding screen"
"feat: Add new functionality to the datatable"
"chore: Change text"
"refactor: Improve code regarding loop repetition"
"revert: Remove deprecated piece of code"
```
[Referência](https://gist.github.com/joshbuchea/6f47e86d2510bce28f8e7f42ae84c716)
