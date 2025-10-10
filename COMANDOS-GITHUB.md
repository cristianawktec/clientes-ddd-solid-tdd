# 🚀 Comandos para conectar ao GitHub

# Substitua SEU_USUARIO e NOME_DO_REPOSITORIO pelos valores corretos

# 1. Adicionar repositório remoto
git remote add origin https://github.com/SEU_USUARIO/NOME_DO_REPOSITORIO.git

# 2. Verificar se foi adicionado
git remote -v

# 3. Fazer o push inicial
git push -u origin master

# OU se o GitHub criou com 'main' como branch padrão:
git branch -M main
git push -u origin main

# 4. Verificar no GitHub se os arquivos apareceram