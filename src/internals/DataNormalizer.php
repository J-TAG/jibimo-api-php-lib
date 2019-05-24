<?php


namespace puresoft\jibimo\internals;


use puresoft\jibimo\exceptions\InvalidIbanException;
use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevelException;
use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatusException;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\payment\values\JibimoPrivacyLevel;
use puresoft\jibimo\payment\values\JibimoTransactionStatus;

class DataNormalizer
{
    /**
     * This method will normalize o mobile number for you to use in Jibimo API.
     * @param string $mobileNumber The mobile number to normalize.
     * @return string The normalized mobile number.
     * @throws InvalidMobileNumberException
     */
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
         `$normalizedNumber`. Please double check the length of digits and use a format like +989123456789
          , 989123456789 , 09123456789 or 9123456789.");


    }

    /**
     * This method will normalize a Jibimo privacy level string for you.
     * @param string $privacyLevel Jibimo privacy level to normalize.
     * @return string Normalized Jibimo privacy level.
     * @throws InvalidJibimoPrivacyLevelException
     */
    public static function normalizePrivacyLevel(string $privacyLevel): string
    {
        // Return correctly formatted items first of all
        switch ($privacyLevel) {
            case JibimoPrivacyLevel::PERSONAL:
            case JibimoPrivacyLevel::FRIEND:
            case JibimoPrivacyLevel::PUBLIC:
                return $privacyLevel;
        }

        // Remove extra whitespaces
        $trimmedPrivacyLevel = trim($privacyLevel);
        // Turn text into lowercase for better comparision
        $loweredPrivacyLevel = strtolower($trimmedPrivacyLevel);

        // If problem is string case sensitivity, we can correct it
        switch ($loweredPrivacyLevel) {
            case strtolower(JibimoPrivacyLevel::PERSONAL):
                return JibimoPrivacyLevel::PERSONAL;
            case strtolower(JibimoPrivacyLevel::FRIEND):
                return JibimoPrivacyLevel::FRIEND;
            case strtolower(JibimoPrivacyLevel::PUBLIC):
                return JibimoPrivacyLevel::PUBLIC;
        }

        // Privacy level is invalid
        throw new InvalidJibimoPrivacyLevelException("The provided Jibimo privacy level `$trimmedPrivacyLevel`
         is invalid. Please use one of `Personal`, `Friend` or `Public`.");
    }

    /**
     * This method will normalize a Jibimo transaction status string for you.
     * @param string $status The Jibimo transaction status to normal.
     * @return string Normalized Jibimo transaction status.
     * @throws InvalidJibimoTransactionStatusException
     */
    public static function normalizeTransactionStatus(string $status): string
    {
        // Return correctly formatted items first of all
        switch ($status) {
            case JibimoTransactionStatus::REJECTED:
            case JibimoTransactionStatus::PENDING:
            case JibimoTransactionStatus::ACCEPTED:
                return $status;
        }

        // Remove extra whitespaces
        $trimmedStatus = trim($status);
        // Turn text into lowercase for better comparision
        $loweredStatus = strtolower($trimmedStatus);

        // If problem is string case sensitivity, we can correct it
        switch ($loweredStatus) {
            case strtolower(JibimoTransactionStatus::REJECTED):
                return JibimoTransactionStatus::REJECTED;
            case strtolower(JibimoTransactionStatus::PENDING):
                return JibimoTransactionStatus::PENDING;
            case strtolower(JibimoTransactionStatus::ACCEPTED):
                return JibimoTransactionStatus::ACCEPTED;
        }

        // Status is invalid
        throw new InvalidJibimoTransactionStatusException("The provided Jibimo transaction status `$trimmedStatus`
         is unknown. It must be one of `Rejected`, `Pending` or `Accepted`.");
    }

    /**
     * @param string $iban
     * @return string
     * @throws InvalidIbanException
     */
    public static function normalizeIban(string $iban): string
    {
        // First normalize digits
        $englishDigits = self::persianToEnglishNumber($iban);

        // Check to see whether the format of IBAN is correct or not
        if(strlen($englishDigits) === 24) {
            // IBAN should not contains `IR`, e.g 140570028870010133089001

            if(is_numeric($englishDigits)) {
                // IBAN format is correct
                return $englishDigits;
            }

        } else if(strlen($englishDigits) === 26) {
            // IBAN MUST contains `IR`, e.g IR140570028870010133089001

            // In this case IBAN format is correct but contains `IR`, we can drop `IR` from it
            $irRemovedIban = substr($englishDigits, 2);

            if(is_numeric($irRemovedIban)) {
                return $irRemovedIban;
            }

        }

        // IBAN is invalid
        throw new InvalidIbanException("The provided IBAN `$englishDigits` is malformed. It must be either a 24 numerical
         digits string or a 26 character string which the last 24 characters are numeric.");
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