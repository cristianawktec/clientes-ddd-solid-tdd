# 📦 UPLOAD MANUAL PARA HOSTGATOR - SEM SSH

## 🎯 ARQUIVOS PARA UPLOAD

### **Lista dos 4 arquivos essenciais:**

1. **`application/config/database.php`**
   - Fonte: `database-para-hostgator.php` 
   - Destino: `/home3/con23128/wk.consultoriawk.com/clientes/application/config/database.php`

2. **`application/views/clientes/index.php`**
   - Fonte: `application/views/clientes/index.php`
   - Destino: `/home3/con23128/wk.consultoriawk.com/clientes/application/views/clientes/index.php`

3. **`application/views/chatbot/widget-simple.php`** ⭐ **NOVO ARQUIVO**
   - Fonte: `application/views/chatbot/widget-simple.php`
   - Destino: `/home3/con23128/wk.consultoriawk.com/clientes/application/views/chatbot/widget-simple.php`

4. **`application/controllers/Chatbot.php`**
   - Fonte: `application/controllers/Chatbot.php`
   - Destino: `/home3/con23128/wk.consultoriawk.com/clientes/application/controllers/Chatbot.php`

## 🔧 PASSO A PASSO - FILE MANAGER

### **PASSO 1: Acessar File Manager**
1. Entre no cPanel da Hostgator
2. Clique em **"Gerenciador de arquivos"** ou **"File Manager"**
3. Navegue até: `/home3/con23128/wk.consultoriawk.com/clientes/`

### **PASSO 2: Backup dos arquivos atuais**
1. Renomeie os arquivos existentes:
   - `database.php` → `database-backup.php`
   - `index.php` → `index-backup.php` 
   - `Chatbot.php` → `Chatbot-backup.php`

### **PASSO 3: Upload dos novos arquivos**
1. **Upload `database.php`:**
   - Copie o conteúdo de `database-para-hostgator.php`
   - Cole em `/application/config/database.php`

2. **Upload `index.php`:**
   - Substitua o arquivo em `/application/views/clientes/index.php`

3. **Upload `widget-simple.php`:** ⭐ **IMPORTANTE**
   - Crie arquivo em `/application/views/chatbot/widget-simple.php`
   - Se a pasta `chatbot` não existir, crie ela primeiro

4. **Upload `Chatbot.php`:**
   - Substitua o arquivo em `/application/controllers/Chatbot.php`

### **PASSO 4: Verificar permissões**
1. Clique com botão direito em cada arquivo
2. Escolha "Permissions" ou "Permissões"
3. Configure para `644` (rw-r--r--)

### **PASSO 5: Testar**
1. Acesse: `wk.consultoriawk.com/clientes/verificar-atualizacao`
2. Deve mostrar ✅ em todos os itens

## 📋 CONTEÚDO DOS ARQUIVOS

### **1. database.php (para colar no File Manager):**
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn' => '',
    'hostname' => 'br908.hostgator.com.br',
    'username' => 'con23128_admin',
    'password' => '10Kpormes!',
    'database' => 'con23128_clientes',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => TRUE,
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
```

### **2. Outros arquivos:**
Os outros 3 arquivos são grandes demais para colar aqui. Use o File Manager para fazer upload diretamente dos arquivos do seu computador.

## 🔍 VERIFICAÇÃO FINAL

Após o upload, acesse:
- `wk.consultoriawk.com/clientes/` - Deve mostrar o botão do chat
- `wk.consultoriawk.com/clientes/verificar-atualizacao` - Deve mostrar tudo ✅

## 🆘 TROUBLESHOOTING SSH

### **Para resolver o SSH posteriormente:**

1. **Baixar chave privada:**
   - No cPanel → SSH Access → Gerenciar chaves SSH
   - Baixe a chave privada correspondente

2. **Configurar no Windows:**
   ```powershell
   # Copie a chave para ~/.ssh/
   cp caminho-da-chave-baixada ~/.ssh/hostgator_key
   chmod 600 ~/.ssh/hostgator_key
   
   # Teste a conexão
   ssh -i ~/.ssh/hostgator_key con23128@br908.hostgator.com.br
   ```

3. **Ou use PuTTY:**
   - Converta a chave para formato .ppk
   - Configure no PuTTY

---

**🎯 PRIORIDADE: Faça o upload manual primeiro, SSH pode ser resolvido depois!**