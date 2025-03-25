<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Domains\Owner\Models\Owner;

class ClientsTableSeeder extends Seeder
{
    public function run(): void
    {
        if (Owner::count() === 0) {
            try {
                $json = File::get(database_path('seeders/data/clients.json'));
                $data = json_decode($json);

                if ($data === null) {
                    Log::error('json_decode returned null. Check for invalid JSON in clients.json. Error: ' . json_last_error_msg());
                    return;
                }
            } catch (\Exception $e) {
                Log::error('Error reading or decoding clients.json: ' . $e->getMessage());
                return;
            }

            foreach ($data as $obj) {
                try {
                    Owner::create([
                        'id' => $obj->id,
                        'name' => $obj->name,
                        'idcard' => $obj->idcard,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error creating owner: ' . $e->getMessage() . ' Data: ' . json_encode($obj));
                    return;
                }
            }
            Log::info('ClientsTableSeeder lefutott sikeresen.');
        } else {
            Log::info('ClientsTableSeeder: A clients tábla már tartalmaz adatokat, a seeder nem futott le.'); //Tábla nem üres log.
        }
    }
}
