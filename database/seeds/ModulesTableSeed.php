<?php

use Illuminate\Database\Seeder;

class ModulesTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('modules')->truncate();

        // Plugins
        DB::table('modules')->insert([
            'parent_id' => 0,
            'name' => 'Plugins',
            'description' => 'Plugins',
            'src' => '-',
            'position' => 30,
            'table_name' => '-',
            'icon' => 'fa-plug',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Settings
        DB::table('modules')->insert([
            'parent_id' => 0,
            'name' => 'Settings',
            'description' => 'Settings',
            'src' => '-',
            'position' => 31,
            'table_name' => '-',
            'icon' => 'fa-cogs',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Products
        DB::table('modules')->insert([
            'parent_id' => 0,
            'name' => 'Pages',
            'description' => 'Pages',
            'src' => 'pages',
            'position' => 0,
            'table_name' => 'pages',
            'icon' => 'fa-file',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Product Collections
        DB::table('modules')->insert([
            'parent_id' => 0,
            'name' => 'Product Collections',
            'description' => 'Product Collections',
            'src' => 'collections',
            'position' => 0,
            'table_name' => 'collections',
            'icon' => 'fa-archive',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Prodcuct Categories
        DB::table('modules')->insert([
            'parent_id' => 0,
            'name' => 'Product Categories',
            'description' => 'Product Categories',
            'src' => 'product-categories',
            'position' => 0,
            'table_name' => 'product-categories',
            'icon' => 'fa-list-ul',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Products
        DB::table('modules')->insert([
            'parent_id' => 0,
            'name' => 'Products',
            'description' => 'Products',
            'src' => 'products',
            'position' => 0,
            'table_name' => 'products',
            'icon' => 'fa-mobile',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Product Parameters
        DB::table('modules')->insert([
            'parent_id' => 0,
            'name' => 'Product Parameters',
            'description' => 'Product Parameters',
            'src' => 'properties',
            'position' => 0,
            'table_name' => 'properties',
            'icon' => 'fa-th-list',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Product Post Categories
        DB::table('modules')->insert([
            'parent_id' => 0,
            'name' => 'Post Categories',
            'description' => 'Post Categories',
            'src' => 'categories',
            'position' => 0,
            'table_name' => 'categories',
            'icon' => 'fa-file-o',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Product Posts
        DB::table('modules')->insert([
            'parent_id' => 0,
            'name' => 'Posts',
            'description' => 'Posts',
            'src' => 'posts',
            'position' => 0,
            'table_name' => 'posts',
            'icon' => 'fa-pencil',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Product Brands
        DB::table('modules')->insert([
            'parent_id' => 0,
            'name' => 'Brands',
            'description' => 'Brands',
            'src' => 'brands',
            'position' => 0,
            'table_name' => 'brands',
            'icon' => 'fa-tag',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Product Promotions
        DB::table('modules')->insert([
            'parent_id' => 0,
            'name' => 'Promotions',
            'description' => 'Promotions',
            'src' => 'promotions',
            'position' => 0,
            'table_name' => 'promotions',
            'icon' => 'fa-briefcase',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Product Galleries
        DB::table('modules')->insert([
            'parent_id' => 0,
            'name' => 'Galleries',
            'description' => 'Galleries',
            'src' => 'galleries',
            'position' => 0,
            'table_name' => 'galleries',
            'icon' => 'fa-image',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Orders
        DB::table('modules')->insert([
            'parent_id' => 0,
            'name' => 'Orders',
            'description' => 'Orders',
            'src' => 'order',
            'position' => 0,
            'table_name' => 'orders',
            'icon' => 'fa-bed',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Returns
        DB::table('modules')->insert([
            'parent_id' => 0,
            'name' => 'Returns',
            'description' => 'Returns',
            'src' => 'returns',
            'position' => 0,
            'table_name' => 'returns',
            'icon' => 'fa-bed',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Lead management
        DB::table('modules')->insert([
            'parent_id' => 0,
            'name' => 'Lead management',
            'description' => 'Lead management',
            'src' => 'feedback',
            'position' => 0,
            'table_name' => 'feed_back',
            'icon' => 'fa-comment',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Plugins/Promo Codes
        DB::table('modules')->insert([
            'parent_id' => 1,
            'name' => 'Promo Codes',
            'description' => 'Promo Codes',
            'src' => 'promocodes',
            'position' => 1,
            'table_name' => 'promocodes',
            'icon' => '',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Plugins/Auto Upload
        DB::table('modules')->insert([
            'parent_id' => 1,
            'name' => 'Auto Upload',
            'description' => 'Auto Upload',
            'src' => 'quick-upload',
            'position' => 2,
            'table_name' => 'products',
            'icon' => '',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Plugins/Auto Meta
        DB::table('modules')->insert([
            'parent_id' => '1',
            'name' => 'Auto Meta',
            'description' => 'Auto Meta',
            'src' => 'autometa',
            'position' => 3,
            'table_name' => 'autometas',
            'icon' => '',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Plugins/Auto Alts
        DB::table('modules')->insert([
            'parent_id' => 1,
            'name' => 'Auto Alts',
            'description' => 'Auto Alts',
            'src' => 'autoalt',
            'position' => 4,
            'table_name' => 'autoalt',
            'icon' => '',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Plugins/Front Users
        DB::table('modules')->insert([
            'parent_id' => 1,
            'name' => 'Front Users',
            'description' => 'Front Users',
            'src' => 'frontusers',
            'position' => 5,
            'table_name' => 'front_users',
            'icon' => '',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Plugins/User Fields
        DB::table('modules')->insert([
            'parent_id' => 1,
            'name' => 'User Fields',
            'description' => 'User Fields',
            'src' => 'userfields',
            'position' => 6,
            'table_name' => 'userfields',
            'icon' => '',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        //  Plugins/Subproducts
        DB::table('modules')->insert([
            'parent_id' => 1,
            'name' => 'Subproducts',
            'description' => 'Subproducts',
            'src' => 'subproducts',
            'position' => 7,
            'table_name' => 'subproducts',
            'icon' => '',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Plugins/Modules
        DB::table('modules')->insert([
            'parent_id' => 1,
            'name' => 'Modules',
            'description' => 'Modules module',
            'src' => 'modules',
            'position' => 8,
            'table_name' => 'modules',
            'icon' => '',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Settings/General
        DB::table('modules')->insert([
            'parent_id' => 2,
            'name' => 'General',
            'description' => 'General',
            'src' => 'settings/general',
            'position' => 1,
            'table_name' => '-',
            'icon' => '',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Settings/Languages
        DB::table('modules')->insert([
            'parent_id' => 2,
            'name' => 'Languages',
            'description' => 'Languages',
            'src' => 'settings/languages',
            'position' => 2,
            'table_name' => '-',
            'icon' => '',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Settings/Images Crop
        DB::table('modules')->insert([
            'parent_id' => 2,
            'name' => 'Images Crop',
            'description' => 'Images Crop',
            'src' => 'settings/crop',
            'position' => 3,
            'table_name' => '-',
            'icon' => '',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // Settings/Contacts
        DB::table('modules')->insert([
            'parent_id' => 2,
            'name' => 'Contacts',
            'description' => 'Contacts',
            'src' => 'settings/contacts',
            'position' => 4,
            'table_name' => '-',
            'icon' => '',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
    }
}
