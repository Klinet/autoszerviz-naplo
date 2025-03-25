<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Domains\ServiceLog\Models\ServiceLog;

class ServiceLogsTableSeeder extends Seeder
{
    public function run(): void
    {
        if (ServiceLog::count() === 0) {
            try {
                $json = File::get(database_path('seeders/data/services.json'));
                $data = json_decode($json);
                if ($data === null) {
                    Log::error('json_decode returned null. Check for invalid JSON in services.json. Error: ' . json_last_error_msg());
                    return;
                }
            }
            catch (\Exception $e){
                Log::error('Error reading or decoding services.json: ' . $e->getMessage());
                return;
            }

            foreach ($data as $obj) {
                $eventId = config("event_types.types.{$obj->event}.id");
                if ($eventId !== null) {
                    ServiceLog::create([
                        'id' => $obj->id,
                        'client_id' => $obj->client_id,
                        'car_id' => $obj->car_id,
                        'lognumber' => $obj->lognumber,
                        'event_id' => $eventId,
                        'eventtime' => $obj->eventtime,
                        'document_id' => $obj->document_id,
                    ]);
                } else {
                    Log::warning("Invalid event type found in services.json: " . $obj->event . " for log entry with id: " .$obj->id);
                }
            }
            Log::info('ServiceLogsTableSeeder lefutott sikeresen.');
        } else {
            Log::info('ServiceLogsTableSeeder: A services tábla már tartalmaz adatokat, a seeder nem futott le.');
        }
    }
}
