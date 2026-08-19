# SkyBooker - Flight Booking System API

A flight booking system RESTful API built with Laravel 11.

## Features

- Flight management (CRUD)
- Airport, airline, and airplane management
- Booking system with passenger management
- Ticket generation
- Pagination support
- Nested routes
- Swagger/OpenAPI documentation

## Tech Stack

- Laravel 11
- PHP 8.2+
- MySQL 8.0+
- L5-Swagger
- Tailwind CSS for styling

## Installation

```bash
git clone https://github.com/yourusername/skybooker.git
cd skybooker
composer install
cp .env.example .env
php artisan key:generate