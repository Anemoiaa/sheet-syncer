<?php

namespace App\Http\Controllers;

use App\Enums\RowStatusEnum;
use App\Models\TextRow;
use App\Repositories\TextRowRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class TextRowController extends Controller
{
    public function __construct(private readonly TextRowRepository $repository)
    {
    }

    public function index(): View
    {
        try {
            $data = $this->repository->getAll();

            return view('welcome', [
                'textRows' => $data,
            ]);
        } catch (Exception $e) {
            Log::error('Error while getting rows' . $e->getMessage(), [$e->getTraceAsString()]);

            return view('welcome', []);
        }
    }

    public function update(TextRow $textRow, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:' . implode(',', array_column(RowStatusEnum::cases(), 'value'))],
        ]);

        $textRow->status = $validated['status'];
        $textRow->save();

        return response()->json(['success' => true]);
    }

    public function generate(Request $request): RedirectResponse
    {
        $count = (int) $request->input('count', 1000);

        try {
            $this->repository->generate($count);

            return redirect()->back()->with('success', "Сгенерировано {$count} строк.");
        } catch (Exception $e) {
            Log::error('Error while generating rows' . $e->getMessage(), [$e->getTraceAsString()]);

            return redirect()->back()->with('error', 'Не удалось сгенерировать строки. Попробуйте ещё раз.');
        }
    }

    public function deleteAll(): RedirectResponse
    {
        try {
            $this->repository->deleteAll();

            return redirect()->back()->with('success', 'Все записи были удалены.');
        } catch (Exception $e) {
            Log::error('Error while deleting rows' . $e->getMessage(), [$e->getTraceAsString()]);

            return redirect()->back()->with('error', 'Не удалось удалить записи. Попробуйте ещё раз.');
        }
    }
}
