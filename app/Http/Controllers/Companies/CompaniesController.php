<?php

namespace App\Http\Controllers\Companies;

use App\Interfaces\Companies\ICompanie;
use App\Models\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CompaniesController extends Controller
{
    protected $companyService;

    public function __construct(ICompanie $companyService)
    {
        $this->companyService = $companyService;
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index(): JsonResponse
    {
        try {
            $companies = $this->companyService->getAll();
            return response()->json(['companies' => $companies], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            if (!$company = $this->companyService->getOne($id)) {
                return response()->json([
                    'message' => 'Company is not found',
                ], Response::HTTP_NOT_FOUND);
            }
            return response()->json(['companie' => $company], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $company = $this->companyService->create($request->all());
            return response()->json(['company' => $company], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            if (!$company = $this->companyService->getOne($id)) {
                return response()->json([
                    'message' => "Company is not found"
                ]);
            }
            $this->companyService->update($id, $request->all());
            return response()->json(['message' => 'Company updated is successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id) : JsonResponse
    {
        try {
            if (!$company = $this->companyService->getOne($id)) {
                return response()->json([
                    'message' => "Company is not found"
                ]);
            }
            $this->companyService->delete($id);
            return response()->json(['message' => 'Company deleted is successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
