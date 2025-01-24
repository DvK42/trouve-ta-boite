# Étape 1 : Base pour PHP et Composer
FROM php:8.2-cli AS php-base

# Installer les outils nécessaires pour Composer, Symfony et PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    libpq-dev \
    libicu-dev \
    default-mysql-client \
    && docker-php-ext-configure intl \
    && docker-php-ext-install zip pdo pdo_pgsql pdo_mysql intl \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest \
    && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

WORKDIR /app

RUN apt-get update && apt-get install -y postgresql-client


# Étape 2 : Ajouter PostgreSQL pour la base de données
FROM postgres:16-alpine AS postgres-base

# Configurer PostgreSQL (les variables seront surchargées via docker-compose)
ENV POSTGRES_USER=app \
    POSTGRES_PASSWORD=ChangeThisPassword \
    POSTGRES_DB=app

VOLUME ["/var/lib/postgresql/data"]

# Étape 3 : Ajouter Mailpit pour le service mail
FROM axllent/mailpit AS mailpit-base

# Exposer les ports SMTP et Web
EXPOSE 1025 8025

# Étape finale : Rassembler tout (uniquement PHP dans cette image)
FROM php-base AS final

# Copier les fichiers de l'application (volumes remplacent souvent cette étape)
COPY . /app

# Commande par défaut pour garder le conteneur actif
CMD ["php", "-a"]
