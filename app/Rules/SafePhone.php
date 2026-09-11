<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Rule para telefone/WhatsApp.
 *
 * Aceita apenas dígitos, "+", espaço, parênteses e hífen no formato de
 * entrada, exigindo entre 8 e 15 dígitos (padrão E.164) após limpeza.
 * Rejeita letras, HTML e quaisquer outros caracteres.
 */
class SafePhone implements ValidationRule
{
    private const ALLOWED_PATTERN = '/^\+?[0-9\-() ]+$/';

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! preg_match(self::ALLOWED_PATTERN, $value)) {
            $fail('O campo :attribute não é um telefone válido.');

            return;
        }

        $digits = preg_replace('/\D+/', '', $value) ?? '';
        $length = strlen($digits);

        if ($length < 8 || $length > 15) {
            $fail('O campo :attribute não é um telefone válido.');
        }
    }
}
