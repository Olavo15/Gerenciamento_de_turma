start:               # Inicia e faz build dos containers
	docker compose up -d --build

stop:                # Encerra os containers
	docker compose down

fix-owner:           # Corrige permissões da pasta local
	sudo chown -R $$(whoami):$$(whoami) ./

remove-dep:
	sudo chown -R $$(whoami):$$(whoami) ./
	echo "🗑️ Removendo pasta vendor..."
	rm -rf ./vendor && echo "✅ vendor removido!"

	echo "📦 Removendo pasta node_modules..."
	rm -rf ./node_modules && echo "✅ node_modules removido!"

	echo "🎉 Limpeza concluída!"

install-composer:    # Instala dependências PHP
	docker exec -it $$(docker compose ps -q php) composer install

install-npm:         # Instala dependências JS
	docker exec -it npm npm install

php-bash:            # Acessa bash do container PHP
	docker exec -it $$(docker compose ps -q php) /bin/bash

pg-bash:             # Acessa bash do container Postgres
	docker exec -it $$(docker compose ps -q postgres) /bin/bash

fix-container-perms: # Corrige permissões nos containers
	docker exec -it $$(docker compose ps -q php) chown -R www-data:www-data storage
	docker exec -it $$(docker compose ps -q postgres) chown -R postgres:postgres /var/lib/postgresql/data

migrate:			 # Executa migrações
	docker exec -it $$(docker compose ps -q php) php artisan migrate

migrate-status:		 # Mostra status das migrações
	docker exec -it $$(docker compose ps -q php) php artisan migrate:status

clear-cache:		 # Executa migrações
	docker exec -it $$(docker compose ps -q php) php artisan config:clear
	docker exec -it $$(docker compose ps -q php) php artisan route:clear
	docker exec -it $$(docker compose ps -q php) php artisan view:clear
	docker exec -it $$(docker compose ps -q php) php artisan cache:clear
	docker exec -it $$(docker compose ps -q php) composer dump-autoload

init:				 # Inicia o ambiente completo
	@echo "🚀 Subindo containers e buildando imagens..." && \
	docker compose up -d --build && \
	\
	echo "📦 Instalando dependências PHP (composer) com retry..." && \
	for i in 1 2 3; do \
		docker exec -it $$(docker compose ps -q php) composer install && break || echo "Tentativa $$i falhou, tentando novamente..."; \
		sleep 3; \
	done && \
	\
	echo "📦 Instalando dependências Node.js (npm)..." && \
	docker exec -it npm npm install && \
	docker exec -it npm npm audit fix && \
	\
	echo "🔐 Liberando permissões das pastas..." && \
	sudo chown -R $$(whoami):$$(whoami) ./ && \
	docker exec -it $$(docker compose ps -q php) chown -R www-data:www-data storage && \
	docker exec -it $$(docker compose ps -q postgres) chown -R postgres:postgres /var/lib/postgresql/data && \
	\
	echo "📂 Executando migrações..." && \
	docker exec -it $$(docker compose ps -q php) php artisan migrate && \
	\
	echo "🗄️  Checando status das migrações..." && \
	docker exec -it $$(docker compose ps -q php) php artisan migrate:status && \
	\
	echo "🧹 Limpando caches..." && \
	docker exec -it $$(docker compose ps -q php) php artisan key:generate && \
	docker exec -it $$(docker compose ps -q php) php artisan config:clear && \
	docker exec -it $$(docker compose ps -q php) php artisan route:clear && \
	docker exec -it $$(docker compose ps -q php) php artisan view:clear && \
	docker exec -it $$(docker compose ps -q php) php artisan cache:clear && \
	\
	echo "⚡ Gerando autoload do composer..." && \
	docker exec -it $$(docker compose ps -q php) composer dump-autoload && \
	\
	echo "✅ Ambiente iniciado com sucesso!" && \
	echo "🌎 Acesse o site em: http://localhost:8081" && \
	echo "📑 Acesse a documentação da api em: http://localhost:8081/docs" && \
	echo "💾 Acesse o pgadmin: http://localhost:8090/login?next=/" && \
	echo "📧 Acesse o mailhog-dashboad: http://localhost:8025" && \
	echo "🐋 Acesse o portainer: http://localhost:9001" && \
	echo "🐛 Acesse o telescope: http://localhost:8081/telescope/"

reset-soft:          # Limpa containers, imagens do projeto e volumes órfãos
	@echo "🛑 Parando todos os containers do projeto..." && \
	docker-compose down --volumes --remove-orphans && \
	\
	echo "🧹 Removendo imagens do projeto..." && \
	docker images -q | xargs -r docker rmi -f && \
	\
	echo "🧹 Limpando volumes órfãos..." && \
	docker volume prune -f && \
	\
	echo "✅ Projeto resetado com sucesso!" && \
	echo "📦 Containers ativos:" && docker ps && \
	echo "📦 Containers parados:" && docker ps -a && \
	echo "📁 Volumes existentes:" && docker volume ls && \
	echo "🖼️  Imagens existentes:" && docker images && \
	echo "🌐 Redes existentes:" && docker network ls

reset-hard:          # Remove tudo do Docker (containers, imagens, volumes, redes)
	@echo "🛑 Parando todos os containers em execução..." && \
	docker ps -q | xargs -r docker stop && \
	\
	echo "🧹 Removendo todos os containers (inclusive parados)..." && \
	docker ps -a -q | xargs -r docker rm -f && \
	\
	echo "🧹 Removendo todas as imagens..." && \
	docker images -q | xargs -r docker rmi -f && \
	\
	echo "🔥 Removendo todos os volumes (CUIDADO!)..." && \
	docker volume ls -q | xargs -r docker volume rm && \
	\
	echo "🔌 Removendo redes não utilizadas..." && \
	docker network prune -f && \
	\
	echo "✅ Ambiente Docker totalmente resetado!" && \
	echo "📦 Containers ativos:" && docker ps && \
	echo "📦 Containers parados:" && docker ps -a && \
	echo "📁 Volumes existentes:" && docker volume ls && \
	echo "🖼️  Imagens existentes:" && docker images && \
	echo "🌐 Redes existentes:" && docker network ls
