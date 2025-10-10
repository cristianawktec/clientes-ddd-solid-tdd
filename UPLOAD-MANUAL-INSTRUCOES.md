# 🚀 Upload Manual - Passo a Passo Detalhado

## 📝 Arquivos para Upload (4 arquivos críticos):

### 1. **diagnostico.php** (PRIMEIRO - para verificar estado atual)
- **Local:** `C:\xampp\htdocs\clientes-production\diagnostico.php`
- **Destino:** `/public_html/clientes/diagnostico.php`

### 2. **database.php** (configuração do banco)
- **Local:** `C:\xampp\htdocs\clientes-production\application\config\database.php`
- **Destino:** `/public_html/clientes/application/config/database.php`

### 3. **index.php** (view principal com Font Awesome)
- **Local:** `C:\xampp\htdocs\clientes-production\application\views\clientes\index.php`
- **Destino:** `/public_html/clientes/application/views/clientes/index.php`

### 4. **widget-simple.php** (widget do chatbot)
- **Local:** `C:\xampp\htdocs\clientes-production\application\views\chatbot\widget-simple.php`
- **Destino:** `/public_html/clientes/application/views/chatbot/widget-simple.php`

### 5. **Chatbot.php** (controlador principal)
- **Local:** `C:\xampp\htdocs\clientes-production\application\controllers\Chatbot.php`
- **Destino:** `/public_html/clientes/application/controllers/Chatbot.php`

---

## 🌐 Acesso ao cPanel:

1. **Login:** https://consultoriawk.com:2083
2. **Usuário:** con23128
3. **Senha:** 6y6-@Qw88-b)

---

## 📁 Processo de Upload via File Manager:

### Passo 1: Acessar File Manager
1. No cPanel, clique em **"File Manager"**
2. Vá para `/public_html/clientes/`

### Passo 2: Upload do Diagnóstico (PRIMEIRO)
1. Na pasta `/public_html/clientes/`, clique **Upload**
2. Selecione: `C:\xampp\htdocs\clientes-production\diagnostico.php`
3. Aguarde upload completar
4. **TESTE:** Acesse https://wk.consultoriawk.com/clientes/diagnostico.php

### Passo 3: Upload dos Outros Arquivos
Para cada arquivo:

**database.php:**
1. Navegue para `/public_html/clientes/application/config/`
2. Faça backup do atual (renomeie para `database.php.backup`)
3. Upload do novo `database.php`

**index.php:**
1. Navegue para `/public_html/clientes/application/views/clientes/`
2. Faça backup do atual (renomeie para `index.php.backup`)
3. Upload do novo `index.php`

**widget-simple.php:**
1. Navegue para `/public_html/clientes/application/views/chatbot/`
2. Upload do `widget-simple.php`

**Chatbot.php:**
1. Navegue para `/public_html/clientes/application/controllers/`
2. Upload do `Chatbot.php`

---

## ✅ Verificação Após Upload:

1. **Diagnóstico:** https://wk.consultoriawk.com/clientes/diagnostico.php
2. **Sistema:** https://wk.consultoriawk.com/clientes/cliente
3. **API Chatbot:** https://wk.consultoriawk.com/clientes/chatbot/test

---

## 🔧 Troubleshooting SSH (para depois):

O problema do SSH pode ser:
1. **Senha incorreta** - Confirme a senha no cPanel
2. **Chave SSH não configurada** - Você tem apenas `id_rsa_note.pub`
3. **SSH desabilitado** - Alguns planos Hostgator bloqueiam SSH

**Solução SSH (opcional):**
```bash
# Gerar nova chave SSH
ssh-keygen -t rsa -b 4096 -f ~/.ssh/hostgator_key

# Adicionar chave pública no cPanel > SSH Access
```

---

## 📞 Próximos Passos:

1. **AGORA:** Faça upload do `diagnostico.php` primeiro
2. **Verifique:** Acesse o diagnóstico via navegador
3. **Compartilhe:** O resultado do diagnóstico comigo
4. **Continue:** Upload dos outros arquivos conforme necessário

---

*Foque no upload manual por enquanto - é mais rápido e confiável!* 🚀