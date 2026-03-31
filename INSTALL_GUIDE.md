# Church CMS - Installation Guide

Welcome! This guide will walk you through installing Church CMS on your server.

## Table of Contents

1. [System Requirements](#system-requirements)
2. [Pre-Installation Checklist](#pre-installation-checklist)
3. [Installation Steps](#installation-steps)
4. [Troubleshooting](#troubleshooting)
5. [Post-Installation](#post-installation)
6. [Security Recommendations](#security-recommendations)

---

## System Requirements

Before you begin, ensure your server meets these requirements:

### Server Requirements
- **PHP Version**: 8.2 or higher
- **MySQL**: 5.7 or higher
- **Composer**: Latest version (auto-installed during setup if available)
- **npm/Node.js**: Latest LTS version (auto-installed during setup if available)

### Required PHP Extensions
- PDO (PHP Data Objects)
- PDO MySQL Extension
- Mbstring Extension
- OpenSSL Extension
- Fileinfo Extension
- Tokenizer Extension
- XML Extension
- Ctype Extension
- JSON Extension

### Server Permissions
- Write access to `/storage` directory
- Write access to `/bootstrap/cache` directory
- Write access to `/public` directory
- Ability to execute shell commands (for Composer and npm)

### Internet Connection
- Required for downloading dependencies (Composer packages, npm packages)

---

## Pre-Installation Checklist

- [ ] Server meets all system requirements
- [ ] PHP version is 8.2+
- [ ] MySQL database created and accessible
- [ ] Database user credentials ready
- [ ] Server folders have proper write permissions
- [ ] Domain/URL configured (or using localhost)
- [ ] Composer installed or available on server
- [ ] npm/Node.js installed or available on server

---

## Installation Steps

### Step 1: Welcome Screen

This is the introduction page to the Church CMS installation wizard.

**[Image placeholder - Step 1: Welcome Screen]**

**What to do:**
- Review the introduction
- Click "Start Installation" button to proceed

**Note:** This step is informational only. No data is collected here.

---

### Step 2: System Requirements Check

The installer checks if your server meets all necessary requirements.

**[Image placeholder - Step 2: Requirements Check]**

**What the installer checks:**
- ✓ PHP version (must be 8.2 or higher)
- ✓ Required PHP extensions (PDO, MySQL, Mbstring, OpenSSL, etc.)
- ✓ Folder permissions (storage, bootstrap/cache, public)
- ✓ Composer availability
- ✓ npm/Node.js availability

**If you see red X marks:**
- Contact your hosting provider to enable required extensions
- Ensure folder permissions are set correctly (755 for directories, 644 for files)

**If Composer or npm show as unavailable:**
- The installer will attempt to install them during the next step
- Don't worry if they show red - the installer handles this automatically

---

### Step 3: Database Configuration

Configure your database connection details.

**[Image placeholder - Step 3: Database Configuration]**

**Fields to fill:**
- **Database Host**: Usually `localhost` or `127.0.0.1` (contact hosting provider if unsure)
- **Database Port**: Usually `3306` (default MySQL port)
- **Database Name**: The name of your MySQL database
- **Database Username**: MySQL user account with database privileges
- **Database Password**: Password for the database user

**What the installer does:**
- Tests the connection to your database
- Reports if credentials are incorrect
- Automatically creates the `.env` file with your configuration

**Tips:**
- If you don't have a database yet, create one via your hosting control panel
- Make sure the database user has CREATE, ALTER, DROP, and INSERT privileges

---

### Step 4: Church Information

Configure your church's basic information.

**[Image placeholder - Step 4: Church Information]**

**Fields to fill:**
- **Church Name**: The official name of your church (required)
- **Church Email**: Main contact email address (required)
- **Church Phone**: Contact phone number (optional)
- **Timezone**: Select your church's timezone (required)

**What the installer does:**
- Validates that Church Name and Email are provided
- Stores this information in the database
- Uses this data to initialize your church profile

**Tips:**
- Use your official church name as it appears in legal documents
- Use a monitored email address that you check regularly
- Select the correct timezone for accurate timestamp records

---

### Step 5: Admin User Setup

Create your administrator account.

**[Image placeholder - Step 5: Admin User Setup]**

**Fields to fill:**
- **Admin Email**: Email address for the admin account (required)
- **Password**: Strong password (minimum 8 characters) (required)
- **Confirm Password**: Re-enter password to confirm (required)

**Password Requirements:**
- Minimum 8 characters long
- Must match the confirmation field
- We recommend using a strong, unique password

**What the installer does:**
- Validates email format
- Checks password length and confirmation match
- Prepares to create admin user during installation

**Security Tips:**
- Use a unique password (not used anywhere else)
- Include uppercase, lowercase, numbers, and symbols
- Store your credentials securely
- You can change this password after first login

---

### Step 6: Installation Progress

The installer runs all setup processes automatically.

**[Image placeholder - Step 6: Installation Progress - with spinners]**

**What's happening:**
1. **Installing Composer Dependencies** - Downloads PHP packages
2. **Installing npm Packages** - Downloads JavaScript libraries
3. **Running Database Migrations** - Creates database tables
4. **Seeding Database** - Populates system data (countries, permissions, bible data, etc.)
5. **Creating Church & Admin** - Sets up your church and admin user account

**Each step shows:**
- ⏳ Spinner while processing
- ✓ Green checkmark when complete

**Important:**
- Do NOT close this page or refresh your browser
- Installation can take 2-10 minutes depending on your server speed
- All processes run automatically

**If installation fails:**
- Check your error logs at `/storage/logs/laravel.log`
- Review the [Troubleshooting](#troubleshooting) section below
- Contact support if issues persist

---

### Step 7: Installation Complete

Your Church CMS is now ready to use!

**[Image placeholder - Step 7: Completion Screen]**

**What's displayed:**
- Installation success confirmation
- Checklist of completed setup tasks
- Your admin login credentials
- Next steps and security recommendations

**Important Action Required:**
- Review the **"Complete Setup & Go to Dashboard"** button information
- Click the button to finalize the installation
- This step creates the installation marker file
- Only click when you're ready to proceed to login

**What was set up:**
- ✓ Database tables created
- ✓ System data seeded (countries, permissions, bible data, etc.)
- ✓ Church information configured
- ✓ Admin user account created
- ✓ Environment configuration established

---

## After Installation Complete Button

Once you click "Complete Setup & Go to Dashboard":

1. The system writes the installation marker file
2. Your browser redirects to the login page
3. You can no longer access the installer (for security)

**Login with:**
- **Email**: The admin email you provided in Step 5
- **Password**: The password you created in Step 5

---

## Troubleshooting

### Database Connection Errors

**Error: "Cannot connect to database"**
- Verify database host, port, username, and password
- Confirm database exists and database user has privileges
- Check if your hosting provider requires different host (not localhost)
- Try contacting your hosting provider's support

**Error: "Access denied for user"**
- Double-check password is correct (case-sensitive)
- Verify username is correct
- Ensure database user has required privileges:
  - SELECT, INSERT, UPDATE, DELETE
  - CREATE, ALTER, DROP
  - REFERENCES (for foreign keys)

### Payment Extension Errors

**Error: "Required PHP extension not found"**
- Contact your hosting provider to enable the extension
- Ask them to enable: PDO, PDO_MySQL, Mbstring, OpenSSL, Fileinfo
- After they enable, refresh the requirements page

### Permission Errors

**Error: "Directory not writable"**
- Paths that need write permission:
  - `/storage`
  - `/bootstrap/cache`
  - `/public/uploads` (if exists)
- Set directory permissions to `755`:
  - Via FTP: Right-click folder → Permissions → 755
  - Via SSH: `chmod -R 755 /path/to/folder`

### Composer/npm Installation Fails

**If Composer or npm not installed:**
- The installer detects this and auto-installs dependencies
- If auto-installation fails:
  - Requires shell_exec() enabled on your server
  - Contact hosting provider to enable this feature
  - Or install manually:
    ```bash
    composer install
    npm install
    npm run dev
    ```

### Installation Hangs or Times Out

**Installation takes longer than expected:**
- Large downloads may take 5-10 minutes
- Be patient - don't refresh or close the page
- Check browser console (F12) for errors

**Installation timeout error:**
- Your server may have a timeout limit
- Increase PHP max execution time (contact hosting provider)
- Or run installation commands manually via SSH:
  ```bash
  php artisan migrate --force
  php artisan db:seed --class=InstallerSeeder --force
  ```

### Still Having Issues?

1. **Check the error logs:**
   - Application errors: `/storage/logs/laravel.log`
   - Installer logs: `/storage/logs/installer.log`

2. **Verify all requirements:**
   - Re-run Step 2 to check system requirements
   - Check PHP version: `php -v`
   - Check required extensions: `php -m`

3. **Contact support:**
   - Provide error messages from logs
   - Include your server information:
     - PHP version
     - MySQL version
     - Hosting provider name

---

## Post-Installation

### Step 1: Initial Login

1. Navigate to your Church CMS login page (usually `/login`)
2. Enter your admin email and password
3. Click "Sign In"

**[Image placeholder - Login Screen]**

### Step 2: Change Your Password

1. Go to Dashboard → Settings → Profile
2. Click "Change Password"
3. Enter your current password
4. Create a new strong password
5. Save changes

**Note:** While the generated password is secure, you may want to use a password you can remember.

### Step 3: Configure Church Settings

1. Go to Dashboard → Settings → Church Settings
2. Configure:
   - Church logo
   - Church contact details
   - Service times
   - Social media links
   - Other church-specific settings

**[Image placeholder - Church Settings]**

### Step 4: Add Staff Members

1. Go to Dashboard → Members → Staff
2. Click "Add New Staff Member"
3. Fill in staff details
4. Assign appropriate permissions
5. Send login credentials or reset password link

### Step 5: Set Up Church Events

1. Go to Dashboard → Events
2. Create your first event
3. Add event details (date, time, location, description)
4. Publish the event

### Step 6: Explore the Dashboard

Take time to familiarize yourself with:
- Dashboard overview
- Menu structure
- Available modules and features
- Settings and configuration options

---

## Security Recommendations

### Critical - Do These Immediately

1. **Remove the installer directory**
   ```bash
   rm -rf public/installer
   ```
   - The installer is a potential security risk if left accessible
   - Once installation is complete, it's no longer needed

2. **Change admin password** (if not already done)
   - Use a strong, unique password
   - Profile → Change Password

3. **Enable HTTPS**
   - Contact your hosting provider to set up SSL certificate
   - Update APP_URL in `.env` to use `https://`
   - Redirect all HTTP traffic to HTTPS

4. **Set up database backups**
   - Daily automated backups recommended
   - Test restore procedures regularly

### Recommended Security Practices

1. **Regular Updates**
   - Keep Church CMS updated to latest version
   - Update dependencies: `composer update`, `npm update`
   - Monitor for security patches

2. **Access Control**
   - Create separate user accounts for staff members
   - Assign appropriate permissions to each user
   - Regularly review user access levels
   - Remove access for users who no longer need it

3. **Data Protection**
   - Enable two-factor authentication (if available)
   - Use strong passwords for all accounts
   - Don't share login credentials
   - Use password manager to store passwords securely

4. **Server Security**
   - Keep server software updated
   - Configure server firewall
   - Use strong hosting provider credentials
   - Monitor server access logs
   - Set up uptime monitoring alerts

5. **Backup Strategy**
   - Automated daily database backups
   - Regular filesystem backups
   - Test restore procedures monthly
   - Keep backup copies off-site

6. **Monitoring**
   - Monitor error logs regularly
   - Set up email alerts for errors
   - Track user login activity
   - Monitor for suspicious activities

---

## Getting Help

### Resources

- **Documentation**: Visit our documentation site
- **Community Forum**: Ask questions and share experiences
- **Email Support**: support@churchcms.com
- **Documentation Links**:
  - [main documentation]
  - [API Reference]
  - [Plugin Development]

### Useful Files for Support

When contacting support, include:
- `/storage/logs/laravel.log` (last 100 lines)
- `/storage/logs/installer.log` (if installation failed)
- PHP version: `php -v`
- MySQL version: `mysql --version`
- Your server OS and hosting provider

---

## Next Steps

Congratulations! Your Church CMS is installed and ready to use.

### Immediate Tasks
1. ✓ Log in as admin
2. ✓ Configure church information
3. ✓ Add staff members
4. ✓ Set up events
5. ✓ Remove installer directory

### Short-term Tasks (This Week)
- Configure all church settings
- Set up sermon/media library
- Add church members
- Create event calendar
- Customize appearance/branding

### Long-term Tasks (This Month)
- Train staff on CMS features
- Set up external integrations
- Configure backup procedures
- Establish security procedures
- Document your setup process

---

## Version Information

- **Church CMS Version**: 1.0
- **Installation Guide Version**: 1.0
- **Last Updated**: March 31, 2026
- **PHP Version Required**: 8.2+
- **MySQL Version Required**: 5.7+

---

## License and Agreement

By installing Church CMS, you agree to the terms outlined in the LICENSE file included with your installation.

Thank you for choosing Church CMS! We hope it serves your church community well.

**Happy serving!** 🙏

---

*This installation guide is provided as-is. For updates and additional resources, please visit our documentation site.*
