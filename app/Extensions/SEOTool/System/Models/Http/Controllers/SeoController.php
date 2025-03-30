<?php

namespace App\Extensions\SEOTool\System\Http\Controllers;

use App\Extensions\SEOTool\System\Services\SEOService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeoController extends Controller
{
    public function index(): View
    {
        return view('seo-tool::index');
    }

    public function suggestKeywords(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        if ($user->remaining_words <= 0 and $user->remaining_words != -1) {
            $data = [
                'message' => ['You have no credits left. Please consider upgrading your plan.'],
            ];

            return response()->json($data, 419);
        }

        $keywords = SEOService::getKeywords($request->keyword);

        return response()->json(['result' => $keywords])->header('Content-Type', 'application/json');
    }

    public function analyseArticle(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        if ($user->remaining_words <= 0 and $user->remaining_words != -1) {
            $data = [
                'message' => ['You have no credits left. Please consider upgrading your plan.'],
            ];

            return response()->json($data, 419);
        }

        $analizingResult = SEOService::analiyzeWithAI($request);
        $percentage = $analizingResult['percentage'];
        $competitorList = $analizingResult['competitorList'];
        $longTailList = $analizingResult['longTailList'];

        return response()->json(['competitorList' => $competitorList, 'percentage'=> $percentage, 'longTailList' => $longTailList])->header('Content-Type', 'application/json');
    }

    public function improveArticle(Request $request)
    {
        $user = Auth::user();

        if ($user->remaining_words <= 0 and $user->remaining_words != -1) {
            $data = [
                'message' => ['You have no credits left. Please consider upgrading your plan.'],
            ];

            return response()->json($data, 419);
        }

        return SEOService::improveWithAI($request);
    }

    // article wizard
    public function generateKeywords(Request $request): ?\Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        if ($user->remaining_words <= 0 and $user->remaining_words != -1) {
            $data = [
                'message' => ['You have no credits left. Please consider upgrading your plan.'],
            ];

            return response()->json($data, 419);
        }

        try {
            $keywords = SEOService::getKeywords($request->topic);
            $jsonKeywords = collect($keywords)->map(function ($keyword) {
                return json_encode($keyword);
            });
            $jsonKeywords = "[\n" . $jsonKeywords->implode(",\n") . "\n]";

            return response()->json(['result' => $jsonKeywords])->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function genSearchQuestions(Request $request): ?\Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        if ($user->remaining_words <= 0 and $user->remaining_words != -1) {
            $data = [
                'message' => ['You have no credits left. Please consider upgrading your plan.'],
            ];

            return response()->json($data, 419);
        }

        try {
            $stringQuestions = SEOService::getSearchQuestions($request);

            return response()->json(['result' => $stringQuestions])->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // admin seo helpers
    public function genSEO(Request $request): ?\Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        if ($user->type !== 'admin') {
            return response()->json([
                'message' => 'You are not authorized to access this resource',
            ], 403);
        }

        try {
            $result = SEOService::generateSEO($request);

            return response()->json(['result' => $result])->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
