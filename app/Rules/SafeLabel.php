<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Rule para nomes, títulos, labels e assuntos curtos (linha única).
 *
 * Allowlist: letras Unicode (com acentos/cedilha), espaço, hífen,
 * ponto, vírgula, dois pontos, interrogação, exclamação, parênteses, barra,
 * apóstrofos (simples e tipográfico), aspas tipográficas e "&".
 * Números são opcionais via construtor (úteis em títulos; desligue para nomes
 * de pessoa).
 *
 * Rejeita tags HTML, < > { } [ ] ` $ ; | \, caracteres de controle,
 * invisíveis/bidirecionais e prefixos de protocolo perigosos.
 */
class SafeLabel implements ValidationRule
{
    private const DANGEROUS_PROTOCOL_PATTERN = '/^\s*(javascript|data|vbscript)\s*:/iu';

    private const CONTROL_OR_INVISIBLE_PATTERN = '/[\x00-\x1F\x7F\x{200B}-\x{200F}\x{2060}\x{FEFF}\x{061C}\x{202A}-\x{202E}\x{2066}-\x{2069}]/u';

    public function __construct(
        private bool $allowNumbers = true,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('O campo :attribute é inválido.');

            return;
        }

        if (preg_match(self::CONTROL_OR_INVISIBLE_PATTERN, $value)) {
            $fail('O campo :attribute contém caracteres não permitidos.');

            return;
        }

        if (preg_match(self::DANGEROUS_PROTOCOL_PATTERN, $value)) {
            $fail('O campo :attribute contém conteúdo não permitido.');

            return;
        }

        if (! preg_match($this->allowedPattern(), $value)) {
            $fail($this->allowNumbers
                ? 'O campo :attribute contém caracteres não permitidos.'
                : 'O campo :attribute deve conter apenas letras e espaçamento válido, sem números.');
        }
    }

    private function allowedPattern(): string
    {
        $digits = $this->allowNumbers ? '\p{N}' : '';

        return '/^[\p{L}\p{M}'.$digits.' \-.,:?!()\/\'\x{2018}\x{2019}\x{201C}\x{201D}&]+$/u';
    }
}
