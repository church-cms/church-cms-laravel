# Church CMS - CLI Installation Guide

This guide covers the command-line installation method for Church CMS. This is ideal for developers who prefer terminal-based setup.

## Quick Start

```bash
# 1. Clone or extract Church CMS
git clone https://github.com/yourusername/church-cms.git
cd church-cms

# 2. Copy environment file
cp .env.example .env

# 3. Edit .env and configure database
nano .env
# Update: DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 4. Run the installer script (framework setup only)
bash install.sh

# 5. Create your church and admin user
php artisan church:setup

# 6. Start the development server
php artisan serve

# 7. Access at http://localhost:8000/admin
```

---

## Prerequisites

Before running the CLI installer, ensure you have:

### System Requirements
- **PHP 8.2+** installed and in PATH
- **MySQL 5.7+** server running
- **Composer** (optional - the script will inform you if needed)
- **npm/Node.js** (optional - for frontend assets)
- **Bash** shell (Linux/macOS/WSL on Windows)

### Check Your Setup

```bash
# Check PHP version
php -v

# Check PHP extensions
php -m | grep -E "pdo|mbstring|openssl"

# Check Composer
composer --version

# Check npm
npm --version

# Check MySQL
mysql --version
```

### Create Database

Before running the installer, create an empty MySQL database:

```bash
# Connect to MySQL
mysql -u root -p

# Create database
CREATE DATABASE churchcms;
GRANT ALL PRIVILEGES ON churchcms.* TO 'churchcms_user'@'localhost' IDENTIFIED BY 'password';
FLUSH PRIVILEGES;
EXIT;
```

Or use phpMyAdmin:
1. Log in to phpMyAdmin
2. Click "New" to create a new database
3. Name it `churchcms` (or your preferred name)
4. Click "Create"

---

## Configuration (.env File)

The installer requires your database credentials to be configured in the `.env` file before running.

### Step 1: Copy Template

```bash
cp .env.example .env
```

### Step 2: Edit .env File

Open `.env` with your preferred editor:

```bash
nano .env
# or
vim .env
# or
code .env  # VS Code
```

### Step 3: Configure Database Section

Find and update these lines:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1          # Your MySQL host (usually localhost or 127.0.0.1)
DB_PORT=3306               # MySQL port (usually 3306)
DB_DATABASE=churchcms      # Your database name
DB_USERNAME=root           # Your MySQL username
DB_PASSWORD=password       # Your MySQL password
```

### Step 4: Save and Exit

- **nano**: Press `Ctrl+O`, then `Enter`, then `Ctrl+X`
- **vim**: Press `:wq` and `Enter`
- **VS Code**: `Ctrl+S`

---

## Running the Installer

### Step 1: Framework Setup

```bash
bash install.sh
```

This script will:
1. ✓ Check system requirements
2. ✓ Verify .env configuration
3. ✓ Test database connection
4. ✓ Install Composer dependencies
5. ✓ Install npm packages
6. ✓ Run database migrations
7. ✓ Seed system data

**This step does NOT create church or admin user.**

### Step 2: Church & Admin Setup

```bash
php artisan church:setup
```

This interactive command will prompt you for:
- Church information (name, email, phone, timezone)
- Administrator credentials (email, password)

Then it will:
- ✓ Create the church record
- ✓ Create the admin user
- ✓ Create the user profile
- ✓ Update .env with PRIMARY_CHURCH_ID
- ✓ Create installation marker (completed)

### Interactive Prompts (Step 2)

#### Church Information
- **Church Name** (required) - Official name of your church
- **Church Email** (required) - Contact email address
- **Church Phone** (optional) - Contact phone number
- **Timezone** (default: UTC) - Your church's timezone

#### Administrator Account
- **Admin Email** (required) - Login email for admin user
- **Password** (required) - Minimum 8 characters
- **Confirm Password** - Re-enter password to verify

---

## Installation Output

### Successful Installation

```
═══════════════════════════════════════════════════════════
Installation Complete!
═══════════════════════════════════════════════════════════

✓ All steps completed successfully
✓ Church: "Your Church Name" created
✓ Admin: admin@example.com created
✓ Database preparation complete
```

### If Something Fails

The installer will show:
- ✗ Error message
- Log file location for debugging
- Steps to resolve the issue

---

## After Installation

### 1. Verify Installation

```bash
# Check if installation marker was created
ls -la storage/installed
```

### 2. Start Development Server

```bash
php artisan serve
```

Then access:
- **URL**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin
- **Login**: Use email and password from installation

### 3. First Time Setup

```bash
# View logs
tail -f storage/logs/laravel.log

# Clear cache (if needed)
php artisan cache:clear

# View installed database tables
php artisan tinker
>>> DB::table('church')->get();
>>> DB::table('users')->get();
>>> exit
```

### 4. Build Frontend Assets

If you skipped npm during installation:

```bash
npm install
npm run dev
```

For production:

```bash
npm run build
```

---

## Troubleshooting

### "PHP is not installed or not in PATH"

**Solution**: 
```bash
# Check PHP installation
which php
php -v

# Add PHP to PATH if needed
export PATH="/usr/local/bin:$PATH"
```

### "Extension not found: pdo_mysql"

**Solution**: Install missing extension
```bash
# macOS with Herd
herd php ...

# Ubuntu/Debian
sudo apt-get install php8.2-mysql

# Then restart PHP service
sudo systemctl restart php8.2-fpm
```

### "Failed to connect to database"

**Causes & Solutions**:

1. **Wrong credentials in .env**
   - Double-check username/password
   - Verify database exists

2. **MySQL not running**
   ```bash
   # Check if MySQL is running
   ps aux | grep mysql
   
   # Start MySQL (macOS)
   brew services start mysql
   
   # Start MySQL (Linux)
   sudo systemctl start mysql
   ```

3. **Wrong host/port**
   - Default host: `127.0.0.1`
   - Default port: `3306`
   - Ask your hosting provider if different

4. **User lacks permissions**
   ```bash
   # Grant permissions
   mysql -u root -p
   GRANT ALL PRIVILEGES ON churchcms.* TO 'churchcms_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

### "Composer dependencies installation failed"

**Solution**:
```bash
# Install dependencies manually
cd /path/to/church-cms
composer install --no-dev

# Then run installer again
bash install.sh
```

### "npm packages installation failed"

**Solution**:
```bash
# Install npm packages manually
npm install

# Build frontend
npm run dev

# Then run installer again
bash install.sh
```

### "Password too short" or "Passwords don't match"

**Solution**: During installation, the script will re-prompt you:
- Enter a password with at least 8 characters
- Make sure both password entries match exactly
- Watch for accidental spaces

---

## Comparison: CLI vs Web Installer

Church CMS offers two installation methods. Choose based on your preference:

| Feature | CLI Method | Web Installer |
|---------|-----------|---------------|
| **Access Method** | Terminal / SSH | Browser GUI |
| **Ideal For** | Developers, Automation, CI/CD | Non-technical users, Local setup |
| **Setup Steps** | 2 commands | Navigate web interface |
| **Customization** | Full control, scripting | Guided, user-friendly |
| **Audit Trail** | Laravel timestamps, logging | Visual feedback + logs |
| **Installation Time** | ~2-3 minutes | ~2-3 minutes |
| **Headless Servers** | ✓ Yes | ✗ No |
| **Development Workflow** | Quick iterations | One-time setup |
| **Error Messages** | Detailed CLI output | User-friendly prompts |

### When to Use CLI (This Guide)

Use CLI installation if you:
- Are a developer familiar with terminal commands
- Need to automate installation in CI/CD pipelines
- Are setting up on a server without browser access (SSH/VPS)
- Want detailed control and logging
- Prefer a faster command-line workflow

### When to Use Web Installer

Use the web installer (`/public/installer/`) if you:
- Prefer a visual, step-by-step interface
- Want to avoid terminal/SSH access
- Are non-technical and want guided prompts
- Prefer visual progress indicators
- Need screenshot-friendly documentation

---

## Advanced Usage

### Reinstall Without Losing Data

If you need to reinstall while keeping your church data:

```bash
# Option 1: Just reset church and admin (keep other data)
# Remove installation marker and re-run church:setup
rm storage/installed
php artisan church:setup

# Option 2: Full reinstall (keeps database tables)
bash install.sh
php artisan church:setup
```

**Note**: This will prompt to create a new church/admin. If a church already exists, the command will ask for confirmation before proceeding.

### Manual Installation (If Script Fails)

```bash
# 1. Install Composer dependencies
composer install

# 2. Install npm packages
npm install
npm run dev

# 3. Clear cache
php artisan config:clear
php artisan cache:clear

# 4. Run migrations
php artisan migrate --force

# 5. Seed database
php artisan db:seed --class=InstallerSeeder --force

# 6. Create church and admin
php artisan church:setup

# 7. Verify installation marker
ls -la storage/installed
```

### Uninstall / Fresh Start

To start over completely:

```bash
# 1. Remove installation marker (allows reinstall)
rm storage/installed

# 2. Drop database and recreate
mysql -u root -p -e "DROP DATABASE churchcms; CREATE DATABASE churchcms;"

# 3. Run migrations and seeding from scratch
php artisan migrate:fresh --seed --class=InstallerSeeder

# 4. Set up church and admin again
php artisan church:setup
```

**Complete wipe** (if removing project):
```bash
# Delete everything
cd ..
rm -rf church-cms
```

---

## Log Files

The installer creates detailed logs for debugging:

| Log File | Purpose |
|----------|---------|
| `/storage/logs/laravel.log` | Main application logs |
| `/storage/logs/installer.log` | Installation process logs |
| `/tmp/migrate.log` | Migration output |
| `/tmp/seed.log` | Seeding output |
| `/tmp/church_install.log` | Church creation output |

**View logs**:
```bash
# Real-time monitoring
tail -f storage/logs/laravel.log

# View last 50 lines
tail -50 storage/logs/laravel.log

# Search for errors
grep -i error storage/logs/laravel.log
```

---

## Environment Variables You Can Set

The installer only requires database configuration. Other optional settings:

```bash
# Application
APP_NAME=ChurchCMS
APP_ENV=local              # or production
APP_DEBUG=true             # Set to false in production
APP_URL=http://localhost

# Additional configuration done via UI
# after installation completes
```

---

## Multiple Installations

To install Church CMS on multiple servers:

1. **First Server**: Follow this guide normally
2. **Subsequent Servers**: 
   ```bash
   # Copy your configured .env
   scp user@server1:/path/to/.env .env
   
   # Or manually recreate .env with same database credentials
   # Then run installer
   bash install.sh
   ```

---

## Next Steps

After successful installation:

1. **Log in**: http://localhost:8000/admin
2. **Configure**: Church Settings → Update logo, contact info
3. **Add Staff**: Members → Add staff accounts
4. **Create Events**: Events → Create first event
5. **Remove Installer**: `rm -rf public/installer` (production)
6. **Enable HTTPS**: Configure SSL certificate
7. **Set up Backups**: Configure daily backups
8. **Monitor Logs**: Set up log monitoring

---

## Security Checklist

- [ ] Changed admin password on first login
- [ ] Removed `/public/installer` directory
- [ ] Configured HTTPS/SSL certificate
- [ ] Set up database backups
- [ ] Configured firewall rules
- [ ] Updated all dependencies (`composer update`)
- [ ] Disabled debug mode in production (`APP_DEBUG=false`)
- [ ] Set up log monitoring and alerts
- [ ] Configure secure email credentials
- [ ] Enable two-factor authentication (if available)

---

## Getting Help

If you encounter issues:

1. **Check logs**: `tail -f storage/logs/laravel.log`
2. **Review installation guide**: This file
3. **Contact support**: Provide error messages and logs
4. **Community forum**: Ask and share experiences

---

## Version Information

- **Script Version**: 1.0
- **Supported PHP**: 8.2+
- **Supported MySQL**: 5.7+
- **Date**: March 31, 2026

---

**Happy serving!** 🙏

If you prefer the visual web installer, access it at: `http://localhost:8000/installer`
