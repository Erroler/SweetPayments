<?php

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\User;
use App\Models\Server;
use App\Models\Community;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        //factory(\App\Models\User::class, 50)->create();

        $users = [
            [
                'steamid' => '76561198044937482',
                'username' => 'DarkPanda',
                'community_name' => 'dildacios',
                'community_id' => '103582791434622862'
            ],
            [
                'steamid' => '76561198303985096',
                'username' => 'J4K',
                'community_name' => 'j4kne',
                'community_id' => '103582791434622861'
            ],
            [
                'steamid' => '76561198013119117',
                'username' => 'HallucinogenicTroll',
                'community_name' => 'ptfunpub',
                'community_id' => '103582791434622860'
            ],
            [
                'steamid' => '76561198037363252',
                'username' => 'Nkz',
                'community_name' => 'dildaciosz',
                'community_id' => '103582791434622864'
            ],
        ];
        foreach ($users as $user_sample) {
            $user = User::create([
                'username' => $user_sample['username'],
                'steamid' => $user_sample['steamid'],
                'avatar' => 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/4e/4ed81815592d937ff4d5bd5b71d1c94cdd85c1fe_full.jpg',
                'profile_url' => 'https://steamcommunity.com/id/pandabawss/',
                'balance' => $faker->randomFloat(2, 10.53, 50.42)
            ]);
            $community = Community::create([
                'small_name' => $user_sample['community_name'],
                'full_name' => 'Os dildacios',
                'group_id' => $user_sample['community_id'],
                'avatar' => 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/2b/2b45b54451f2937e96e86e74eb75363df09c3270_full.jpg',
                'user_id' => $user->id,
                'members' => 10
            ]);
            factory(\App\Models\Server::class, 8)->create(['community_id' => $community->id]);

            $subscriptions = factory(\App\Models\Subscription::class, 10)->create(['community_id' => $community->id]);

            // Generate sales.
            $startDate = Carbon::createFromTimeStamp($faker->dateTimeBetween('-8 months', '-6 months')->getTimestamp());

            while (true) {
                $startDate->addMinutes($faker->numberBetween(1, 2440));
                $subscription = $subscriptions->random();
                $end_date = clone $startDate;
                $end_date->addDays($subscription->duration);
                if (!$startDate->isPast()) {
                    break;
                }
                factory(\App\Models\Sale::class, 1)->create([
                    'subscription_id' => $subscription->id,
                    'created_at' => $startDate,
                    'expires_on' => $end_date,
                    'completed' => true
                ]);
            }

            // Generate account log.
            // $startDate = Carbon::createFromTimeStamp($faker->dateTimeBetween('-3 months', 'today')->getTimestamp());
            // while (true) {
            //     $startDate = $startDate->addMinutes($faker->numberBetween(2500, 7000));
            //     if (!$startDate->isPast()) {
            //         break;
            //     }
            //     $method = $faker->randomElement(['paysafecard', 'bank_transfer']);
            //     $ammount = $faker->randomFloat(2, 10, 100);
            //     \App\Models\AccountLog::create([
            //         'user_id' => $user->id,
            //         'action' => 'WITHDRAWL_REQUEST',
            //         'value' => $ammount,
            //         'created_at' => $startDate,
            //         'payment_method' => $method,
            //         'extra_info' => []
            //     ]);

            //     $startDate = $startDate->addMinutes($faker->numberBetween(2500, 7000));

            //     if (!$startDate->isPast()) {
            //         break;
            //     }

            //     \App\Models\AccountLog::create([
            //         'user_id' => $user->id,
            //         'action' => 'WITHDRAWL_APPROVAL',
            //         'value' => $ammount,
            //         'created_at' => $startDate,
            //         'payment_method' => $method,
            //         'extra_info' => []
            //     ]);
            // }

            // Tickets
            $startDate = Carbon::createFromTimeStamp($faker->dateTimeBetween('-9 months', '-8 months')->getTimestamp());
            while (true) {
                $startDate = $startDate->addMinutes($faker->numberBetween(5000, 10000));
                if (!$startDate->isPast()) {
                    break;
                }
                $ammount = $faker->randomFloat(2, 10, 100);
                $ticket = \App\Models\Ticket::create([
                    'user_id' => $user->id,
                    'status' =>  $faker->randomElement(['closed', 'awaiting_client_response', 'open']),
                    'title' => $faker->name,
                    'last_updated' => $startDate,
                ]);

                for($i = 0; $i < 3; $i++) {
                    \App\Models\TicketMessage::create([
                        'user_id' => $user->id,
                        'ticket_id' =>  $ticket->id,
                        'content' => $faker->sentences(3, true),
                        'created_at' => $faker->dateTimeBetween('-3 months', 'now')
                    ]);
                }

            }
        }
    }
}
