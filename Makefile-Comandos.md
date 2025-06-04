# 📘 Comandos de Automação com `make`

Automatizações úteis para desenvolvimento com Laravel em Docker.

---

## 🚀 `start`

**Descrição:**  
Inicia e faz o build dos containers do projeto.

**Uso:**  
```bash
make start
```

---

## 🛑 `stop`

**Descrição:**  
Encerra todos os containers do projeto.

**Uso:**  
```bash
make stop
```

---

## 🧰 `fix-owner`

**Descrição:**  
Corrige as permissões da pasta local, definindo o usuário atual como proprietário.

**Uso:**  
```bash
make fix-owner
```

---

## 🧹 `remove-dep`

**Descrição:**  
Remove as pastas `vendor` (PHP) e `node_modules` (JavaScript), limpando as dependências locais.

**Uso:**  
```bash
make remove-dep
```

---

## 📦 `install-composer`

**Descrição:**  
Instala as dependências do PHP via Composer dentro do container.

**Uso:**  
```bash
make install-composer
```

---

## 📦 `install-npm`

**Descrição:**  
Instala as dependências JavaScript via NPM no container responsável pelo frontend.

**Uso:**  
```bash
make install-npm
```

---

## 🐘 `php-bash`

**Descrição:**  
Abre um terminal bash dentro do container PHP para executar comandos diretamente.

**Uso:**  
```bash
make php-bash
```

---

## 🐘 `pg-bash`

**Descrição:**  
Abre um terminal bash dentro do container do PostgreSQL.

**Uso:**  
```bash
make pg-bash
```

---

## 🔧 `fix-container-perms`

**Descrição:**  
Corrige permissões das pastas `storage` e dos dados do banco de dados dentro dos containers.

**Uso:**  
```bash
make fix-container-perms
```

---

## 🗄️ `migrate`

**Descrição:**  
Executa as migrações do banco de dados Laravel.

**Uso:**  
```bash
make migrate
```

---

## 📊 `migrate-status`

**Descrição:**  
Exibe o status atual das migrações aplicadas ou pendentes no Laravel.

**Uso:**  
```bash
make migrate-status
```

---

## 🧼 `clear-cache`

**Descrição:**  
Limpa todos os caches da aplicação Laravel: config, rotas, views, cache geral, além de gerar o autoload novamente.

**Uso:**  
```bash
make clear-cache
```

---

## 🧪 `init`

**Descrição:**  
Executa todo o processo de inicialização do projeto: builda os containers, instala dependências, corrige permissões, aplica migrações, limpa cache e gera autoload.

**Uso:**  
```bash
make init
```

---

## 🔄 `reset-soft`

**Descrição:**  
Limpa containers, imagens do projeto e volumes órfãos sem afetar recursos globais do Docker.

**Uso:**  
```bash
make reset-soft
```

---

## 💥 `reset-hard`

**Descrição:**  
Remove **tudo** do Docker: containers, imagens, volumes e redes. Útil para limpar o ambiente por completo (⚠️ cuidado ao usar!).

**Uso:**  
```bash
make reset-hard
```

---