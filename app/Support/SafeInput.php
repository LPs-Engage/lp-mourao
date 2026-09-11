<?php

namespace App\Support;

use Normalizer;

/**
 * Normalização segura e conservadora de entrada de formulários.
 *
 * Remove apenas o que é invisível ou perigoso (byte nulo, caracteres de
 * controle, zero-width, bidirecionais) sem alterar o conteúdo legítimo
 * percebido pelo usuário. Payloads visíveis (HTML, protocolos perigosos)
 * NÃO são "corrigidos" aqui — eles devem ser rejeitados pelas Rules com 422.
 */
final class SafeInput
{
    /**
     * Caracteres invisíveis/bidirecionais perigosos:
     * zero-width (U+200B–U+200D, U+2060, U+FEFF), marcas direcionais
     * (U+200E, U+200F, U+061C) e controles bidi (U+202A–U+202E, U+2066–U+2069).
     */
    private const INVISIBLE_PATTERN = '/[\x{200B}-\x{200F}\x{2060}\x{FEFF}\x{061C}\x{202A}-\x{202E}\x{2066}-\x{2069}]/u';

    /**
     * Normaliza campo de linha única (nome, título, assunto curto).
     * Remove controle/invisíveis, converte quebras/tabs em espaço,
     * colapsa espaços repetidos e aplica trim.
     */
    public static function normalizeLine(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        $value = self::stripDangerous($value);
        $value = preg_replace('/[ \t\r\n]+/u', ' ', $value) ?? $value;

        return trim($value);
    }

    /**
     * Normaliza campo multilinha (textarea/mensagem livre).
     * Preserva quebras de linha legítimas; remove controle/invisíveis,
     * limita linhas em branco consecutivas e espaços repetidos excessivos.
     */
    public static function normalizeMultiline(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        $value = str_replace(["\r\n", "\r"], "\n", $value);
        $value = self::stripDangerous($value);

        // No máximo uma linha em branco consecutiva.
        $value = preg_replace("/\n{3,}/u", "\n\n", $value) ?? $value;

        // Colapsa sequências excessivas de espaços/tabs dentro da linha.
        $value = preg_replace('/[ \t]{3,}/u', ' ', $value) ?? $value;

        return trim($value);
    }

    /**
     * Normaliza e-mail de contato: linha única, sem espaços internos, minúsculo.
     */
    public static function normalizeEmail(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        $value = self::normalizeLine($value);

        return mb_strtolower(str_replace(' ', '', $value));
    }

    /**
     * Normaliza telefone para a forma limpa: apenas dígitos,
     * preservando o "+" inicial (DDI) quando presente.
     */
    public static function normalizePhone(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        $value = trim(self::stripDangerous($value));
        $hasPlus = str_starts_with($value, '+');
        $digits = preg_replace('/\D+/', '', $value) ?? '';

        return ($hasPlus ? '+' : '').$digits;
    }

    /**
     * Remove byte nulo, caracteres de controle e invisíveis/bidi perigosos,
     * aplicando normalização Unicode NFC. Não altera conteúdo visível.
     */
    private static function stripDangerous(string $value): string
    {
        // Entrada com encoding inválido é descartada por segurança.
        if (! mb_check_encoding($value, 'UTF-8')) {
            return '';
        }

        if (class_exists(Normalizer::class)) {
            $value = Normalizer::normalize($value, Normalizer::FORM_C) ?: $value;
        }

        // Remove controles ASCII, preservando \t (x09), \n (x0A) e \r (x0D),
        // que são tratados pelos normalizadores de linha/multilinha.
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value) ?? $value;

        return preg_replace(self::INVISIBLE_PATTERN, '', $value) ?? $value;
    }
}
