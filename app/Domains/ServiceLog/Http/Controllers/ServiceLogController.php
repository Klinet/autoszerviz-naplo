<?php

namespace App\Domains\ServiceLog\Http\Controllers;

use App\Domains\ServiceLog\Http\Requests\StoreServiceLogRequest;
use App\Domains\ServiceLog\Http\Requests\UpdateServiceLogRequest;
use App\Domains\ServiceLog\Http\Resources\ServiceLogResource;
use App\Domains\ServiceLog\Models\ServiceLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceLogController extends Controller
{
    public function index(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $query = ServiceLog::query();

        if ($request->has('car_id')) {
            $query->where('car_id', $request->query('car_id'));
        }
        if ($request->has('client_id')) {
            $query->where('client_id', $request->query('client_id'));
        }

        $query->orderBy('lognumber', 'desc');

        $serviceLogs = $query->paginate(15);

        $serviceLogs->load('car');

        return ServiceLogResource::collection($serviceLogs);
    }

    public function store(StoreServiceLogRequest $request): ServiceLogResource
    {
        $validatedData = $request->validated();
        $serviceLog = ServiceLog::create($validatedData);
        $serviceLog->load('car');

        return new ServiceLogResource($serviceLog);
    }

    public function show(ServiceLog $servicelog): ServiceLogResource
    {
        $servicelog->load('car');
        return new ServiceLogResource($servicelog);
    }

    public function update(UpdateServiceLogRequest $request, ServiceLog $servicelog): ServiceLogResource
    {
        $servicelog->update($request->validated());
        $servicelog->load('car');
        return new ServiceLogResource($servicelog->refresh());
    }

    public function destroy(ServiceLog $servicelog): JsonResponse
    {
        $servicelog->delete();
        return response()->json(null, 204);
    }
}
