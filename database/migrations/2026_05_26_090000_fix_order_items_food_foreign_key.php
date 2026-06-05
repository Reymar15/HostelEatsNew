<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            return;
        }

        if (! Schema::hasTable('order_items')) {
            return;
        }

        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement('CREATE TABLE order_items_fixed (
            id integer primary key autoincrement not null,
            order_id integer not null,
            food_id integer not null,
            quantity integer not null,
            price numeric not null,
            created_at datetime,
            updated_at datetime,
            foreign key(order_id) references orders(id) on delete cascade,
            foreign key(food_id) references foods(id) on delete cascade
        )');

        DB::statement('INSERT INTO order_items_fixed (id, order_id, food_id, quantity, price, created_at, updated_at)
            SELECT id, order_id, food_id, quantity, price, created_at, updated_at FROM order_items');

        Schema::drop('order_items');
        DB::statement('ALTER TABLE order_items_fixed RENAME TO order_items');

        DB::statement('PRAGMA foreign_keys = ON');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            return;
        }

        if (! Schema::hasTable('order_items')) {
            return;
        }

        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement('CREATE TABLE order_items_old (
            id integer primary key autoincrement not null,
            order_id integer not null,
            food_id integer not null,
            quantity integer not null,
            price numeric not null,
            created_at datetime,
            updated_at datetime,
            foreign key(order_id) references orders(id) on delete cascade,
            foreign key(food_id) references food(id) on delete cascade
        )');

        DB::statement('INSERT INTO order_items_old (id, order_id, food_id, quantity, price, created_at, updated_at)
            SELECT id, order_id, food_id, quantity, price, created_at, updated_at FROM order_items');

        Schema::drop('order_items');
        DB::statement('ALTER TABLE order_items_old RENAME TO order_items');

        DB::statement('PRAGMA foreign_keys = ON');
    }
};
