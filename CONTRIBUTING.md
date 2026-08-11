# Contributing to ChurchCMS

ChurchCMS is developed and maintained by GegoSoft Technologies (OPC) Private Limited, India.
Project website: https://gegosoft.com

## Before you start

- Check for an existing issue or discussion before opening a new one.
- Keep proposals specific: describe the problem, expected behavior, and impact on churches or administrators.
- Do not include secrets, personal data, or production exports in issues or pull requests.

## Development setup

1. Fork the repository.
2. Clone your fork locally.
3. Install dependencies:

   ```bash
   composer install
   npm install
   ```

4. Create a local environment file:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure your database and any required services.
6. Run the application setup:

   ```bash
   php artisan migrate
   php artisan db:seed
   php artisan passport:install
   npm run dev
   ```

## Pull request guidelines

- Keep each pull request focused on one concern.
- Include a short summary of the problem and the chosen approach.
- Document any database changes, background jobs, or external service dependencies.
- Add or update tests when the change affects behavior.
- Update documentation when setup, configuration, or user-visible behavior changes.

## Coding expectations

- Follow the existing Laravel and Vue conventions already used in the codebase.
- Prefer small, targeted changes over large refactors.
- Avoid introducing breaking changes without discussing them first.
- Keep configuration examples sanitized and use placeholders for credentials.

## Review checklist

Before opening a pull request, verify that you have:

- Run the relevant test suite or manual verification steps
- Checked for obvious regressions in the affected workflows
- Removed secrets and environment-specific values
- Updated README or other docs if the setup changed

## Reporting security issues

Do not open a public issue for suspected security vulnerabilities. Coordinate privately with the project maintainers through https://gegosoft.com.
