<?php

namespace App\Domains\ServiceLog\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $eventKey = null;
        foreach (config('event_types.types', []) as $key => $details) {
            if ($details['id'] === $this->event_id) {
                $eventKey = $key;
                break;
            }
        }

        $eventName = $eventKey ? config("event_types.types.{$eventKey}.name") : 'Ismeretlen esemény';

        $displayEventTime = $this->eventtime;
        if ($eventKey === 'regisztralt' && $this->eventtime === null) {
            $this->loadMissing('car');
            $displayEventTime = $this->car ? $this->car->registered : null;
        }

        $formattedTime = null;
        if ($displayEventTime instanceof \DateTimeInterface) {
            $formattedTime = $displayEventTime->format('Y-m-d H:i:s');
        } elseif (is_string($displayEventTime)) {
            try {
                $formattedTime = Carbon::parse($displayEventTime)->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                $formattedTime = $displayEventTime;
            }
        }

        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'car_id' => $this->car_id,
            'log_number' => $this->lognumber,
            'event_id' => $this->event_id,
            'event_name' => $eventName,
            'event_time' => $formattedTime,
            'document_id' => $this->document_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
