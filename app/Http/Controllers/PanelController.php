<?php

namespace App\Http\Controllers;

use App\Paling\Compare;
use App\Paling\Elevation;
use App\Paling\PanelException;
use App\Paling\PanelPlanner;
use App\Paling\SamplePanel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

    public function sampleElevation(): Response
    {
        return $this->svg(PanelPlanner::plan(SamplePanel::garden()));
    }

    public function store(Request $request): JsonResponse
    {
        $payload = $this->payload($request);
        if ($payload instanceof JsonResponse) {
            return $payload;
        }

        try {
            return response()->json(PanelPlanner::plan($payload));
        } catch (PanelException $exception) {
            return response()->json(['errors' => $exception->errors], 400);
        }
    }

    public function elevation(Request $request): JsonResponse|Response
    {
        $payload = $this->payload($request);
        if ($payload instanceof JsonResponse) {
            return $payload;
        }

        try {
            return $this->svg(PanelPlanner::plan($payload));
        } catch (PanelException $exception) {
            return response()->json(['errors' => $exception->errors], 400);
        }
    }

    public function compare(Request $request): JsonResponse
    {
        $payload = $this->payload($request);
        if ($payload instanceof JsonResponse) {
            return $payload;
        }

        $errors = [];
        $left = $this->side('left', $payload['left'] ?? null, $errors);
        $right = $this->side('right', $payload['right'] ?? null, $errors);
        if ($left === null || $right === null) {
            return response()->json(['errors' => $errors], 400);
        }

        $diff = Compare::panels($left, $right);

        return response()->json([
            'left' => $left['name'],
            'right' => $right['name'],
            'pickets' => $diff['pickets'],
            'woodLengthMm' => $diff['woodLengthMm'],
            'text' => Compare::format($left, $right, $diff),
        ]);
    }

    /**
     * @param  list<string>  $errors
     * @return array<string, int|string>|null
     */
    private function side(string $side, mixed $panel, array &$errors): ?array
    {
        if (! is_array($panel) || array_is_list($panel)) {
            $errors[] = $side.': Compare needs a '.$side.' panel';

            return null;
        }

        try {
            return PanelPlanner::plan($panel);
        } catch (PanelException $exception) {
            foreach ($exception->errors as $message) {
                $errors[] = $this->label($side, $message);
            }

            return null;
        }
    }

    private function label(string $side, string $message): string
    {
        $index = strpos($message, ':');
        if ($index === false) {
            return $side.': '.$message;
        }

        return $side.'.'.substr($message, 0, $index).substr($message, $index);
    }

    /**
     * @param  array<string, int|string>  $plan
     */
    private function svg(array $plan): Response
    {
        return response(Elevation::svg($plan), 200, [
            'Content-Type' => 'image/svg+xml; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.Elevation::fileName($plan['name']).'"',
        ]);
    }

    private function payload(Request $request): array|JsonResponse
    {
        $payload = json_decode($request->getContent(), true);
        if (! is_array($payload) || array_is_list($payload)) {
            return response()->json(['errors' => ['body: the body must be a JSON object']], 400);
        }

        return $payload;
    }
}
