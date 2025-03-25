<?php

namespace App\Domains\Owner\Http\Controllers;

use App\Domains\Owner\Http\Requests\SearchOwnerRequest;
use App\Domains\Owner\Models\Owner;
use App\Domains\Owner\Http\Resources\OwnerResource;
use App\Http\Controllers\Controller;
use App\Domains\Owner\Http\Requests\StoreOwnerRequest;
use App\Domains\Owner\Http\Requests\UpdateOwnerRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OwnerController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return OwnerResource::collection(Owner::paginate(15));
    }

    public function store(StoreOwnerRequest $request): OwnerResource
    {
        $owner = Owner::create($request->validated());
        return new OwnerResource($owner);
    }

    public function show(Owner $owner): OwnerResource
    {
        return new OwnerResource($owner);
    }

    public function update(UpdateOwnerRequest $request, Owner $owner): OwnerResource
    {
        $owner->update($request->validated());
        return new OwnerResource($owner->refresh());
    }

    public function destroy(Owner $owner): JsonResponse
    {
        $owner->delete();
        return response()->json(null, 204);
    }

    public function search(SearchOwnerRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $owner = null;
        $error = null;

        $owners = Owner::where('name', 'like', '%' . $validated['name'] . '%')->get();

        if (!empty($validated['name'])) {
            if ($owners->count() === 1) {
                $owner = $owners->first();
            } elseif ($owners->count() > 1) {
                die('SearchOwnerRequest');
            } else {
                die('SearchOwnerRequest2');
            }
        } elseif (!empty($validated['idcard'])) {
            $owner = Owner::where('idcard', $validated['idcard'])->first();
            if (!$owner) {
                $error = 'Nem található ügyfél ezzel az igazolványszámmal.';
                return response()->json(['message' => $error], 404);
            }
        }

        if ($owner) {
            $owner->load('cars.serviceLogs');

            $carsCount = $owner->cars->count();
            $totalServiceLogsCount = $owner->cars->reduce(function ($carry, $car) {
                return $carry + $car->serviceLogs->count();
            }, 0);

            return response()->json([
                'id' => $owner->id,
                'name' => $owner->name,
                'idcard' => $owner->idcard,
                'cars_count' => $carsCount,
                'total_service_logs_count' => $totalServiceLogsCount,
            ]);
        }

        return response()->json(['message' => $error ?? 'Ismeretlen hiba a keresés során.'], 500);
    }
}
