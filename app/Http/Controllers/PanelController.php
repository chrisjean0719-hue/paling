<?php

namespace App\Http\Controllers;

use App\Paling\PanelException;
use App\Paling\PanelPlanner;
use App\Paling\SamplePanel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PanelController extends Controller
{
    public function health(): JsonResponse
    {
        return response()->json(['status' => 'ok']);
    }

    public function sample(): JsonResponse
    {
        return response()->json(PanelPlanner::plan(SamplePanel::garden()));
    }

    public function store(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);
        if (! is_array($payload) || array_is_list($payload)) {
            return response()->json(['errors' => ['body: the body must be a JSON object']], 400);
        }

        try {
            return response()->json(PanelPlanner::plan($payload));
        } catch (PanelException $exception) {
            return response()->json(['errors' => $exception->errors], 400);
        }
    }
}
