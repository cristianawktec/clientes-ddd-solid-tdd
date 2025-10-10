# Sistema de Cadastro de Clientes - PHP MVC (CodeIgniter 3) + Chatbot IA

Este projeto é uma versão atualizada e melhorada do sistema de gerenciamento de clientes com endereço vinculado, implementado em **CodeIgniter 3**. Agora inclui um **chatbot inteligente com Google Gemini AI** para assistência aos usuários. A estrutura adota o padrão MVC tradicional do CI com uma organização *DDD-like* (camadas `domain` / `infrastructure` / `usecases`) para isolar regras de negócio e tornar o código testável e mais manutenível, seguindo princípios SOLID com Test-Driven Development (TDD).

## ✅ Funcionalidades

### 📋 Sistema de Clientes
* Listagem de clientes (com miniatura da imagem)
* Cadastro de clientes com upload de foto
* Edição de cliente (dados + endereço)
* Exclusão de cliente (propaga remoção do endereço)
* Validações de formulário (nome, email, telefone)
* Integração com API de CEP (ViaCEP) via serviço

### 🤖 Chatbot Inteligente (NOVO)
* **Assistente virtual** especializado no sistema de clientes
* **Integração com Google Gemini AI** para respostas inteligentes
* **Sistema híbrido**: IA + respostas básicas (fallback)
* **Interface moderna** com design responsivo
* **Widget flutuante** no canto da tela
* **Sessões persistentes** para continuidade da conversa
* **API REST** para comunicação em tempo real

### 🏗️ Arquitetura
* Estrutura preparada para DDD: `domain`, `infrastructure`, `usecases`
* Testes unitários (PHPUnit) cobrindo criação, edição e exclusão de cliente
* Injeção de dependências nos serviços/repos (facilita testes e mantém SOLID)
* Deploy automatizado via SSH para servidor Hostgator

## ⚙️ Requisitos

* PHP 8.0+ (versões compatíveis com CodeIgniter 3)
* MySQL / MariaDB
* XAMPP / MAMP / WAMP / LAMP (ou servidor equivalente)
* Composer (necessário para PHPUnit)
* **Google Gemini API Key** (para chatbot IA)
* Pastas do projeto dentro do `htdocs`/`www` do servidor
* **SSH configurado** (para deploy em produção)

## 🗂️ Estrutura do projeto

```
clientes/
├── app/                                    # PSR-4 (modelos/adapters modernos) 
│   ├── Models/ 
│   ├── Repositories/ 
│   └── Services/
├── application/
│   ├── config/
│   ├── controllers/
│   │   ├── Cliente.php
│   │   └── Chatbot.php                     # NOVO - Controlador do chatbot IA
│   ├── domain/
│   │   ├── entities/
│   │   │   └── ClienteEntity.php
│   │   └── services/
│   │       └── ClienteService.php          # regras de negócio
│   ├── helpers/
│   │   └── ddd_autoload_helper.php         # autoload das classes DDD
│   ├── infrastructure/
│   │   ├── repositories/
│   │   │   ├── ClienteRepository.php
│   │   │   └── CepRepository.php
│   │   └── services/
│   │       ├── CepService.php              # consulta ViaCEP + cache DB
│   │       └── ClienteService.php          # adapter para CI/Model
│   ├── libraries/
│   │   └── Curl.php
│   ├── models/
│   │   ├── ClienteModel.php
│   │   └── CepModel.php
│   ├── usecases/
│   │   └── CadastrarCliente.php
│   └── views/
│       ├── chatbot/                        # NOVO - Views do chatbot
│       │   ├── widget-simple.php           # Widget responsivo do chatbot
│       │   ├── widget.php                  # Widget alternativo
│       │   └── test.php                    # Teste da API
│       └── clientes/
│           ├── index.php                   # ATUALIZADO - com widget integrado
│           ├── create.php
│           └── edit.php
├── tests/                                  # testes PHPUnit
│   └── ClienteEntityTest.php
├── uploads/                                # imagens enviadas
├── database/
│   └── clientes.sql
├── bootstrap.php                           # inicialização para PHPUnit
├── phpunit.xml                             # configuração de testes
└── system/                                 # CodeIgniter core
```

> **Observação:** o `domain` guarda entidade e regras; `infrastructure` concentra os adaptadores (modelos/serviços que conversam com CI e o banco). O helper `ddd_autoload_helper.php` facilita carregar classes por convenção.

## 🔧 Banco de Dados

Se preferir, importe o `database/clientes.sql`. Abaixo o SQL mínimo para criar as tabelas:

```sql
CREATE TABLE `clientes` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `telefone` VARCHAR(20) NOT NULL,
  `imagem` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `endereco` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_cliente` INT NOT NULL,
  `cep` VARCHAR(10) DEFAULT NULL,
  `logradouro` VARCHAR(150) DEFAULT NULL,
  `numero` VARCHAR(20) DEFAULT NULL,
  `complemento` VARCHAR(150) DEFAULT NULL,
  `bairro` VARCHAR(100) DEFAULT NULL,
  `localidade` VARCHAR(100) DEFAULT NULL,
  `uf` VARCHAR(2) DEFAULT NULL,
  `ibge` VARCHAR(20) DEFAULT NULL,
  `gia` VARCHAR(20) DEFAULT NULL,
  CONSTRAINT `fk_endereco_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `clientes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## 🤖 Chatbot com Google Gemini AI

### Funcionalidades do Chatbot

O sistema inclui um chatbot inteligente integrado que oferece assistência aos usuários sobre o sistema de clientes.

### Configuração da API

1. **Obter API Key do Google Gemini:**
   - Acesse: https://ai.google.dev/
   - Gere uma API Key gratuita
   - Configure no controlador `application/controllers/Chatbot.php`

2. **Configuração no código:**
```php
// Em application/controllers/Chatbot.php
private $gemini_api_key = 'SUA_API_KEY_AQUI';
```

### Características Técnicas

- **Modelo utilizado:** `gemini-2.5-flash` (otimizado para eficiência)
- **Sistema híbrido:** IA principal + fallback inteligente
- **Controle de sessão:** Mantém contexto da conversa
- **API REST:** Endpoints JSON para comunicação
- **Interface responsiva:** Widget moderno com Bootstrap 5

### Endpoints da API

```bash
# Iniciar sessão do chatbot
POST /chatbot/start

# Enviar mensagem
POST /chatbot/message
{
  "message": "Como cadastrar um cliente?"
}

# Verificar status
GET /chatbot/test

# Finalizar sessão
POST /chatbot/end
```

### Respostas Inteligentes

O chatbot está configurado para responder sobre:
- Cadastro e edição de clientes
- Upload de fotos e documentos
- Consulta de endereços via CEP
- Funcionalidades do sistema
- Navegação e uso da interface

### Sistema de Fallback

Quando a API do Google Gemini não está disponível, o sistema automaticamente utiliza respostas básicas pré-configuradas, garantindo que o chatbot sempre funcione.

## 🚀 Deploy e Produção

### SSH Deploy (Hostgator)

O projeto está configurado para deploy automático via SSH:

```bash
# Conectar via SSH
ssh -i ~/.ssh/hostgator_key usuario@servidor.hostgator.com.br

# Upload de arquivos via SCP
scp -i ~/.ssh/hostgator_key arquivo.php usuario@servidor:~/destino/
```

### Arquivos de Deploy

- `DEPLOY-HOSTGATOR-INSTRUCOES.md` - Instruções completas de deploy
- `UPLOAD-MANUAL-HOSTGATOR.md` - Upload manual via cPanel
- `diagnostico.php` - Verificação do ambiente do servidor

## 🏗️ Arquitetura e Princípios SOLID

Este projeto implementa uma arquitetura limpa baseada em **DDD (Domain-Driven Design)** e **princípios SOLID**, garantindo código testável, manutenível e extensível.

### Estrutura DDD-like

```
application/
├── domain/                     # Camada de domínio (regras de negócio)
│   ├── entities/              # Entidades do domínio
│   └── services/              # Serviços de domínio
├── infrastructure/            # Camada de infraestrutura
│   ├── repositories/          # Implementações de repositórios
│   └── services/             # Serviços externos (APIs, etc.)
├── usecases/                 # Casos de uso da aplicação
└── controllers/              # Controladores (camada de apresentação)
```

### Princípios SOLID Implementados

#### S - Single Responsibility Principle
- **`ClienteModel`**: Responsável apenas pelo acesso ao banco de dados
- **`CepService`**: Focado exclusivamente na consulta de CEP e cache
- **`ClienteService`**: Gerencia regras de negócio de clientes
- **`Chatbot`**: Controlador dedicado exclusivamente ao chatbot IA

#### O - Open/Closed Principle
- **Interfaces bem definidas**: `ClienteRepositoryInterface` permite extensão
- **Novos repositórios**: MySQL, InMemory, API externa sem alterar código existente
- **Serviços plugáveis**: Fácil adição de novos serviços de IA ou APIs

#### L - Liskov Substitution Principle
- **Substituição transparente**: `ClienteRepositoryMysql` ↔ `ClienteRepositoryInMemory`
- **Interfaces consistentes**: Qualquer implementação funciona nos testes
- **Polimorfismo**: Comportamento previsível em todas as implementações

#### I - Interface Segregation Principle
- **Interfaces específicas**: Métodos focados por responsabilidade
- **Sem dependências desnecessárias**: Cada consumidor usa apenas o que precisa
- **Contratos limpos**: Interfaces pequenas e coesas

#### D - Dependency Inversion Principle
- **Abstrações primeiro**: Controllers dependem de interfaces, não implementações
- **Injeção de dependências**: Manual nos controllers, automática nos testes
- **Baixo acoplamento**: Fácil troca de implementações

## 🧪 Testes (TDD)

Este projeto utiliza PHPUnit para testes automatizados.

### 1. Instalação
```bash
composer install
```

### 2. Arquivo bootstrap.php

Criado um arquivo `bootstrap.php` para contornar o "No direct script access allowed" do CodeIgniter nos testes:

```php
<?php
if (!defined('BASEPATH')) {
    define('BASEPATH', __DIR__);
}
if (!defined('APPPATH')) {
    define('APPPATH', __DIR__ . '/application/');
}
require __DIR__ . '/vendor/autoload.php';
```

### 3. Executar testes
```bash
php vendor/bin/phpunit --testdox
```

### 4. Exemplo de saída
```
PHPUnit 9.6.29 by Sebastian Bergmann and contributors.

* Cliente Entity
✔ Deve criar cliente com dados validos
✔ Deve editar cliente
✔ Deve excluir cliente

Time: 00:00.015, Memory: 6.00 MB

OK (3 tests, 6 assertions)
```

## 🔎 Debug - Problemas Comuns

### "No direct script access allowed"
* **Causa:** CodeIgniter bloqueando acesso direto
* **Solução:** Resolvido pelo `bootstrap.php` com as constantes `BASEPATH` e `APPPATH`

### Imagem não aparece
* **Verificação:** Se o arquivo existe em `uploads/`
* **Configuração:** Se `base_url` está correto no CodeIgniter

### Tabela enderecos não encontrada
* **Correção:** Agora a tabela correta é `endereco` (singular)
* **Atenção:** Verificar estrutura do banco de dados

### Sessão indefinida ($this->session)
* **Solução:** Garantir que a lib `session` está no autoload
* **Verificação:** Configurações de sessão no `config.php`

## 🔧 Regras SOLID Aplicadas (Resumo Prático)

### S (Single Responsibility)
Cada classe/arquivo tem uma responsabilidade única:
- **`ClienteModel`**: Acesso ao banco de dados
- **`CepService`**: Consulta de CEP e cache
- **`ClienteService`**: Regras de atualização e coordenação
- **`Chatbot`**: Controlador exclusivo do chatbot IA

### O (Open/Closed)
Serviços e repositórios estruturados por interface (ex.: `ClienteRepositoryInterface`) permitem adicionar novos repositórios (MySQL / InMemory) sem alterar consumidores.

### L (Liskov Substitution)
Implementações de repositórios seguem a interface; podemos trocar `ClienteRepositoryMysql` por `ClienteRepositoryInMemory` nos testes sem quebrar o código.

### I (Interface Segregation)
Interfaces pequenas e focadas (apenas métodos necessários por consumidor).

### D (Dependency Inversion)
Controllers e services dependem de abstrações (interfaces) e recebem implementações via injeção (manual nos controllers ou via helper de autoload).

## 🧠 Observações

* **Projeto em CodeIgniter 3**, atualizado para suportar práticas modernas de SOLID e DDD + TDD
* **Chatbot inteligente** com Google Gemini AI integrado
* **Deploy automatizado** via SSH para servidores de produção
* **Arquitetura mais limpa e testável**
* **Sistema híbrido** que garante funcionamento mesmo com falhas na API externa
* **Ideal para avaliação** de desenvolvedor backend full stack

## 🎯 Demonstração

**🌐 Sistema em Produção:** https://wk.consultoriawk.com/clientes/

**📸 Principais Funcionalidades:**

### 💼 Gerenciamento de Clientes
- ✅ Listagem com filtros avançados
- ✅ Cadastro com upload de fotos
- ✅ Edição e exclusão segura
- ✅ Integração automática com ViaCEP

### 🤖 Chatbot Inteligente
- ✅ Widget flutuante responsivo
- ✅ Respostas contextuais sobre o sistema
- ✅ Integração com Google Gemini AI
- ✅ Sistema de fallback inteligente

### 🚀 Deploy Profissional
- ✅ SSH configurado para produção
- ✅ Scripts de deploy automatizado
- ✅ Ambiente Hostgator otimizado
- ✅ Monitoramento e diagnóstico

## 📸 Screenshots do Sistema

### 💼 Lista de Clientes
![Lista de Clientes](./uploads/lista-clientes.png?v=2)
*Interface principal com listagem, filtros e chatbot integrado*

### 📝 Cadastro de Cliente
![Novo Cliente](./uploads/novo-cliente.png?v=2)
*Formulário de cadastro com upload de imagem e integração ViaCEP*

### ✏️ Edição de Cliente
![Editar Cliente](./uploads/editar-cliente.png?v=2)
*Edição completa de dados pessoais e endereço*

### 🤖 Chatbot IA em Ação
![Chatbot Funcionando](./uploads/chatbot-funcionando.png?v=2)
*Assistente virtual com Google Gemini AI respondendo sobre o sistema*

### Funcionalidades Demonstradas:
- ✅ **Interface moderna** com Bootstrap 5 e Font Awesome
- ✅ **CRUD completo** de clientes com validações
- ✅ **Upload de imagens** para perfil dos clientes
- ✅ **Integração ViaCEP** para busca automática de endereços
- ✅ **Chatbot inteligente** com respostas contextuais
- ✅ **Filtros dinâmicos** por nome, email, telefone e UF
- ✅ **Design responsivo** para desktop e mobile

## ✍️ Autor

Desenvolvido por **Cristian Marques** — https://wk.consultoriawk.com/

Projeto adaptado para DDD-like com separação clara entre domain, infrastructure, testes unitários e **chatbot inteligente com Google Gemini AI**.

### 🚀 Tecnologias Utilizadas

- **Backend:** PHP 8.0+, CodeIgniter 3, MySQL
- **Frontend:** Bootstrap 5, Font Awesome, jQuery
- **IA:** Google Gemini API 2.5-flash
- **Testes:** PHPUnit com TDD
- **Deploy:** SSH, SCP, Hostgator
- **Arquitetura:** DDD-like, SOLID, MVC