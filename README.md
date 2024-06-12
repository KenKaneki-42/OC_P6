# SNOWTRICKS

## Table of Contents

1. [Description](#description)
2. [Main Features](#main-features)
3. [Technologies Used](#technologies-used)
4. [Libraries](#libraries)
5. [Installation](#installation)
6. [Configuration](#configuration)
7. [Contributing](#contributing)
8. [FAQ](#faq)

## Description

SnowTricks is a community-driven platform where enthusiasts can share and explore snowboarding tricks. The website features multimedia content, user interactions, and a robust system for managing contributions.

## Main Features

- **CRUD Operations on Tricks**: Users can create and read tricks. A User can update and delete his own trick.
- **Interactive Comments**: Each user can publish comments for a trick.
- **Security Features**: Protection against CSRF attacks and secure user authentication.

## Technologies Used

- PHP 8.3.6
- Symfony 7.0
- MySQL (Relational Database)
- HTML, CSS, JavaScript

## Libraries

- `symfony/mailjet-mailer`: For handling outgoing emails.
- `twig/twig`: Templating engine.
- `doctrine/orm`: Database ORM.
- `symfony/security-bundle`: Security and user management.


## Installation

```bash
git clone https://github.com/KenKaneki-42/OC_P6.git
cd OC_P6
composer install
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
symfony server:start
```

Open http://localhost:8000 in your browser to view the application.

## Configuration

### Database Configuration:
- **Copy `.env` to `.env.local`.**
  This ensures that your local settings do not interfere with the production settings.
- **Set your database URL in `.env.local` under `DATABASE_URL`.**
  Example for a MySQL database:
  ```plaintext
  DATABASE_URL="mysql://username:password@localhost:3306/database_name"

### Mailer Configuration:
- Adjust mailer settings in .env.local for the MAILER_DSN variable according to your provider.
Example for SMTP setup:
MAILER_DSN=smtp://PUBLIC_KEY:PRIVATE_KEY@in-v3.mailjet.com

## Contributing

If you want to contribute to SnowTricks, please follow these steps:

1. Fork the repository
2. Create a new branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -am 'Add a new feature'`)
4. Push the branch (`git push origin feature/new-feature`)
5. Create a pull request

## FAQ

### Q: How can I create a new trick?

A: To create a new trick, log in as a user, then navigate to the trick creation page.

### Q: Are comments moderated?

A: For the moment no, but if there is abuse, you can contact the team and we can ban a user.
