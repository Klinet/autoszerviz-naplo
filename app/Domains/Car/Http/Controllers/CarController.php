<?php

namespace App\Domains\Car\Http\Controllers;

use App\Domains\Car\Http\Requests\StoreCarRequest;
use App\Domains\Car\Http\Requests\UpdateCarRequest;
use App\Domains\Car\Http\Resources\CarResource;
use App\Domains\Car\Models\Car;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CarController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return CarResource::collection(Car::all());
    }

    public function store(StoreCarRequest $request): CarResource
    {
        $car = Car::create($request->validated());
        return new CarResource($car);
    }

    public function show(Car $car): CarResource
    {
        return new CarResource($car);
    }

    public function update(UpdateCarRequest $request, Car $car): CarResource
    {
        $car->update($request->validated());
        return new CarResource($car);
    }

    public function destroy(Car $car): JsonResponse
    {
        $car->delete();
        return response()->json(null, 204);
    }
}
