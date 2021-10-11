<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;
use Tests\Adapters\SessionTest;
use Tests\Adapters\CookieTest;
use App\Models\Lang;
use App\Models\ProductCategory;


class UserTest extends TestCase
{
    public function testUserComeOnSite()
    {
        // $user_id = md5(rand(0, 9999999).date('Ysmsd'));

        $categoriesWhithChilds = ProductCategory::pluck('parent_id')->toArray();
        $categoryRand = ProductCategory::whereNotIn('id', $categoriesWhithChilds)->inRandomOrder()->first();

        // SessionTest::set('user_id', $user_id);
        // setcookie('user_id', $user_id, time() + 10000000, '/');

        $this->call('GET', '/')->assertStatus(404);
        $this->call('GET', '/ro')->assertStatus(200);
        $this->call('GET', '/ro/catalog/'.$categoryRand->alias)->assertStatus(200);
    }

    public function testUserRegistration()
    {
        $faker = Faker::create();

        $response = $this->call('POST', '/ro/registration', [
            '_token' => csrf_token(),
            'phone' => $faker->e164PhoneNumber,
            'name' => $faker->name,
            'password' => 'password',
            'terms_agreement' => 'on',
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    // public function testLoginPost(){
    //     $response = $this->call('POST', '/ro/login', [
    //         'phone' => 'badUsername@gmail.com',
    //         'password' => 'badPass',
    //         '_token' => csrf_token()
    //     ]);
    //
    //     $this->assertEquals(200, $response->getStatusCode());
    // }

    public function testAddtoCart(){
        $user_id = md5(rand(0, 9999999).date('Ysmsd'));

        $_COOKIE['user_id'] = $user_id;

        $response = $this->call('POST', '/ro/add-product-to-cart', [
            'subproductId' => 50,
            'qty' => '1',
            '_token' => csrf_token(),
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
