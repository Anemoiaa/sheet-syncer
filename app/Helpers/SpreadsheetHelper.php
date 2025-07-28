<?php

namespace App\Helpers;

class SpreadsheetHelper
{
    public static function numberToColumnLetter(int $number): string
    {
        $result = '';
        while ($number > 0) {
            $mod = ($number - 1) % 26;
            $result = chr(65 + $mod) . $result;
            $number = (int) (($number - $mod) / 26);
        }
        return $result;
    }
}
