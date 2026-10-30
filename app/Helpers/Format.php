<?php

declare(strict_types=1);

class Format
{
    public static function status(string $status): string
    {
        $class = match ($status) {
            'ACTIVE', 'SUCCESS' => 'status-success',
            'BLOCKED', 'FAILED' => 'status-danger',
            'PENDING'           => 'status-warning',
            default             => 'status-neutral',
        };

        $label = ucfirst(strtolower($status));

        return '<span class="status ' . $class . '">' . Security::escape($label) . '</span>';
    }

    public static function cardNumber(string $number): string
    {
        $digits = preg_replace('/\D/', '', $number) ?? '';

        if (strlen($digits) < 13) {
            return '••••';
        }

        $masked = substr($digits, 0, 6)
            . str_repeat('•', strlen($digits) - 10)
            . substr($digits, -4);

        return implode(' ', mb_str_split($masked, 4));
    }

    public static function money(int|float $amount, string $currency): string
    {
        return number_format($amount, 0, '.', ' ') . ' ' . $currency;
    }
}