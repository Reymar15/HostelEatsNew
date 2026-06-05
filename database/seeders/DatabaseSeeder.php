<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Food;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@hosteleats.test'],
            [
                'name'     => 'HostelEats Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'role'     => 'super_admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'student@hosteleats.test'],
            [
                'name'     => 'Juan Dela Cruz',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'role'     => 'customer',
            ]
        );

        $branches = [
            'Jollibee' => [
                'description' => 'Filipino fast-food favorites',
                'logo' => 'https://placehold.co/800x450/e11d48/ffffff?text=Jollibee',
                'foods' => [
                    ['Chickenjoy', 95, 'Crispy fried chicken with gravy.'],
                    ['Jolly Spaghetti', 75, 'Sweet Filipino-style spaghetti.'],
                    ['Burger Steak', 89, 'Burger patties with mushroom gravy.'],
                    ['Yum Burger', 45, 'Classic burger with special dressing.'],
                    ['Palabok Fiesta', 110, 'Rice noodles with savory palabok sauce.'],
                ],
            ],
            "McDonald's" => [
                'description' => 'World-famous burgers and fries',
                'logo' => 'https://placehold.co/800x450/facc15/111827?text=McDonald%27s',
                'foods' => [
                    ['Big Mac', 180, 'Double-layer burger with special sauce.'],
                    ['Cheeseburger', 75, 'Beef patty with cheese and pickles.'],
                    ['McChicken', 120, 'Crispy chicken sandwich.'],
                    ['Fries', 65, 'Golden crispy fries.'],
                    ['McFloat', 55, 'Soft drink topped with vanilla soft serve.'],
                ],
            ],
            'Mang Inasal' => [
                'description' => 'Grilled chicken and Pinoy classics',
                'logo' => 'https://placehold.co/800x450/16a34a/ffffff?text=Mang+Inasal',
                'foods' => [
                    ['Paa Large', 145, 'Grilled chicken leg quarter.'],
                    ['Pecho Large', 165, 'Grilled chicken breast and wing.'],
                    ['Pork BBQ', 99, 'Sweet and smoky pork barbecue.'],
                    ['Palabok', 89, 'Classic palabok with toppings.'],
                    ['Halo-Halo', 79, 'Cold Filipino dessert with mixed sweets.'],
                ],
            ],
            'KFC' => [
                'description' => 'Finger lickin good chicken',
                'logo' => 'https://placehold.co/800x450/dc2626/ffffff?text=KFC',
                'foods' => [
                    ['Original Recipe Chicken', 130, 'Signature seasoned fried chicken.'],
                    ['Zinger Burger', 155, 'Spicy crispy chicken burger.'],
                    ['Famous Bowl', 95, 'Mashed potato bowl with chicken shots.'],
                    ['Twister', 120, 'Chicken wrap with vegetables.'],
                    ['Bucket Fries', 90, 'Large serving of crispy fries.'],
                ],
            ],
            'Starbucks' => [
                'description' => 'Coffee, drinks and pastries',
                'logo' => 'https://placehold.co/800x450/047857/ffffff?text=Starbucks',
                'foods' => [
                    ['Caramel Macchiato', 185, 'Espresso with milk and caramel.'],
                    ['Caffe Latte', 165, 'Smooth espresso and steamed milk.'],
                    ['Java Chip Frappuccino', 205, 'Blended coffee with chocolate chips.'],
                    ['Blueberry Cheesecake', 195, 'Creamy cheesecake with blueberries.'],
                    ['Chocolate Doughnut', 95, 'Soft doughnut with chocolate glaze.'],
                ],
            ],
        ];

        $branchAdmins = [
            'Jollibee'    => ['jollibee.admin@hosteleats.test',    'Jollibee Admin'],
            "McDonald's"  => ['mcdonalds.admin@hosteleats.test',   "McDonald's Admin"],
            'Mang Inasal' => ['manginasal.admin@hosteleats.test',  'Mang Inasal Admin'],
            'KFC'         => ['kfc.admin@hosteleats.test',         'KFC Admin'],
            'Starbucks'   => ['starbucks.admin@hosteleats.test',   'Starbucks Admin'],
        ];

        foreach ($branches as $name => $data) {
            $branch = Branch::updateOrCreate(
                ['name' => $name],
                [
                    'description' => $data['description'],
                    'logo'        => $data['logo'],
                ]
            );

            // Seed branch admin user
            if (isset($branchAdmins[$name])) {
                [$email, $adminName] = $branchAdmins[$name];
                User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name'      => $adminName,
                        'password'  => Hash::make('branch123'),
                        'is_admin'  => true,
                        'role'      => 'branch_admin',
                        'branch_id' => $branch->id,
                    ]
                );
            }

            foreach ($data['foods'] as [$foodName, $price, $description]) {
                Food::updateOrCreate(
                    ['branch_id' => $branch->id, 'name' => $foodName],
                    [
                        'price'       => $price,
                        'description' => $description,
                        'image'       => 'https://placehold.co/800x450/f3f4f6/111827?text='.urlencode($foodName),
                    ]
                );
            }
        }
    }
}
