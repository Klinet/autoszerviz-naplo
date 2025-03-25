<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Domains\Car\Models\Car;

class CarsTableSeeder extends Seeder
{
    public function run(): void
    {
        if (Car::count() === 0) {
            try {
                $json = File::get(database_path('seeders/data/cars.json'));
                $data = json_decode($json);

                if ($data === null) {
                    Log::error('json_decode returned null. Check for invalid JSON in cars.json. Error: ' . json_last_error_msg());
                    return;
                }
            } catch (\Exception $e) {
                Log::error('Error reading or decoding cars.json: ' . $e->getMessage());
                return;
            }

            foreach ($data as $obj) {
                try {
                    Car::create([
                        'id' => $obj->id,
                        'client_id' => $obj->client_id,
                        'car_id' => $obj->car_id,
                        'type' => $obj->type,
                        'registered' => $obj->registered,
                        'ownbrand' => $obj->ownbrand,
                        'accident' => $obj->accident,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error creating car: ' . $e->getMessage() . ' Data: ' . json_encode($obj));
                    return;
                }
            }
            Log::info('CarsTableSeeder lefutott sikeresen.');
        }
        else {
            Log::info('CarsTableSeeder: A cars tábla már tartalmaz adatokat, a seeder nem futott le.');
        }
    }
}
