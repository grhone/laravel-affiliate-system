# Grhone's Laravel Affiliate System

## Overview

Grhone's Laravel Affiliate System is a robust, easy-to-use affiliate management package for Laravel applications. It provides functionalities for managing affiliate registrations, tracking referrals, calculating commissions, and handling affiliate payments. This package is ideal for businesses looking to integrate an affiliate marketing system into their Laravel-based web applications.

## Requirements

- PHP >= 7.3
- Laravel Framework 7.0 or higher
- MySQL 5.7 or higher (or equivalent database server)

## Installation

Follow these steps to install the Laravel Affiliate System in your Laravel project:

### Step 1: Install the Package

Via Composer, run the following command:

```bash
composer require grhone/laravel-affiliate-system
```

### Step 2: Publish Configuration and Migrations

Publish the package's configuration and migrations to your project:

```bash
php artisan vendor:publish --provider="Grhone\LaravelAffiliateSystem\Providers\AffiliateServiceProvider"
```

This command publishes the config file config/affiliate.php, views, and migration files to your project.

### Step 3: Run Migrations

After publishing, run the migrations:

```bash
php artisan migrate
```

This will create the necessary tables (affiliates, referrals, payments) in your database.

### Step 4: Configure

Edit the published configuration file in config/affiliate.php to suit your application's needs. You can set various parameters like commission rates, cookie duration, and more.

### Step 5: Add Service Provider (If required)

If your Laravel version doesn't support package auto-discovery, add the service provider to your config/app.php file:

```php
'providers' => [
    // ...
    Grhone\LaravelAffiliateSystem\Providers\AffiliateServiceProvider::class,
];
```

### Step 6: Integration

Integrate the package into your application:

Use the Referrable trait in your User model (or any model you wish to associate with affiliates).

Set up routes and controllers as per your application's requirements, utilizing the provided functionalities.

## Usage

### Managing Affiliates

- Create, update, and manage affiliates using the provided controllers and views.
- Track affiliate performance and manage payouts.

### Tracking Referrals

- Utilize the tracking functionalities to monitor referrals and conversions.
- Affiliate earnings are automatically calculated based on your configured commission rates.

## Customization

You can extend and customize the views, controllers, and models provided by the package to match your application's specific requirements.

## Contributing

Contributions, issues, and feature requests are welcome. Feel free to check issues page for open issues or open a new one.

## License

This package is licensed under the MIT License - see the LICENSE file for details.