# CashCore – Profit First Financial Intelligence

> **"Das Tool zwingt dich, dein Geld bewusst zu sehen, zu bewerten und aktiv umzulenken."**
>
> The tool forces you to consciously see, evaluate, and redirect your money.

CashCore is a Profit First financial intelligence SaaS built with Laravel 10. It helps entrepreneurs see where their money really goes, detect cash leaks, score every expense, unlock hidden capital, and permanently change financial behavior.

Full **English/German (EN/DE)** support built in from day one.

---

## Features

### 1. Cash Transparency Engine
- Import bank & accounting data (CSV)
- Auto-categorize income & expenses
- Dashboard with KPIs: Cost %, Profit %, Overhead %
- Chart.js visualizations (monthly trend, expense breakdown)

### 2. Cash Leak Detection
- Algorithmic detection of rising costs, unused subscriptions, dead expenses
- Leak Score (0–100)
- Actionable list: resolve or ignore each leak

### 3. Expense Value Scoring
- 4-dimension scoring per expense: Revenue, Efficiency, Strategic, Usage (0–10 each)
- Total score out of 40
- Auto-recommendation: Keep / Reduce / Eliminate

### 4. Hidden Cash Unlocker
- Track liquidity blockers: open invoices, payment terms, inventory, inefficient flows
- Calculate total unlockable capital
- Action items per blocker

### 5. Behavioral System (Game Changer)
- Monthly/quarterly/annual cost review sessions with checklists
- Alerts: "Costs rising faster than revenue", "You're becoming cost-blind"
- Streak tracking for building discipline

### 6. Scenario Simulator
- What-if analysis: adjust revenue/costs and see projected profit change
- Before vs. After comparison with delta

### 7. Profit Allocation Layer
- Profit First bucket distribution: Profit, Taxes, Salary, Operations
- Percentage-based allocation with doughnut chart visualization

---

## Tech Stack

| Technology | Usage |
|---|---|
| **Laravel 10** | PHP framework |
| **Tailwind CSS 3** | Styling (dark theme) |
| **Vite** | Frontend bundler |
| **Chart.js 4** | Dashboard visualizations |
| **Alpine.js** | Lightweight interactivity |
| **SQLite / MySQL** | Database |

---

## Installation

### Requirements

- PHP 8.1+
- Composer
- Node.js 18+
- MySQL 5.7+ / MariaDB 10.3+ / SQLite

### Local Setup

```bash
# Clone the repository
git clone https://github.com/mrshahbazdev/cashcore.git
cd cashcore

# Install PHP dependencies
composer install

# Install frontend dependencies & build
npm install
npm run build

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env (see Database section below)

# Run migrations
php artisan migrate

# Start development server
php artisan serve
```

Visit `http://localhost:8000` to access CashCore.

### Database Configuration

**MySQL/MariaDB** (recommended for production):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cashcore
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**SQLite** (quick local setup):
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

---

## Shared Hosting Deployment

CashCore includes `.htaccess` files configured for shared hosting (cPanel, Plesk, etc.):

### Option A: Document Root Points to Project Root

1. Upload the entire project to your hosting (e.g., `/home/username/cashcore/`)
2. The root `.htaccess` will automatically redirect requests to `public/`
3. Set your domain's document root to the project folder

### Option B: Document Root Points to `public/` (Recommended)

1. Upload the project to a directory outside `public_html` (e.g., `/home/username/cashcore/`)
2. Point your domain's document root to `/home/username/cashcore/public/`
3. Or create a symlink: `ln -s /home/username/cashcore/public /home/username/public_html`

### Shared Hosting Steps

```bash
# 1. Upload files via FTP/SFTP or Git

# 2. SSH into your hosting (if available)
cd /path/to/cashcore

# 3. Install dependencies
composer install --no-dev --optimize-autoloader

# 4. Copy & configure .env
cp .env.example .env
php artisan key:generate
# Edit .env with your database credentials

# 5. Run migrations
php artisan migrate --force

# 6. Build frontend (or upload pre-built public/build/ directory)
npm install
npm run build

# 7. Set permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 8. Cache config for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### .htaccess Features Included

- **Root `.htaccess`**: Redirects all requests to `public/` directory
- **`public/.htaccess`**: Laravel front controller + security headers + gzip compression + browser caching

---

## Language Support

CashCore supports **English** and **German** out of the box.

- Toggle language via the EN/DE switcher in the header
- 235 translation keys in `lang/en/cashcore.php` and `lang/de/cashcore.php`
- Add more languages by copying one of these files to `lang/{locale}/cashcore.php`

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/                    # Login, Register
│   │   └── CashCore/               # 8 feature controllers
│   └── Middleware/
│       └── SetLocale.php            # EN/DE locale middleware
├── Models/                          # 9 Eloquent models
└── Services/
    └── CashCoreService.php          # Business logic layer

database/migrations/                 # 9 CashCore tables

lang/
├── en/cashcore.php                  # English translations (235 keys)
└── de/cashcore.php                  # German translations (235 keys)

resources/views/
├── layouts/app.blade.php            # Main layout
├── auth/                            # Login & Register
├── cashcore/
│   ├── layout.blade.php             # CashCore layout with nav tabs
│   ├── dashboard.blade.php          # Cash Transparency Engine
│   ├── transactions/                # CRUD + CSV import
│   ├── leaks/                       # Leak Detection
│   ├── scoring/                     # Expense Value Scoring
│   ├── unlocker/                    # Hidden Cash Unlocker
│   ├── behavior/                    # Behavioral System
│   ├── scenarios/                   # Scenario Simulator
│   └── allocation/                  # Profit Allocation Layer
└── welcome.blade.php                # Landing page
```

---

## Routes

All CashCore routes are under `/cashcore/*` with `auth` middleware:

| Route | Description |
|---|---|
| `GET /cashcore` | Dashboard (Cash Transparency) |
| `GET /cashcore/transactions` | Transaction list |
| `GET /cashcore/transactions/create` | Add transaction |
| `GET /cashcore/transactions/import` | CSV import |
| `GET /cashcore/leaks` | Leak Detection |
| `POST /cashcore/leaks/detect` | Run leak detection |
| `GET /cashcore/scoring` | Expense Scoring |
| `GET /cashcore/unlocker` | Cash Unlocker |
| `GET /cashcore/behavior` | Behavioral System |
| `GET /cashcore/scenarios` | Scenario Simulator |
| `GET /cashcore/allocation` | Profit Allocation |

---

## License

This project is proprietary software. All rights reserved.
