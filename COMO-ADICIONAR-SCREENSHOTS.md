# 📸 Como Adicionar as Screenshots Reais

## 🎯 Instruções para Upload das Imagens

### **Passo a Passo:**

1. **Capturar as telas** do sistema funcionando
2. **Salvar as imagens** com os nomes exatos:
   - `lista-clientes.png`
   - `novo-cliente.png` 
   - `editar-cliente.png`
   - `chatbot-funcionando.png`

3. **Fazer upload** para `assets/screenshots/`
4. **Remover os arquivos .md** temporários
5. **Fazer commit** das imagens

### **Comandos Git:**

```bash
# Adicionar as imagens PNG
git add assets/screenshots/*.png

# Remover placeholders .md
git rm assets/screenshots/*.md

# Commit das screenshots
git commit -m "feat: Adiciona screenshots reais do sistema

📸 Screenshots adicionadas:
- Lista de clientes com chatbot integrado
- Formulário de cadastro com ViaCEP
- Formulário de edição preenchido
- Chatbot IA funcionando em tempo real"

# Push para GitHub
git push origin main
```

### **📋 Checklist das Screenshots:**

#### **Lista de Clientes (lista-clientes.png)**
- [ ] Interface principal visível
- [ ] Tabela com dados dos clientes
- [ ] Filtros de busca aparentes
- [ ] Chatbot flutuante no canto
- [ ] Botões Editar/Excluir visíveis

#### **Novo Cliente (novo-cliente.png)**
- [ ] Formulário de cadastro aberto
- [ ] Campos de dados pessoais
- [ ] Seção de upload de imagem
- [ ] Campos de endereço com CEP
- [ ] Botão "Salvar" visível

#### **Editar Cliente (editar-cliente.png)**
- [ ] Formulário preenchido com dados
- [ ] Imagem atual do cliente (admin)
- [ ] Endereço completo preenchido
- [ ] Botões "Salvar/Cancelar" visíveis

#### **Chatbot Funcionando (chatbot-funcionando.png)**
- [ ] Widget do chatbot aberto
- [ ] Conversa com mensagens
- [ ] Resposta da IA visível
- [ ] Interface moderna do chat
- [ ] Status "Online" aparente

### **💡 Dicas para Boas Screenshots:**

- **Resolução:** Pelo menos 1200px de largura
- **Formato:** PNG para melhor qualidade
- **Conteúdo:** Mostrar a funcionalidade principal
- **Clareza:** Texto legível e interface nítida
- **Contexto:** Incluir elementos importantes da UI

---

**🎉 Após adicionar as screenshots, o README.md ficará completo com demonstrações visuais do sistema!**