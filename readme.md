# ChurchCMS

ChurchCMS is an open source church management platform built with Laravel 8+, Vue 2, and Laravel Mix. It is designed for ministries that need a single system for member data, communication, sermons, events, prayer workflows, media, and community engagement.

Developed and maintained by GegoSoft Technologies (OPC) Private Limited, India.
Website: https://gegosoft.com

## Project links

- Marketing website: https://churchcms.app
- Community: https://github.com/church-cms
- Laravel repository: https://github.com/church-cms/church-cms-laravel
- Android mobile app repository: https://github.com/church-cms/church-cms-android
- Documentation repository: https://github.com/church-cms/church-cms-docs

## Why this project exists

Church teams often end up stitching together spreadsheets, messaging tools, livestream links, donation systems, and separate member directories. ChurchCMS brings those workflows together into one application so administrators, preachers, volunteers, and members can work from the same source of truth.

## Feature overview

- Member, guest, preacher, and sub-admin management
- Sermons, bulletins, audio, video, and gallery publishing
- Event calendars, attendance, reminders, and birthday notifications
- Prayer requests, community help workflows, and messaging tools
- Donation and fund management capabilities
- Newsletter, campaign, subscriber, and mailing-list features
- Mobile-app, push-notification, livestream, and conference integrations
- Role-based administration with audit-oriented activity logging

## Technology stack

- PHP 8.2+
- Laravel 8 or higher
- Vue 2
- MySQL 5.7+ or MariaDB
- Node.js and npm for frontend assets
- Optional integrations for S3-compatible storage, Firebase Cloud Messaging, Twilio, Pusher, and analytics

## Getting started

Church CMS offers **two installation methods** to suit your preference:

### 📱 Option 1: Web Installer (Recommended for first-time users)

A visual, browser-based installation wizard that guides you through setup step-by-step.

**Best for:** Non-technical users, local desktop setup, visual feedback

1. Clone the repository:
   ```bash
   git clone <your-fork-or-repo-url>
   cd church-cms-laravel
   ```

2. Copy and configure `.env`:
   ```bash
   cp .env.example .env
   # Edit .env with your database credentials
   nano .env
   ```

3. Open the installer in your browser:
   ```
   http://localhost:your-port/installer
   ```

4. Follow the 6-step visual wizard to complete setup

📖 **Full guide:** [INSTALL_GUIDE.md](INSTALL_GUIDE.md)

---

### 💻 Option 2: CLI Installer (Recommended for developers)

A command-line installation method perfect for automation, CI/CD, and SSH servers.

**Best for:** Developers, automation, servers without browser access, scripting

```bash
# 1. Clone the repository
git clone <your-fork-or-repo-url>
cd church-cms-laravel

# 2. Copy and configure .env
cp .env.example .env
nano .env  # Update database credentials

# 3. Run framework setup
bash install.sh

# 4. Create your church and admin user
php artisan church:setup

# 5. Start the development server
php artisan serve

# 6. Access the application
# http://localhost:8000/admin
```

📖 **Full guide:** [CLI_INSTALL_GUIDE.md](CLI_INSTALL_GUIDE.md)

---

### 🔧 Manual Setup (If you prefer complete control)

For experienced Laravel developers who want to set up manually:

```bash
# 1. Install dependencies
composer install
npm install

# 2. Configure environment
cp .env.example .env
php artisan key:generate

# 3. Setup database
php artisan migrate
php artisan db:seed
php artisan passport:install

# 4. Build frontend assets
npm run dev

# 5. Create church and admin user
php artisan church:setup

# 6. Run the application
php artisan serve
```

Visit `http://127.0.0.1:8000` in your browser.

## Development workflow

Useful commands during local development:

```bash
php artisan serve
npm run watch
php artisan queue:work
```

If your instance uses local file uploads, create the storage symlink when needed:

```bash
php artisan storage:link
```

## Configuration notes

- Default local development uses file-based cache and sessions.
- The example environment defaults to local filesystem storage so the app can boot without S3.
- Push notifications, livestreaming, SMS, and analytics are optional and require extra service configuration.
- Review every third-party integration before enabling it in production.

## Testing

The repository includes PHPUnit and Laravel Dusk configuration, but application-level tests are not yet comprehensive. When contributing new behavior, add or update automated tests where practical.

Typical test commands:

```bash
./vendor/bin/phpunit
php artisan dusk
```

## Open source expectations

- Use issues for bug reports, regressions, and feature proposals.
- Keep pull requests focused and small enough to review.
- Avoid committing secrets, production data, or generated assets that do not belong in source control.
- Update documentation when behavior or setup changes.

## Contributing

Contribution guidelines are documented in [CONTRIBUTING.md](CONTRIBUTING.md).

## Maintainer

ChurchCMS is developed and maintained by GegoSoft Technologies (OPC) Private Limited, India.
Project website: https://gegosoft.com

## License

This project is licensed under the MIT License. See [LICENSE](LICENSE).
