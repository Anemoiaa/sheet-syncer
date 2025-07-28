<?php

namespace App\Helpers;

class GoogleSheetHelper
{
    public static function extractIdFromUrl(string $url): ?string
    {
        if (preg_match('~docs\.google\.com/spreadsheets/d/([a-zA-Z0-9-_]+)~', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
