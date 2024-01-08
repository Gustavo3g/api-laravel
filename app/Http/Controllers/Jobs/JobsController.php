<?php

namespace App\Http\Controllers;

use App\Interfaces\Jobs\IJob;
use App\Models\Job;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class JobsController extends Controller
{
    protected $jobService;
    public function __construct(IJob $jobService) {
        $this->jobService = $jobService;
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        try {
            $jobs = Job::with('company')->get();
            return response()->json($jobs);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $job = Job::with('company')->find($id);

            if(!$job) {
                return response()->json([
                    'message'   => 'Record not found',
                ], 404);
            }

            return response()->json($job);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $job = new Job();
            $job->fill($request->all());
            $job->save();

            return response()->json($job, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $job = Job::find($id);

            if(!$job){
                return response()->json([
                    'message' => "Record not found"
                ]);
            }

            $job->fill($request->all());
            $job->save();

            return response()->json($job);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $job = Job::find($id);

            if(!$job){
                return response()->json([
                    'message' => "Record not found"
                ], 404);
            }

            $job->delete();
            return response()->json(['message' => 'Record deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
