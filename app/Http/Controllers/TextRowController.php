<?php

namespace App\Http\Controllers;

use App\Enums\RowStatusEnum;
use App\Models\SheetConfig;
use App\Models\TextRow;
use App\Repositories\TextRowRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;


class TextRowController extends Controller
{
    public function __construct(private readonly TextRowRepository $repository)
    {
    }

    public function index(): View
    {
        try {
            return view('index', [
                'textRows' => $this->repository->getAll(),
                'sheet'    => SheetConfig::first(),
            ]);
        } catch (Exception $e) {
            Log::error('Error while getting rows' . $e->getMessage(), [$e->getTraceAsString()]);

            return view('index', []);
        }
    }

    public function create(): View
    {
        return view('create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'text'   => 'required|string|min:1|max:1024',
            'status' => 'in:' . implode(',', array_column(RowStatusEnum::cases(), 'value')),
        ]);

        try {
            TextRow::create($validated);
        } catch (Exception $e) {
            Log::error('Error while saving row' . $e->getMessage(), [$e->getTraceAsString()]);

            return redirect()->back()->with('error', 'Что-то пошло не так. Попробуйте ещё раз.');
        }

        return redirect()->route('home')->with('success', 'Запись была создана');
    }

    public function edit(TextRow $textRow): View
    {
        return view('edit', ['row' => $textRow]);
    }

    // TODO Create update method in Repository
    public function updateStatus(TextRow $textRow, Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            TextRow::ATTRIBUTE_STATUS => ['required', 'in:' . implode(',', array_column(RowStatusEnum::cases(), 'value'))],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $textRow->status = $request->input(TextRow::ATTRIBUTE_STATUS);
            $textRow->save();

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            Log::error('Error while updating (status) model', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'row_id'  => $textRow->id,
            ]);

            return response()->json(['success' => false], 500);
        }
    }

    public function update(TextRow $textRow, Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            TextRow::ATTRIBUTE_TEXT   => 'required|string|min:1|max:1024',
            TextRow::ATTRIBUTE_STATUS => ['required', 'in:' . implode(',', array_column(RowStatusEnum::cases(), 'value'))],
        ]);

        try {
            $textRow->fill($validated)->save();

            return redirect()->back()->with('success', 'Запись обновлена');
        } catch (Exception $e) {
            Log::error('Error while updating model', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'row_id'  => $textRow->id,
            ]);

            return redirect()->back()->with('error', 'Не удалось обновить запись');
        }
    }

    public function delete(TextRow $textRow): RedirectResponse
    {
        try {
            $this->repository->delete($textRow);

            return redirect()->back()->with('success', 'Запись была удалена.');
        } catch (Exception $e) {
            Log::error('Error while deleting row' . $e->getMessage(), [$e->getTraceAsString()]);

            return redirect()->back()->with('error', 'Не удалось удалить запись. Попробуйте ещё раз.');
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
}
