<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Rule para URLs/CTAs.
 *
 * Aceita apenas:
 * - URLs absolutas http:// ou https:// válidas (máx. 2048 caracteres);
 * - paths internos começando com "/" (mas nunca "//", que seria
 *   protocol-relative e apontaria para domínio externo).
 *
 * Rejeita javascript:, data:, file:, vbscript:, protocolos desconhecidos e
 * valores com caracteres de controle/invisíveis tentando esconder o protocolo.
 */
class SafeUrl implements ValidationRule
{
    private const MAX_LENGTH = 2048;

    private const CONTROL_OR_INVISIBLE_PATTERN = '/[\x00-\x1F\x7F\x{200B}-\x{200F}\x{2060}\x{FEFF}\x{061C}\x{202A}-\x{202E}\x{2066}-\x{2069}\s]/u';

    public function __construct(private bool $allowRelative = true) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || $value === '' || mb_strlen($value) > self::MAX_LENGTH) {
            $fail('O campo :attribute não é um endereço válido.');

            return;
        }

        // Espaços, controles e invisíveis não têm lugar em URL e podem
        // mascarar protocolos perigosos (ex.: "java\u200Bscript:").
        if (preg_match(self::CONTROL_OR_INVISIBLE_PATTERN, $value)) {
            $fail('O campo :attribute não é um endereço válido.');

            return;
        }

        if ($this->allowRelative && str_starts_with($value, '/')) {
            if (str_starts_with($value, '//') || str_contains($value, ':')) {
                $fail('O campo :attribute não é um endereço válido.');
            }

            return;
        }

        // A partir daqui só URL absoluta com protocolo explícito na allowlist.
        if (! preg_match('/^https?:\/\//i', $value)) {
            $fail('O campo :attribute deve começar com http:// ou https://.');

            return;
        }

        if (filter_var($value, FILTER_VALIDATE_URL) === false) {
            $fail('O campo :attribute não é um endereço válido.');
        }
    }
}
