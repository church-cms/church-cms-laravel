<?php

namespace Database\Factories;

use App\Models\Fund;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FundFactory extends Factory
{
    protected $model = Fund::class;

    public function definition(): array
    {
        $this->faker->addProvider(new \Faker\Provider\kk_KZ\Company($this->faker));
        $this->faker->addProvider(new \Faker\Provider\ms_MY\Payment($this->faker));

        $authorised_at = $this->faker->dateTimeBetween('-365 days', '+1 days');
        $membership    = $this->faker->randomElement(['guest', 'member']);
        $users         = User::ByRole(5)->pluck('id')->toArray();
        $amount        = $this->faker->numberBetween(100, 9000);
        $method        = $this->faker->randomElement(['card', 'cash', 'cheque', 'demanddraft']);

        $user_id = null;
        $data    = [];

        if ($membership === 'member') {
            $user_id = $this->faker->randomElement($users);
        } else {
            $data['first_name']    = $this->faker->unique()->firstName;
            $data['last_name']     = $this->faker->unique()->lastName;
            $data['address']       = $this->faker->unique()->address;
            $data['mobile_number'] = $this->faker->unique()->randomNumber(9, false);
        }

        $payment_details = [];

        if ($method === 'cheque') {
            $payment_details['cheque_number']  = $this->faker->unique()->randomNumber(6, false);
            $payment_details['account_number'] = $this->faker->businessIdentificationNumber;
            $payment_details['payee_name']     = $this->faker->name;
        } elseif ($method === 'card') {
            $payment_details['card_name'] = $this->faker->creditCardType;
            $payment_details['bank_name'] = $this->faker->bank;
        } elseif ($method === 'demanddraft') {
            $payment_details['payable_at']     = $this->faker->city;
            $payment_details['account_number'] = $this->faker->businessIdentificationNumber;
        }

        return [
            'authorised_at'   => $authorised_at,
            'membership'      => $membership,
            'user_id'         => $user_id,
            'data'            => $data,
            'amount'          => $amount,
            'method'          => $method,
            'payment_details' => $payment_details,
            'uuid'            => uniqid(),
        ];
    }
}
