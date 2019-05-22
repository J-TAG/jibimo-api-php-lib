<?php


namespace puresoft\jibimo\internals;


use puresoft\jibimo\exceptions\InvalidMobileNumberException;

class DataNormalizer
{
    public static function normalizeMobileNumber(string $mobileNumber): string
    {
        // First normalize digits
        $normalizedNumber = self::persianToEnglishNumber($mobileNumber);


        if(strlen($normalizedNumber) === 10) {
            // Something like: 9366061280

            if(strpos($normalizedNumber, '9') === 0) {
                // Mobile maybe valid, so correct its form and return it back
                return '+98' . $normalizedNumber; // +98 9366061280
            }

        } else if (strlen($normalizedNumber) === 11) {
            // Something like: 09366061280

            if(strpos($normalizedNumber, '09') === 0) {
                // Mobile maybe valid, so correct its form and return it back
                return '+98' . substr($normalizedNumber, 1); // +98 (Remove 0)9366061280
            }

        } else if(strlen($normalizedNumber) === 12) {
            // Something like: 989366061280

            if(strpos($normalizedNumber, '989') === 0) {
                // Mobile maybe valid, so correct its form and return it back
                return '+' . $normalizedNumber; // + 989366061280
            }

        } else if(strlen($normalizedNumber) === 13) {
            // Something like: +989366061280

            if(strpos($normalizedNumber, '+989') === 0) {
                // Mobile maybe valid and it doesn't need any modification, so return it back as what is it
                return $normalizedNumber; // +989366061280 (No modification)
            }

        }

        // Mobile number structure is invalid, so throw exception
        throw new InvalidMobileNumberException("This mobile number format is invalid and can not be normalized:
         `$normalizedNumber`. Please double check the length of digits and use a format like +989123456789 or 989123456789 or 09123456789 or 9123456789.");


    }

    public static function normalizePrivacyLevel(string $privacyLevel): string
    {
        // TODO: Normalize privacy level here
    }

    /**
     * This method will gets a string which may or may not contain Persian numbers and then convert it to English
     * numbers.
     * @param string $stringContainsNumber
     * @return string The normalized string.
     */
    public static function persianToEnglishNumber(string $stringContainsNumber): string
    {
        $result = str_replace('۰', '0', $stringContainsNumber);
        $result = str_replace('۱', '1', $result);
        $result = str_replace('۲', '2', $result);
        $result = str_replace('۳', '3', $result);
        $result = str_replace('۴', '4', $result);
        $result = str_replace('۵', '5', $result);
        $result = str_replace('۶', '6', $result);
        $result = str_replace('۷', '7', $result);
        $result = str_replace('۸', '8', $result);
        $result = str_replace('۹', '9', $result);

        return $result;
    }
}