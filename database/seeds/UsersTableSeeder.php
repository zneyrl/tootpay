<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;
use App\Models\Role;
use App\Models\TootCard;
use Carbon\Carbon;
use App\Models\Setting;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create(User::adminJson())->roles()->attach(Role::find(admin()));
        User::create(User::guestJson())->roles()->attach(Role::find(guest()));

        foreach (User::cashiersJson() as $cashier) {
            User::create($cashier)->roles()->attach(Role::find(cashier()));
        }

        $john = User::create([
            'id' => '00420130015',
            'name' => 'John Brian Alvior',
            'email' => 'johnbrianalvior@gmail.com',
            'phone_number' => '09279167296',
            'password' => bcrypt('123456')
        ]);
        $john_card = TootCard::create([
            'id' => '0004249518',
            'uid' => '000424951806455214',
            'pin_code' => '1111',
            'load' => 80,
            'points' => 0,
            'is_active' => 'on',
            'expires_at' => Carbon::now()->addYear(intval(Setting::value('toot_card_expire_year_count')))
        ]);
        $john->roles()->attach(Role::find(cardholder()));
        $john->tootCards()->attach($john_card);

        $fara = User::create([
            'id' => '00420130276',
            'name' => 'Farrah Zeus Resurreccion',
            'email' => 'f.zresurreccion@gmail.com',
            'phone_number' => '09975117325',
            'password' => bcrypt('123456')
        ]);
        $fara_card = TootCard::create([
            'id' => '0004250635',
            'uid' => '000425063506456331',
            'pin_code' => '1234',
            'load' => 80,
            'points' => 0,
            'is_active' => 'on',
            'expires_at' => Carbon::now()->addYear(intval(Setting::value('toot_card_expire_year_count')))
        ]);
        $fara->roles()->attach(Role::find(cardholder()));
        $fara->tootCards()->attach($fara_card);

//        foreach (User::cardholdersJson() as $cardholder) {
//            $user = User::create($cardholder);
//            $user->roles()->attach(Role::find(cardholder()));
//
//            $faker = Faker::create();
//            $toot_card = TootCard::create([
//                'id' => '000' . $faker->randomNumber(7),
//                'uid' => '10012' . $faker->randomNumber(7),
//                'pin_code' => $faker->randomNumber(4),
//                'load' => 80,
//                'points' => 0,
//                'is_active' => 'on',
//                'expires_at' => Carbon::now()->addYear(intval(Setting::value('toot_card_expire_year_count'))),
//            ]);
//            $user->tootCards()->attach($toot_card);
//        }
//        $test = User::create(User::testJson());
//        $test->roles()->attach(Role::find(cardholder()));
//        $test->tootCards()->attach(TootCard::create(TootCard::testJson()));
    }
}
