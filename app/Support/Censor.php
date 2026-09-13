<?php

declare(strict_types=1);

namespace App\Support;

final class Censor
{
    /**
     * Censor generic string keeping visible chars at start and end.
     */
    public static function string(
        ?string $str,
        int $visibleStart = 1,
        int $visibleEnd = 1,
        string $maskChar = '*',
        ?int $fixedLength = null
    ): string {
        if ($str === null || $str === '') {
            return '';
        }

        $length = mb_strlen($str);
        if ($length <= ($visibleStart + $visibleEnd)) {
            return str_repeat($maskChar, max(1, $length));
        }

        $start = mb_substr($str, 0, $visibleStart);
        $end = $visibleEnd > 0 ? mb_substr($str, -$visibleEnd) : '';
        $maskCount = $fixedLength !== null ? $fixedLength : max(1, $length - $visibleStart - $visibleEnd);

        return $start . str_repeat($maskChar, $maskCount) . $end;
    }

    /**
     * Censor email address (e.g. "user@example.com" -> "u***r@example.com").
     */
    public static function email(
        ?string $email,
        int $visibleStart = 1,
        int $visibleEnd = 1,
        string $maskChar = '*',
        ?int $fixedLength = 3,
        bool $maskDomain = false
    ): string {
        if ($email === null || $email === '') {
            return '';
        }

        $atPos = mb_strpos($email, '@');
        if ($atPos === false) {
            return self::string($email, $visibleStart, $visibleEnd, $maskChar, $fixedLength);
        }

        $username = mb_substr($email, 0, $atPos);
        $domain = mb_substr($email, $atPos + 1);

        $uLen = mb_strlen($username);
        if ($uLen <= 2) {
            $maskedUsername = mb_substr($username, 0, 1) . $maskChar;
        } else {
            $uStart = mb_substr($username, 0, $visibleStart);
            $uEnd = $visibleEnd > 0 ? mb_substr($username, -$visibleEnd) : '';
            $maskCount = $fixedLength !== null ? $fixedLength : max(1, $uLen - $visibleStart - $visibleEnd);
            $maskedUsername = $uStart . str_repeat($maskChar, $maskCount) . $uEnd;
        }

        $finalDomain = $domain;
        if ($maskDomain) {
            $lastDot = mb_strrpos($domain, '.');
            if ($lastDot !== false && $lastDot > 0) {
                $domainName = mb_substr($domain, 0, $lastDot);
                $ext = mb_substr($domain, $lastDot);
                $finalDomain = self::string($domainName, 1, 0, $maskChar, 3) . $ext;
            }
        }

        return $maskedUsername . '@' . $finalDomain;
    }

    /**
     * Censor phone number keeping country code / start digits and trailing digits.
     */
    public static function phone(
        ?string $phone,
        int $visibleStart = 4,
        int $visibleEnd = 4,
        string $maskChar = '*',
        ?int $fixedLength = 4
    ): string {
        if ($phone === null || $phone === '') {
            return '';
        }

        $trimmed = trim($phone);
        $length = mb_strlen($trimmed);

        if ($length <= ($visibleStart + $visibleEnd)) {
            return str_repeat($maskChar, max(1, $length));
        }

        $start = mb_substr($trimmed, 0, $visibleStart);
        $end = mb_substr($trimmed, -$visibleEnd);
        $count = $fixedLength !== null ? $fixedLength : max(1, $length - $visibleStart - $visibleEnd);

        return $start . str_repeat($maskChar, $count) . $end;
    }

    /**
     * Censor full name (e.g. "Jane Doe" -> "J**e D*e").
     */
    public static function name(
        ?string $name,
        string $mode = 'edges',
        string $maskChar = '*'
    ): string {
        if ($name === null || $name === '') {
            return '';
        }

        $parts = preg_split('/\s+/', trim($name));
        if ($parts === false || empty($parts)) {
            return '';
        }

        $result = [];
        foreach ($parts as $index => $part) {
            $len = mb_strlen($part);
            if ($mode === 'initials') {
                $result[] = mb_substr($part, 0, 1) . '.';
            } elseif ($mode === 'firstOnly') {
                if ($index === 0) {
                    $result[] = $part;
                } else {
                    $result[] = mb_substr($part, 0, 1) . str_repeat($maskChar, max(1, $len - 1));
                }
            } else {
                if ($len <= 2) {
                    $result[] = mb_substr($part, 0, 1) . $maskChar;
                } else {
                    $result[] = mb_substr($part, 0, 1) . str_repeat($maskChar, max(1, $len - 2)) . mb_substr($part, -1);
                }
            }
        }

        return implode(' ', $result);
    }
}
