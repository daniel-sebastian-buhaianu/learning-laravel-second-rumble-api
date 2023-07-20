<?php

namespace App\Helpers;

use \DateTime;

class ConversionHelper
{
    /**
     * Convert a date string in the format "Aug 19, 2022" to MySQL date format (YYYY-MM-DD).
     *
     * @param string $inputDate The date string to convert.
     * @return string The date in MySQL date format (YYYY-MM-DD), or null if invalid.
     */
    static public function dateStringToMySQLDate(string $inputDate): ?string
    {
        // Create a DateTime object from the input date string
        $date = DateTime::createFromFormat('M d, Y', $inputDate);

        // Convert the DateTime object to the MySQL date format
        return $date ? $date->format('Y-m-d') : null;
    }

    /**
     * Convert a count string with optional multipliers (K, M, B) to an integer.
     *
     * Examples:
     * - "107"    => 107
     * - "107K"   => 107000
     * - "107M"   => 107000000
     * - "1.2K"   => 1200
     *
     * @param string $count The count string to convert.
     * @return int The converted integer value.
     */
    static public function countStringToInt(string $count): int
    {
        preg_match('/^(\d+\.?\d*)([KMB]?)$/', $count, $matches);

        $value = (float) $matches[1];
        $multiplier = strtoupper($matches[2]);

        switch ($multiplier) {
            case 'K':
                $value *= 1000;
                break;
            case 'M':
                $value *= 1000000;
                break;
            case 'B':
                $value *= 1000000000;
                break;
            default:
                // If no multiplier, the value is already correct
                break;
        }

        return (int) $value;
    }
}