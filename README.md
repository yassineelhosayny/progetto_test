[![Adverteaser · Filament Base](https://img.shields.io/badge/ADVERTEASER-FILAMENT--BASE-292524?style=for-the-badge&labelColor=B60D45&logo=github&logoColor=white)](#)

[![Vedi su Forge](https://img.shields.io/badge/VEDI_SU_FORGE-1A1A1A?style=flat-square&logo=bandcamp&logoColor=white)](https://forge.laravel.com/adverteaser/adverteaser-dev2/2953057)
[![Visita IL sito](https://img.shields.io/badge/VISITA_IL_SITO-1A1A1A?style=flat-square&logo=bandcamp&logoColor=white)](https://filament-base.dev2.adverteaser.com)

---

# Project Setup

## 1. Clonare la repository

```bash
git clone <repository-url>
cd <nome-progetto>
```

Scarica il progetto in locale ed entra nella relativa cartella.

## 2. Configurare il file `.env`

Copia il file di esempio:

```bash
cp .env.example .env
```

Su PowerShell:

```powershell
Copy-Item .env.example .env
```

Apri il file `.env` e configura almeno applicazione, database, email e lingua.

Esempio:

```env
APP_NAME="Filament Base"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=filament_base
DB_USERNAME=root
DB_PASSWORD=your_password

APP_LOCALE=it
APP_FALLBACK_LOCALE=it
```

Prima di continuare, crea nel database MySQL lo schema indicato in `DB_DATABASE`.

## 3. Installare le dipendenze

```bash
composer install
npm install
```

* `composer install` installa le dipendenze PHP definite nel `composer.lock`.
* `npm install` installa le dipendenze frontend definite nel `package-lock.json`.

## 4. Generare la chiave dell'applicazione

```bash
php artisan key:generate
```

Genera la chiave usata da Laravel per cifrare cookie, sessioni e altri dati sensibili.

## 5. Eseguire le migrazioni

```bash
php artisan migrate
```

Crea e aggiorna le tabelle del database.

## 6. Eseguire i seeder iniziali

```bash
php artisan db:seed --class=SuperAdminSeeder
php artisan db:seed --class=RolePermissionSeeder
```

* `SuperAdminSeeder` crea l'utente amministratore iniziale.
* `RolePermissionSeeder` crea e configura ruoli e permessi.

## 7. Creare il collegamento allo storage

```bash
php artisan storage:link
```

Crea il collegamento simbolico `public/storage`, necessario per rendere accessibili dal browser i file salvati nello storage pubblico.

## 8. Avviare il progetto

Avvia il frontend:

```bash
npm run dev
```

Compila gli asset con Vite e resta in ascolto delle modifiche.

In un secondo terminale, avvia Laravel:

```bash
php artisan serve
```

L'applicazione sarà normalmente disponibile su:

```text
http://127.0.0.1:8000
```

## Comandi completi

```bash
git clone <repository-url>
cd <nome-progetto>

cp .env.example .env

composer install
npm install

php artisan key:generate

php artisan migrate
php artisan db:seed --class=SuperAdminSeeder
php artisan db:seed --class=RolePermissionSeeder

php artisan storage:link

npm run dev
php artisan serve
```
