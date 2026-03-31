#!/bin/bash

#############################################################################
# Church CMS - Installation Script
#
# This script handles framework setup:
# - Composer dependencies
# - npm packages
# - Database migrations
# - System seeding
#
# After this script, run: php artisan church:setup
# To create your church and admin user account
#
# Usage: bash install.sh
#############################################################################

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
STORAGE_DIR="$PROJECT_ROOT/storage"
ENV_FILE="$PROJECT_ROOT/.env"

#############################################################################
# Helper Functions
#############################################################################

# Print colored output
print_header() {
    echo -e "\n${BLUE}═══════════════════════════════════════════════════════════${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}═══════════════════════════════════════════════════════════${NC}\n"
}

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ $1${NC}"
}

# Check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Prompt user for input
read_input() {
    local prompt="$1"
    local default="$2"
    local input

    if [ -z "$default" ]; then
        read -p "$prompt: " input
    else
        read -p "$prompt [$default]: " input
        input="${input:-$default}"
    fi

    echo "$input"
}

# Validate email
validate_email() {
    local email="$1"
    if [[ "$email" =~ ^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$ ]]; then
        return 0
    else
        return 1
    fi
}

# Validate password strength
validate_password() {
    local password="$1"
    if [ ${#password} -lt 8 ]; then
        return 1
    fi
    return 0
}

#############################################################################
# Step 1: Check System Requirements
#############################################################################

check_requirements() {
    print_header "Step 1: Checking System Requirements"

    local all_ok=true

    # Check PHP version
    if command_exists php; then
        php_version=$(php -r 'echo phpversion();')
        print_success "PHP version: $php_version"

        # Check if version is >= 8.2
        if php -r 'exit(version_compare(PHP_VERSION, "8.2.0", ">=") ? 0 : 1);'; then
            print_success "PHP version is 8.2 or higher"
        else
            print_error "PHP version must be 8.2 or higher (current: $php_version)"
            all_ok=false
        fi
    else
        print_error "PHP is not installed or not in PATH"
        all_ok=false
    fi

    # Check required extensions
    local extensions=("pdo" "pdo_mysql" "mbstring" "openssl" "fileinfo" "tokenizer" "xml" "ctype" "json")
    for ext in "${extensions[@]}"; do
        if php -r "exit(extension_loaded('$ext') ? 0 : 1);" 2>/dev/null; then
            print_success "Extension: $ext"
        else
            print_error "Extension not found: $ext"
            all_ok=false
        fi
    done

    # Check Composer
    if command_exists composer; then
        print_success "Composer is installed"
    else
        print_warning "Composer not found - will attempt to install dependencies manually"
    fi

    # Check npm
    if command_exists npm; then
        npm_version=$(npm --version)
        print_success "npm is installed (version $npm_version)"
    else
        print_warning "npm not found - npm packages will not be installed"
    fi

    if [ "$all_ok" = false ]; then
        print_error "Some system requirements are not met. Please install missing components and try again."
        exit 1
    fi

    print_success "All system requirements met!"
}

#############################################################################
# Step 2: Verify .env File
#############################################################################

verify_env_file() {
    print_header "Step 2: Verifying Environment Configuration"

    if [ ! -f "$ENV_FILE" ]; then
        print_error ".env file not found at $ENV_FILE"
        print_info "Please copy .env.example to .env and configure your database:"
        print_info "  cp .env.example .env"
        print_info "  nano .env  # or your preferred editor"
        print_info ""
        print_info "Configure these values:"
        print_info "  DB_HOST=127.0.0.1"
        print_info "  DB_PORT=3306"
        print_info "  DB_DATABASE=your_database_name"
        print_info "  DB_USERNAME=your_username"
        print_info "  DB_PASSWORD=your_password"
        exit 1
    fi

    print_success ".env file found"

    # Check if database credentials are set
    if grep -q "^DB_HOST=" "$ENV_FILE" && grep -q "^DB_DATABASE=" "$ENV_FILE"; then
        print_success "Database credentials configured in .env"
    else
        print_error "Database credentials not configured in .env"
        print_info "Please edit .env and configure your database settings"
        exit 1
    fi
}

#############################################################################
# Step 3: Collect Church Information
#############################################################################

# This step is now done via artisan command

#############################################################################
# Step 5: Test Database Connection
#############################################################################

test_database_connection() {
    print_header "Step 5: Testing Database Connection"

    # Extract DB credentials from .env
    local db_host=$(grep "^DB_HOST=" "$ENV_FILE" | cut -d '=' -f 2 | tr -d '\r')
    local db_port=$(grep "^DB_PORT=" "$ENV_FILE" | cut -d '=' -f 2 | tr -d '\r')
    local db_name=$(grep "^DB_DATABASE=" "$ENV_FILE" | cut -d '=' -f 2 | tr -d '\r')
    local db_user=$(grep "^DB_USERNAME=" "$ENV_FILE" | cut -d '=' -f 2 | tr -d '\r')
    local db_pass=$(grep "^DB_PASSWORD=" "$ENV_FILE" | cut -d '=' -f 2 | tr -d '\r')

    print_info "Connecting to database..."
    print_info "Host: $db_host:$db_port"
    print_info "Database: $db_name"
    print_info "User: $db_user"

    # Try to connect using PHP
    if php -r "
        \$dsn = 'mysql:host=$db_host;port=$db_port';
        \$pdo = new PDO(\$dsn, '$db_user', '$db_pass');
        echo 'connected';
    " 2>/dev/null | grep -q "connected"; then
        print_success "Database connection successful"
    else
        print_error "Failed to connect to database"
        print_error "Please verify your database credentials in .env file"
        exit 1
    fi
}

#############################################################################
# Step 6: Run Migrations
#############################################################################

run_migrations() {
    print_header "Step 6: Running Database Migrations"

    cd "$PROJECT_ROOT"

    print_info "Creating database tables..."
    if php artisan migrate --force 2>&1 | tee /tmp/migrate.log; then
        print_success "Migrations completed successfully"
    else
        print_error "Migration failed. Check /tmp/migrate.log for details"
        exit 1
    fi
}

#############################################################################
# Step 7: Run Seeders
#############################################################################

run_seeders() {
    print_header "Step 7: Seeding System Data"

    cd "$PROJECT_ROOT"

    print_info "Populating system data (countries, permissions, bible data, etc.)..."
    if php artisan db:seed --class=InstallerSeeder --force 2>&1 | tee /tmp/seed.log; then
        print_success "Database seeding completed successfully"
    else
        print_error "Seeding failed. Check /tmp/seed.log for details"
        exit 1
    fi
}

#############################################################################
# Step 8: Install Dependencies (Optional)
#############################################################################

install_dependencies() {
    print_header "Step 8: Installing Dependencies"

    cd "$PROJECT_ROOT"

    # Check if vendor directory exists
    if [ ! -d "vendor" ]; then
        print_info "vendor directory not found. Installing Composer dependencies..."

        if command_exists composer; then
            if composer install --no-dev 2>&1 | tail -5; then
                print_success "Composer dependencies installed"
            else
                print_error "Composer installation failed"
                exit 1
            fi
        else
            print_warning "Composer not found. Skipping dependency installation."
            print_info "Please run: composer install"
        fi
    else
        print_success "Composer dependencies already installed"
    fi

    # Check if node_modules directory exists
    if [ ! -d "node_modules" ]; then
        print_info "node_modules directory not found. Installing npm packages..."

        if command_exists npm; then
            if npm install 2>&1 | tail -5; then
                print_success "npm packages installed"

                # Run build
                print_info "Building frontend assets..."
                if npm run dev 2>&1 | tail -5; then
                    print_success "Frontend assets built successfully"
                fi
            else
                print_warning "npm installation had issues but continuing..."
            fi
        else
            print_warning "npm not found. Skipping npm installation."
            print_info "Please run: npm install && npm run dev"
        fi
    else
        print_success "npm packages already installed"
    fi
}

#############################################################################
# Step 9: Display Completion Message
#############################################################################

show_completion() {
    print_header "Installation Complete!"

    echo ""
    echo -e "${GREEN}╔════════════════════════════════════════════════════════════╗${NC}"
    echo -e "${GREEN}║                                                            ║${NC}"
    echo -e "${GREEN}║  Church CMS framework setup is complete! 🎉               ║${NC}"
    echo -e "${GREEN}║                                                            ║${NC}"
    echo -e "${GREEN}╚════════════════════════════════════════════════════════════╝${NC}"
    echo ""

    echo -e "${BLUE}Next Steps:${NC}"
    echo "  1. Create your church and admin user:"
    echo "     php artisan church:setup"
    echo ""
    echo "  2. Start the development server:"
    echo "     php artisan serve"
    echo ""
    echo "  3. Access your Church CMS:"
    echo "     http://localhost:8000/admin"
    echo ""

    echo -e "${YELLOW}What was installed:${NC}"
    echo "  ✓ Composer dependencies"
    echo "  ✓ npm packages and assets"
    echo "  ✓ Database migrations"
    echo "  ✓ System data (countries, permissions, bible data, etc.)"
    echo ""

    echo -e "${BLUE}Useful Commands:${NC}"
    echo "  • Create church/admin:       php artisan church:setup"
    echo "  • Start dev server:          php artisan serve"
    echo "  • View logs:                 tail -f storage/logs/laravel.log"
    echo "  • Clear cache:               php artisan cache:clear"
    echo "  • Rebuild assets:            npm run dev"
    echo ""

    echo -e "${YELLOW}Security Reminders (for production):${NC}"
    echo "  • Remove installer directory: rm -rf public/installer"
    echo "  • Enable HTTPS"
    echo "  • Set up regular backups"
    echo ""

    echo -e "${GREEN}Thank you for choosing Church CMS! Happy serving! 🙏${NC}"
    echo ""
}

#############################################################################
# Main Installation Flow
#############################################################################

main() {
    clear

    # Welcome message
    echo -e "${BLUE}"
    echo "╔════════════════════════════════════════════════════════════╗"
    echo "║                                                            ║"
    echo "║             Church CMS - Installation Script              ║"
    echo "║                                                            ║"
    echo "║    This script sets up the Laravel framework, database,   ║"
    echo "║    and system data. Church and admin will be created      ║"
    echo "║    with: php artisan church:setup                         ║"
    echo "║                                                            ║"
    echo "╚════════════════════════════════════════════════════════════╝"
    echo -e "${NC}"
    echo ""

    echo ""
    read -p "Press Enter to begin installation..."
    echo ""

    # Run installation steps
    check_requirements
    verify_env_file
    test_database_connection
    install_dependencies
    run_migrations
    run_seeders
    show_completion
}

# Run main function
main "$@"
