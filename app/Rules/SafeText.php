<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Rule para textarea/mensagem livre (multilinha).
 *
 * Permite texto natural em português com pontuação comum e quebras de linha.
 * Rejeita tags HTML/script, byte nulo, caracteres de controle (exceto \n)
 * e caracteres invisíveis/bidirecionais perigosos.
 *
 * IMPORTANTE: não bloqueia palavras SQL (SELECT, DROP, UNION...). Proteção
 * contra SQL Injection é responsabilidade de bindings/Eloquent, não de
 * filtro de palavras — payloads SQLi aqui são apenas texto inofensivo.
 */
class SafeText implements ValidationRule
{
    /** Detecta abertura de tag HTML: "<" seguido de letra, "/", "!" ou "?". */
    private const HTML_TAG_PATTERN = '/<\s*[a-z!\/?]/iu';

    /** Controles ASCII exceto \n (x0A); \t e \r já viram espaço/linha na normalização. */
    private const CONTROL_PATTERN = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u';

    private const INVISIBLE_PATTERN = '/[\x{200B}-\x{200F}\x{2060}\x{FEFF}\x{061C}\x{202A}-\x{202E}\x{2066}-\x{2069}]/u';

    public function __construct(private int $maxLines = 40) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('O campo :attribute é inválido.');

            return;
        }

        if (preg_match(self::CONTROL_PATTERN, $value) || preg_match(self::INVISIBLE_PATTERN, $value)) {
            $fail('O campo :attribute contém caracteres não permitidos.');

            return;
        }

        if (preg_match(self::HTML_TAG_PATTERN, $value)) {
            $fail('O campo :attribute não pode conter código HTML.');

            return;
        }

        if (substr_count($value, "\n") + 1 > $this->maxLines) {
            $fail('O campo :attribute possui linhas demais.');
        }
    }
}
