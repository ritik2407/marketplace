# Marketplace - OLX Style Classifieds Platform

A modern, full-featured online classifieds and marketplace web application built with **Laravel 12 (PHP 8.3)**, **MySQL**, **Tailwind CSS v4**, and **Laravel Sanctum**.

---

## Key Features

- **User Authentication**: Web session auth + Laravel Sanctum API token authentication (`/api/register`, `/api/login`, `/api/user`).
- **Post & Browse Ads**:
  - Products and Services listings with Title, Description, Category, Subcategory, Price (Negotiable toggle), Condition (Brand New, Like New, Good, Fair), and Contact Info (Phone/WhatsApp/Email).
  - Multi-image uploads with primary cover photo selection and gallery management.
- **Location Hierarchy & Cascading Filters**:
  - Country → State → City → Area dynamic dropdown selection.
  - Dedicated SEO landing pages for Categories (`/category/{slug}`), Cities (`/city/{slug}`), and City + Category combinations (`/city/{city_slug}/category/{category_slug}`).
- **User Dashboard**:
  - Manage active, sold, and inactive ads.
  - Track inquiries received from buyers.
  - Save favorite listings.
- **Admin Control Center (`/admin`)**:
  - Manage all marketplace listings (edit, change status, toggle featured, manage photo galleries, delete).
  - Manage users and grant/revoke admin access.
  - Manage categories and subcategories.
  - Manage cities (popular city flags) and areas.
  - Manage customer inquiries.

---

## Demo Credentials

| Role | Email | Password | Access |
|---|---|---|---|
| **Administrator** | `admin@marketplace.com` | `password123` | Full Admin Center (`/admin`) + Frontend |
| **Verified Seller** | `rahul@example.com` | `password123` | User Dashboard & Ad Posting |

---

## Getting Started

### Option 1: Run with Docker Compose (Recommended)

#### 1. Clone the repository
```bash
git clone https://github.com/ritik2407/marketplace.git
cd marketplace
```

#### 2. Environment Setup
```bash
cp .env.example .env
```

#### 3. Build & Start Docker Containers
```bash
docker compose up -d --build
```

#### 4. Run Setup Commands inside the App Container
```bash
# Install PHP dependencies
docker compose exec app composer install

# Generate application key
docker compose exec app php artisan key:generate

# Run database migrations and seed realistic sample data
docker compose exec app php artisan migrate --seed

# Create storage symlink for uploaded ad photos
docker compose exec app php artisan storage:link

# Install and build frontend assets
docker compose exec app npm install
docker compose exec app npm run build
```

#### 5. Open in Browser
- **Marketplace Web App**: [http://localhost:8000](http://localhost:8000)
- **Admin Portal**: [http://localhost:8000/admin](http://localhost:8000/admin)

To stop the containers:
```bash
docker compose down
```

---

### Option 2: Run Locally (Without Docker)

#### Prerequisites
- PHP 8.3+ with extensions: `pdo_mysql`, `mbstring`, `gd`, `zip`, `bcmath`, `intl`
- MySQL 8.0+
- Composer 2+
- Node.js 20+ & npm

#### 1. Install Dependencies
```bash
composer install
npm install
```

#### 2. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```
Edit `.env` with your local MySQL credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marketplace
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### 3. Run Migrations & Seeders
```bash
php artisan migrate --seed
php artisan storage:link
```

#### 4. Compile Assets & Start Development Server
In one terminal:
```bash
npm run dev
```

In a second terminal:
```bash
php artisan serve --port=8000
```

Access the app at [http://localhost:8000](http://localhost:8000).

---

## Production / Nginx Reverse Proxy with SSL

If deploying behind a host Nginx reverse proxy with SSL (e.g. Let's Encrypt / Certbot), proxy traffic to the Docker container port (`8000`):

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;

    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;

    client_max_body_size 25M;

    location / {
        proxy_pass http://127.0.0.1:8000;
        proxy_http_version 1.1;

        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_set_header X-Forwarded-Host $host;
        proxy_set_header X-Forwarded-Port $server_port;
    }
}
```

---

## Running Automated Tests

Run the PHPUnit feature and unit test suite:
```bash
php artisan test
```

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
