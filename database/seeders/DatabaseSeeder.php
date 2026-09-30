<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
       $this->call([
    RoleSeeder::class,
    OrganizationSeeder::class,
    UserSeeder::class,

    CategorySeeder::class,
    SupplierSeeder::class,
    ProductSeeder::class,        
    PlatformSeeder::class,       

    PlatformProductSeeder::class, 
    PlatformPricingSeeder::class, 

    WarehouseSeeder::class,
    BankSeeder::class,
    CouponSeeder::class,
    CustomerSeeder::class,
    ProductReviewSeeder::class,
]);

    }
}
