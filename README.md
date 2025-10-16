# 🔐 Authentication with Laravel Sanctum

Uma aplicação simples desenvolvida com **Laravel** para fins de **estudo**, demonstrando como implementar autenticação de usuários usando **Laravel Sanctum** (API tokens).

> Este projeto serve como base para aprendizado de autenticação moderna em APIs RESTful com Laravel.

---

## 📘 Visão Geral

O objetivo deste projeto é praticar:

* Configuração do **Sanctum** em uma API Laravel;
* Criação de rotas de autenticação (`register`, `login`, `logout`);
* Proteção de endpoints com middleware `auth:sanctum`;
* Retorno de respostas **JSON** para integração com aplicações front-end (SPA ou cliente HTTP).

---

## 🧱 Funcionalidades

* Registro de novos usuários (`name`, `email`, `password`);
* Login e emissão de **token Sanctum**;
* Logout com revogação de token;
* Endpoint protegido de exemplo;
* Validações e respostas padronizadas.

---

## ⚙️ Tecnologias Utilizadas

* **Laravel 10+**
* **PHP 8+**
* **Laravel Sanctum**
* **MySQL** (ou outro banco relacional)
* **Composer**

---

## 🛠️ Instalação e Setup

Clone o repositório e configure o ambiente local:

```bash
# Clone o projeto
git clone https://github.com/RafaelSotero-dev/authentication_with_laravel_sanctum.git
cd authentication_with_laravel_sanctum

# Instale as dependências PHP
composer install

# Copie o arquivo de ambiente
cp .env.example .env

# Configure o .env
# - DB_DATABASE, DB_USERNAME, DB_PASSWORD
# - APP_URL
# - SANCTUM_STATEFUL_DOMAINS (caso use SPA)

# Gere a chave da aplicação
php artisan key:generate

# Rode as migrations
php artisan migrate

# Inicie o servidor local
php artisan serve
```

O servidor estará disponível em:
👉 **[http://localhost:8000](http://localhost:8000)**

---

## 🚀 Endpoints da API

As rotas de autenticação seguem o padrão:
`/api/auth/*`

| Método   | Rota                 | Descrição                        | Protegida?             |
| -------- | -------------------- | -------------------------------- | ---------------------- |
| **POST** | `/api/auth/register` | Registrar novo usuário           | ❌ Não                  |
| **POST** | `/api/auth/login`    | Autenticar usuário e gerar token | ❌ Não                  |
| **GET**  | `/api/auth/user`     | Retornar dados do usuário logado | ✅ Sim (`auth:sanctum`) |
| **POST** | `/api/auth/logout`   | Logout / Revogar token atual     | ✅ Sim (`auth:sanctum`) |

---

### 🔑 Cabeçalhos necessários para rotas protegidas

```
Authorization: Bearer {token}
Accept: application/json
```

---

## 🧪 Testando com Postman / Insomnia

1. Faça um **POST** em `/api/auth/register` para criar um novo usuário.
2. Faça **POST** em `/api/auth/login` com email/senha e obtenha o token.
3. Envie o token no header `Authorization` para acessar `/api/auth/user`.
4. Faça **POST** em `/api/auth/logout` para revogar o token.

---

## 🧭 Estrutura do Projeto

```
app/
 ├── Http/
 │   ├── Controllers/
 │   │   └── AuthController.php
 │   └── Requests/
 │       ├── RegisterRequest.php
 │       └── LoginRequest.php
 ├── Models/
 │   └── User.php
routes/
 └── api.php
```

---

## 🧩 Possíveis Melhorias Futuras

* Implementar refresh token;
* Adicionar papéis e permissões (roles);
* Autenticação via cookies para SPA;
* Validações mais robustas e mensagens customizadas;
* Testes automatizados (unitários e de integração);
* Integração com front-end (ex: React, Vue, Next.js).

---

## 📝 Licença

Este projeto está sob licença **MIT** — use, estude e adapte livremente.

---

Quer que eu adicione agora uma versão com **badges e visual aprimorado** (ex: Laravel, PHP, License, Status)?
Posso gerar a versão final estilizada com Markdown decorativo para GitHub.
