# Company Website CMS (Laravel + Filament)

A modern, lightweight CMS for company websites, built with the Laravel ecosystem. Bundles a Filament admin panel and a public-facing Blade + Tailwind website.

## Tech Stack

- **Backend / Frontend**: Laravel 12, Blade, Livewire
- **Admin Panel**: [Filament v3](https://filamentphp.com/)
- **Auth & Authorization**: Laravel Auth + [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)
- **Database**: MySQL (default) or PostgreSQL — tests use SQLite in-memory
- **Asset Bundler**: Vite + Tailwind CSS v4 + Alpine.js
- **File Storage**: Laravel Storage on the `public` disk (S3-compatible can be added later)
- **Other**: Spatie Activity Log, Spatie Media Library, Spatie Sitemap, Eloquent Sluggable

## Features

| Module | Notes |
|--------|-------|
| Auth | Filament login, roles (`super_admin`, `admin`, `editor`), `canAccessPanel` gate |
| Dashboard | Stats widget for pages / articles / unread messages / services |
| Pages | CRUD, slug auto, status `draft` / `published` / `archived`, SEO fields, featured image |
| Articles | CRUD, workflow `draft` / `review` / `published` / `archived`, category + tags + featured image |
| Categories & Tags | CRUD with inline-create from Article form |
| Media Library | `spatie/laravel-medialibrary` installed; file uploads via Filament FileUpload to `public` disk |
| Company Profile | Singleton page (logo, about, vision, mission, contact, social media) |
| Site Settings | Singleton page (site name, logo, favicon, primary color, default SEO, analytics ID) |
| Services | CRUD with image, icon, sort order, active flag |
| Banners | Hero banners with image + CTA |
| Contact Messages | Public form → admin inbox; `unread` / `read` / `replied` / `archived` |
| Menus | Header / footer menus, parent-child support |
| Public Site | Home, Blog list + detail, Services list + detail, Static Pages, Contact form |
| Audit Log | `spatie/laravel-activitylog` on key models |
| SEO | Sitemap (`/sitemap.xml`), meta title / description / OG image |

## Requirements

- PHP 8.2+
- Composer 2.x
- Node.js 18+ and npm
- MySQL 8+ (or PostgreSQL 13+)

## Getting Started

```bash
# 1. Clone and install
git clone <your-repo-url>
cd <your-repo>
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate

# Edit .env to point to your MySQL database
# DB_CONNECTION=mysql
# DB_DATABASE=company_cms
# DB_USERNAME=root
# DB_PASSWORD=

# 3. Create storage symlink (uploads go to storage/app/public)
php artisan storage:link

# 4. Run migrations and seeders
php artisan migrate --seed

# 5. Build front-end assets
npm run build      # production
# or
npm run dev        # dev with HMR

# 6. Serve
php artisan serve
```

Admin panel: <http://localhost:8000/admin>

Seeded accounts (all use password `password`):

| Email | Role |
|-------|------|
| `superadmin@example.com` | `super_admin` |
| `admin@example.com` | `admin` |
| `editor@example.com` | `editor` |

Public site: <http://localhost:8000>

## Project Layout

```
app/
  Filament/
    Pages/                  # ManageCompanyProfile, ManageSiteSettings
    Resources/              # PageResource, ArticleResource, ...
    Widgets/                # CmsStatsWidget
  Http/
    Controllers/Public/     # HomeController, ArticleController, ...
    Requests/Public/        # StoreContactMessageRequest
  Models/                   # Page, Article, Service, Banner, ...
  Providers/
    AppServiceProvider.php
    Filament/AdminPanelProvider.php
  View/Composers/           # PublicLayoutComposer

resources/
  views/
    public/
      layouts/app.blade.php
      partials/             # header, footer
      pages/, articles/, services/, home, contact
    filament/pages/         # custom singleton pages

routes/
  web.php                   # all public routes + admin routes registered by Filament

database/
  migrations/               # categories, tags, pages, articles, services, banners, ...
  seeders/                  # RolePermissionSeeder, UserSeeder, DemoContentSeeder
```

## Testing

```bash
php artisan test
```

Tests run against an in-memory SQLite database. The included `PublicPagesTest` covers the main public routes and the contact form.

## Linting / Code Style

```bash
./vendor/bin/pint            # fix style issues
./vendor/bin/pint --test     # check only
```

## CI

A GitHub Actions workflow runs at `.github/workflows/ci.yml`. It installs PHP + Node, runs Pint and PHPUnit on every push and pull request.

## Roles & Permissions

Permissions follow the `{action}_{resource}` convention from Filament Shield, e.g. `view_any_page`, `create_article`, `delete_user`. See `database/seeders/RolePermissionSeeder.php`.

- `super_admin` — all permissions (also bypasses Gate checks via `Gate::before`)
- `admin` — everything except user CRUD
- `editor` — pages, articles, categories, tags, media

## Storage

Uploads from Filament use the `public` disk and live under `storage/app/public/`. After running `php artisan storage:link` they are served from `public/storage/...`.

To move to S3 / Cloudflare R2 / DigitalOcean Spaces later:

1. Configure an S3-compatible disk in `config/filesystems.php`.
2. Update `FILESYSTEM_DISK=` in `.env`.
3. Change `disk('public')` calls in the Filament resources to your new disk name (or replace with `config('filesystems.default')`).

## License

MIT
