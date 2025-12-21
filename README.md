# DonasiKita - Platform Crowdfunding & Donasi Online

## Setup Development

1. Clone repository:
```bash
git clone https://github.com/DonasiKita/donasi-kita.git
cd donasi-kita
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
