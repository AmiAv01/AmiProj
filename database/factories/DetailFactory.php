<?php

namespace Database\Factories;

use App\Models\Detail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DetailFactory extends Factory
{
    protected $model = Detail::class;

    public function definition(): array
    {
        return [
            'dt_id' => $this->faker->randomNumber(),
            'dt_code' => $this->faker->randomNumber(),
            'dt_extcode' => $this->faker->randomNumber(),
            'dt_extname' => $this->faker->randomNumber(),
            'dt_type' => $this->faker->word(),
            'dt_comment' => $this->faker->word(),
            'dt_foto' => $this->faker->word(),
            'dt_invoice' => $this->faker->word(),
            'dt_netto' => $this->faker->randomNumber(),
            'dt_oem' => $this->faker->word(),
            'dt_baza' => $this->faker->randomNumber(),
            'dt_cena' => $this->faker->randomNumber(),
            'dt_prod' => $this->faker->randomNumber(),
            'dt_ost' => $this->faker->randomNumber(),
            'dt_ostc' => $this->faker->randomNumber(),
            'dt_typec' => $this->faker->word(),
            'dt_bp' => $this->faker->randomNumber(),
            'dt_cargo' => $this->faker->word(),
            'dt_e' => $this->faker->randomNumber(),
            'dt_hs' => $this->faker->randomNumber(),
            'dt_datep' => Carbon::now(),
            'dt_name' => $this->faker->name(),
            'fr_code' => $this->faker->word(),
            'dt_tp_ptype' => $this->faker->randomNumber(),
            'lt_dt_acode' => $this->faker->randomNumber(),
            'deleted_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
