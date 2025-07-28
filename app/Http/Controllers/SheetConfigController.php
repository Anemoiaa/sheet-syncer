<?php

namespace App\Http\Controllers;

use App\Helpers\GoogleSheetHelper;
use App\Models\SheetConfig;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SheetConfigController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'url' => 'required|url',
        ]);

        $spreadsheetId = GoogleSheetHelper::extractIdFromUrl($validated['url']);

        if (!$spreadsheetId) {
            return redirect()
                ->back()
                ->withErrors(['url' => 'Неверная ссылка на Google Sheets'])
                ->withInput();
        }

        try {
            $config = SheetConfig::firstOrFail();
            $config->spreadsheet_id = $spreadsheetId;
            $config->url = $validated['url'];
            $config->save();

            return redirect()->back()->with('success', 'Настройки успешно сохранены.');
        } catch (Exception) {
            return redirect()->back()->with('error', 'Ошибка. Попробуйте ёще раз.');
        }
    }
}
