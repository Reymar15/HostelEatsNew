# HostelEats Setup

## Tech Stack

- Laravel 12, compatible with Laravel 10+
- Blade UI with Tailwind CSS
- Supabase PostgreSQL
- Session-based cart
- Breeze authentication

## Supabase PostgreSQL `.env`

Copy `.env.example` to `.env`, then set your Supabase database values:

```env
APP_NAME=HostelEats
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=pgsql
DB_HOST=db.your-project-ref.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-database-password
DB_SSLMODE=require
```

Keep these database-backed drivers because the migrations include the needed tables:

```env
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

## Install And Run

```bash
composer install
npm install
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

Open:

```text
http://127.0.0.1:8000
```

## Demo Accounts

```text
Admin:   admin@hosteleats.test
Student: student@hosteleats.test
Password for both: password
```

## Main File Structure

```text
app/
  Http/
    Controllers/
      Admin/
        BranchController.php
        FoodController.php
        OrderController.php
      BranchController.php
      CartController.php
      HomeController.php
      OrderController.php
    Middleware/
      AdminMiddleware.php
  Models/
    Branch.php
    Food.php
    Order.php
    OrderItem.php
    User.php

database/
  migrations/
    *_create_users_table.php
    *_create_branches_table.php
    *_create_food_table.php
    *_create_orders_table.php
    *_create_order_items_table.php
    *_add_is_admin_to_users_table.php
  seeders/
    DatabaseSeeder.php

resources/views/
  branches/
    index.blade.php
    show.blade.php
  cart/
    index.blade.php
  orders/
    checkout.blade.php
    index.blade.php
  admin/
    branches/
    foods/
    orders/
  layouts/
    app.blade.php
    navigation.blade.php

routes/
  web.php
```

## Features

- Users can register, log in, browse branches, view food menus, add foods to a session cart, checkout, and view order history.
- Admin users can create, update, and delete branches and foods.
- Admin users can view all orders and update order status.
- Orders and order items are stored in the database with Eloquent relationships.
