<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fsm\Application;
use App\Models\Fsm\Containment;
use App\Models\Fsm\ServiceProvider;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ApiServiceController extends Controller
{
    public function getApplicationDetails($application_id){
        try {
            $application = Application::whereNull('deleted_at')->findOrFail($application_id);
        } catch(ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => "No Application found for ID $application_id."
            ], 500);
        } catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
        return [
            'success' => true,
            'data' => $application,
            'message' => 'Application Details.'
        ];
    }

    public function getContainmentDetails($application_id){
        try {
            $application = Application::findOrFail($application_id);
            $containments = explode(" ",$application->containment_id);
            $data = [];
            foreach ($containments as $containment_id){
                array_push($data,Containment::findOrFail($containment_id));
            }
        } catch(ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => "No containment found or application with ID $application_id doesn't exist."
            ], 500);
        } catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
        return [
            'success' => true,
            'data' => $data,
            'message' => 'Containment details for application ' . $application_id
        ];
    }

    public function getServiceProviders(){
        try {
            $serviceProviders = ServiceProvider::Operational()->pluck('company_name','id')->toArray();
        } catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
        return [
            'success' => true,
            'data' => $serviceProviders,
            'message' => 'Service Providers.'
        ];
    }

}
