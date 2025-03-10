# Project Setup Guide

## Prerequisites
Before you begin, ensure you have the following installed:
- Docker
- Docker Compose (comes by default with Docker Desktop)

## Step-by-Step Installation

### 1. Clone the Repository
```bash
git clone https://github.com/DavidOrtegaFarrerons/pill-reminder.git
cd pill-reminder
```

### 2. Prepare the Environment
Copy the development environment file:
```bash
cp .env.dev .env.dev.local
```

### 3. Build and Start the Project
Install dependencies and start the services:
```bash
cd docker && docker-compose up -d --build
```

### 4. Verify Installation
Check if all services are running correctly:
```bash
docker-compose ps
```

### 5. Install composer dependencies
Run composer install --dev (run it without --dev if you want to deploy it to a production environment) to install all dependencies:
```bash
docker-compose exec api composer install --dev
```


### 6. Update Environment Variables
Update the `.env.dev.local` file with the necessary JWT variables (If you don't do it, you won't be able to generate the JWT secret and public key later on):
```
# JWT Keys
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=your_passphrase
```

For CORS issues, set the frontend domain, the default for pill-reminder-fe is:
```
CORS_ALLOW_ORIGIN=localhost:5173
```

For the DB configure it also in the env, default for the docker is:
````
DATABASE_URL="mysql://db:db@127.0.0.1:3306/db?serverVersion=10.5.8-MariaDB"
````

### 7. Generate JWT Key Pairs
Generate the JWT key pairs using the following command (Verify that you added the env variables with values first):
```bash
docker-compose exec api php bin/console lexik:jwt:generate-keypair
```

### 8. Generate JWT Key Pairs
Run the migrations to generate the database schema:
```bash
docker-compose exec api php bin/console doctrine:migrations:migrate
```

### 9. Enjoy
With this setup, you will now be running the entire Pill reminder API!



