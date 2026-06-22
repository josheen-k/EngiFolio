# EngiFolio
ICT Project, Sem 1 , 2026, AU

## Install Steps Linux (debian based)
### Backend PHP and Laravel 10
Update and install dependencies:
`sudo apt update && sudo apt upgrade -y`
`sudo apt install software-properties-common ca-certificates lsb-release apt-transport-https -y`

Add PHP PPA and install PHP 8.4 and extensions
`sudo add-apt-repository ppa:ondrej/php -y`
`sudo apt update`
`sudo apt install php8.4 php8.4-cli php8.4-fpm php8.4-mysql php8.4-xml php8.4-curl php8.4-gd php8.4-mbstring php8.4-zip php8.4-bcmath php8.4-intl php8.4-tokenizer -y`

Install Composer
Used to run back end
`curl -sS https://getcomposer.org/installer | php`
`sudo mv composer.phar /usr/local/bin/composer`

### AWS
`curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"`
`unzip awscliv2.zip`
`sudo ./aws/install`

Configure credentials
`aws configure`

### Run in backend folder
`composer install`
`cp .env.example .env`
`php artisan key:generate`
`php artisan migrate`
`php artisan storage:link`

### Run in frontend folder
`sudo apt install nodejs npm`
`npm install`


## Running The App
Open two terminals. In the first terminal run:
`cd backend`
`php artisan serve`
In the second terminal run:
`cd frontend`
`npm run dev`

## Install Steps Windows using install script

### STEPS:
- Download engifolio as zip file from github and unzip it
- Run terminal/powershell as admin from windows menu
- ```cd``` into project folder where the frontend, backend and this file are in, for example: if you extracted the zip file in Downloads folder and the unzipped folder is called Engifolio then you would do ```cd ~/Downloads/Engifolio/```
- Run this cmd  ```powershell -ExecutionPolicy Bypass -File install.ps1```

## Populating The Database
To populate the database with set sample data, run this command from the backend folder.
`php artisan migrate:fresh --seed`

## Install For PDF Export
To install requirements for PDF Exports run this command in the backend folder
`composer require barryvdh/laravel-dompdf`

## Link storage URL
Run in backend: `php artisan storage:link` to create a link from public/storage to storage/apps/public
Add `APP_URL=http://localhost:8000` to `EngiFolio/backend/.env`
