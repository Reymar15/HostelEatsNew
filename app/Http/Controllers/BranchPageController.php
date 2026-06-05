<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BranchPageController extends Controller
{
    public function show(string $branch): View|RedirectResponse
    {
        if (session('auth_role') !== 'user') {
            return redirect()->route('login')->with('error', 'Please login as user first.');
        }

        $data = $this->catalog();
        $selectedBranch = collect($data['branches'])->first(function (array $item) use ($branch): bool {
            return (string) $item['id'] === $branch || $item['slug'] === $branch;
        });

        if (! $selectedBranch) {
            abort(404);
        }

        $selectedFoods = collect($data['foods'])
            ->where('branch_id', $selectedBranch['id'])
            ->values();

        return view('pages.branch-show', array_merge($data, [
            'title' => $selectedBranch['name'],
            'selectedBranch' => $selectedBranch,
            'selectedFoods' => $selectedFoods,
            'selectedCategories' => $selectedFoods->pluck('category')->unique()->values(),
            'cartItems' => collect(session('cart', []))->values()->all(),
            'profile' => [
                'name' => session('auth_name', 'Juan Dela Cruz'),
                'email' => session('auth_email', 'juan.delacruz@student.edu'),
                'student_id' => session('auth_student_id', 'STU-2026-1048'),
                'hostel_block' => session('auth_hostel_block', 'Block C - Room 214'),
            ],
        ]));
    }

    private function catalog(): array
    {
        $branches = [
            ['id' => 1, 'name' => 'Jollibee', 'description' => 'Filipino fast-food favorites', 'status' => 'Open now', 'accent' => 'red', 'initials' => 'JB', 'banner' => 'https://images.unsplash.com/photo-1562967914-608f82629710?auto=format&fit=crop&w=1400&q=80', 'logo' => '/images/branches/jollibee-logo.svg'],
            ['id' => 2, 'name' => "McDonald's", 'description' => 'World-famous burgers and fries', 'status' => 'Open now', 'accent' => 'yellow', 'initials' => 'MC', 'banner' => 'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=1400&q=80', 'logo' => '/images/branches/mcdonalds-logo.svg'],
            ['id' => 3, 'name' => 'Mang Inasal', 'description' => 'Grilled chicken and Pinoy classics', 'status' => 'Open now', 'accent' => 'green', 'initials' => 'MI', 'banner' => 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?auto=format&fit=crop&w=1400&q=80', 'logo' => '/images/branches/mang-inasal-logo.svg'],
            ['id' => 4, 'name' => 'KFC', 'description' => "Finger lickin' good chicken meals", 'status' => 'Open now', 'accent' => 'crimson', 'initials' => 'KF', 'banner' => 'https://images.unsplash.com/photo-1626645738196-c2a7c87a8f58?auto=format&fit=crop&w=1400&q=80', 'logo' => '/images/branches/kfc-logo.svg'],
            ['id' => 5, 'name' => 'Starbucks', 'description' => 'Coffee, drinks and pastries', 'status' => 'Open now', 'accent' => 'sage', 'initials' => 'SB', 'banner' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1400&q=80', 'logo' => '/images/branches/starbucks-logo.svg'],
        ];

        $slugify = fn (string $value): string => strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $value), '-'));
        $branchLookup = collect($branches)->keyBy('name');

        $foods = [
            ['name' => 'Chickenjoy', 'branch' => 'Jollibee', 'category' => 'Chicken', 'price' => 95, 'tag' => 'Crispy favorite', 'photo' => 'https://images.pexels.com/photos/106343/pexels-photo-106343.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'Jolly Spaghetti', 'branch' => 'Jollibee', 'category' => 'Pasta', 'price' => 75, 'tag' => 'Sweet style', 'photo' => 'https://images.pexels.com/photos/1279330/pexels-photo-1279330.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'Burger Steak', 'branch' => 'Jollibee', 'category' => 'Chicken', 'price' => 89, 'tag' => 'Rice meal', 'photo' => 'https://images.pexels.com/photos/675951/pexels-photo-675951.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'Yum Burger', 'branch' => 'Jollibee', 'category' => 'Burgers', 'price' => 55, 'tag' => 'Classic snack', 'photo' => 'https://images.pexels.com/photos/1633578/pexels-photo-1633578.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'Palabok', 'branch' => 'Jollibee', 'category' => 'Pasta', 'price' => 105, 'tag' => 'Pinoy noodles', 'photo' => 'https://images.pexels.com/photos/2347311/pexels-photo-2347311.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'Big Mac', 'branch' => "McDonald's", 'category' => 'Burgers', 'price' => 180, 'tag' => 'Signature burger', 'photo' => 'https://images.pexels.com/photos/1639557/pexels-photo-1639557.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'McChicken', 'branch' => "McDonald's", 'category' => 'Burgers', 'price' => 145, 'tag' => 'Chicken burger', 'photo' => 'https://images.pexels.com/photos/1600711/pexels-photo-1600711.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'Fries', 'branch' => "McDonald's", 'category' => 'Burgers', 'price' => 75, 'tag' => 'Golden side', 'photo' => 'https://images.pexels.com/photos/1583884/pexels-photo-1583884.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'Cheeseburger', 'branch' => "McDonald's", 'category' => 'Burgers', 'price' => 120, 'tag' => 'Cheesy classic', 'photo' => 'https://images.pexels.com/photos/2983101/pexels-photo-2983101.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'McSpaghetti', 'branch' => "McDonald's", 'category' => 'Pasta', 'price' => 95, 'tag' => 'Pasta meal', 'photo' => 'https://images.pexels.com/photos/1527603/pexels-photo-1527603.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'Pecho Inasal', 'branch' => 'Mang Inasal', 'category' => 'Chicken', 'price' => 175, 'tag' => 'Grilled pecho', 'photo' => 'https://images.pexels.com/photos/2338407/pexels-photo-2338407.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'PM1 Chicken Inasal', 'branch' => 'Mang Inasal', 'category' => 'Chicken', 'price' => 145, 'tag' => 'Rice combo', 'photo' => 'https://images.pexels.com/photos/6210876/pexels-photo-6210876.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'Halo-Halo', 'branch' => 'Mang Inasal', 'category' => 'Drinks', 'price' => 99, 'tag' => 'Cold dessert', 'photo' => 'https://images.pexels.com/photos/1352278/pexels-photo-1352278.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'Pork Sisig', 'branch' => 'Mang Inasal', 'category' => 'Chicken', 'price' => 135, 'tag' => 'Sizzling style', 'photo' => 'https://images.pexels.com/photos/533325/pexels-photo-533325.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'Java Rice', 'branch' => 'Mang Inasal', 'category' => 'Chicken', 'price' => 45, 'tag' => 'Savory side', 'photo' => 'https://images.pexels.com/photos/723198/pexels-photo-723198.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'Original Recipe Chicken', 'branch' => 'KFC', 'category' => 'Chicken', 'price' => 145, 'tag' => 'Original recipe', 'photo' => 'https://images.pexels.com/photos/60616/fried-chicken-chicken-fried-crunchy-60616.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'Zinger Burger', 'branch' => 'KFC', 'category' => 'Burgers', 'price' => 155, 'tag' => 'Spicy crunch', 'photo' => 'https://images.pexels.com/photos/2271107/pexels-photo-2271107.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'Famous Bowl', 'branch' => 'KFC', 'category' => 'Chicken', 'price' => 135, 'tag' => 'Loaded bowl', 'photo' => 'https://images.pexels.com/photos/1640774/pexels-photo-1640774.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'Hotshots', 'branch' => 'KFC', 'category' => 'Chicken', 'price' => 99, 'tag' => 'Bite sized', 'photo' => 'https://images.pexels.com/photos/5840088/pexels-photo-5840088.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'Mashed Potato', 'branch' => 'KFC', 'category' => 'Chicken', 'price' => 65, 'tag' => 'Creamy side', 'photo' => 'https://images.pexels.com/photos/4110251/pexels-photo-4110251.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'Caramel Frappuccino', 'branch' => 'Starbucks', 'category' => 'Coffee', 'price' => 205, 'tag' => 'Blended coffee', 'photo' => 'https://images.pexels.com/photos/302899/pexels-photo-302899.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'Iced Americano', 'branch' => 'Starbucks', 'category' => 'Coffee', 'price' => 165, 'tag' => 'Cool espresso', 'photo' => 'https://images.pexels.com/photos/2615323/pexels-photo-2615323.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'Cappuccino', 'branch' => 'Starbucks', 'category' => 'Coffee', 'price' => 175, 'tag' => 'Foamy classic', 'photo' => 'https://images.pexels.com/photos/312418/pexels-photo-312418.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'Chocolate Cake', 'branch' => 'Starbucks', 'category' => 'Coffee', 'price' => 185, 'tag' => 'Rich slice', 'photo' => 'https://images.pexels.com/photos/291528/pexels-photo-291528.jpeg?auto=compress&cs=tinysrgb&w=900'],
            ['name' => 'Blueberry Cheesecake', 'branch' => 'Starbucks', 'category' => 'Coffee', 'price' => 195, 'tag' => 'Creamy dessert', 'photo' => 'https://images.pexels.com/photos/1126359/pexels-photo-1126359.jpeg?auto=compress&cs=tinysrgb&w=900'],
        ];

        $branches = array_map(function (array $branch) use ($slugify): array {
            $branch['slug'] = $slugify($branch['name']);
            $branch['branch_url'] = '/branches/'.$branch['id'];
            $branch['status_label'] = in_array($branch['name'], ['KFC'], true) ? 'Inactive' : 'Active';

            return $branch;
        }, $branches);

        $foods = array_map(function (array $food) use ($branchLookup, $slugify): array {
            $branch = $branchLookup[$food['branch']];
            $food['branch_id'] = $branch['id'];
            $food['id'] = $slugify($food['branch'].' '.$food['name']);
            $food['branch_url'] = '/branches/'.$branch['id'];
            $food['image'] = $food['photo'];
            $food['fallback_image'] = '/images/foods/'.$slugify($food['name']).'.svg';

            return $food;
        }, $foods);

        return [
            'branches' => $branches,
            'foods' => $foods,
            'foodsByBranch' => collect($foods)->groupBy('branch'),
            'categories' => collect($foods)->pluck('category')->unique()->values()->map(fn ($category) => [
                'name' => $category,
                'count' => collect($foods)->where('category', $category)->count(),
                'description' => $category.' menu items from partner branches.',
            ])->all(),
        ];
    }
}
