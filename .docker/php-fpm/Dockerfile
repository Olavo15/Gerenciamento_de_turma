FROM php:8.4-fpm-alpine

# Definir argumento para o ambiente de execução
ARG APP_ENV=local

# Definir a variável de ambiente
ENV APP_ENV=$APP_ENV

# Definir diretório de trabalho
WORKDIR /app

RUN apk add nano bash

# Instalar dependências do sistema
RUN apk --no-cache add \
    autoconf \
    g++ \
    make \
    libtool \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zlib-dev \
    postgresql-dev \
    zip \
    linux-headers \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mysqli pdo pdo_mysql pdo_pgsql zip

# Instalar a extensão Redis
RUN apk --no-cache add libzip-dev \
    && pecl install redis \
    && docker-php-ext-enable redis



# Instalar e configurar Xdebug
RUN if [ "$APP_ENV" = "local" ]; then pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug,coverage" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.idekey=VSCODE" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.log=/var/www/html/xdebug.log" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && touch /var/www/html/xdebug.log \
    && chmod 775 /var/www/html/xdebug.log; \
    fi

# Remover linux-headers após a instalação do Xdebug
RUN apk del linux-headers

# Definir o maxímo de execução (em segundos, e.g., 300 segundos (5 minutos)
RUN echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/docker-php-max-execution-time.ini

# Instalar Composer
RUN apk --no-cache add wget curl git && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# Definir diretório como /app seguro
RUN git config --global --add safe.directory /app

# Expor portas necessárias
EXPOSE 9000 9003

# Comando padrão ao iniciar o container
ENTRYPOINT ["sh", "-c", "composer install && php-fpm"]
