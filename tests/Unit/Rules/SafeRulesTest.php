<?php

use App\Rules\SafeLabel;
use App\Rules\SafePhone;
use App\Rules\SafeText;
use App\Rules\SafeUrl;
use App\Support\SafeInput;
use Illuminate\Contracts\Validation\ValidationRule;

function ruleFails(ValidationRule $rule, mixed $value): bool
{
    $failed = false;

    $rule->validate('campo', $value, function () use (&$failed): void {
        $failed = true;
    });

    return $failed;
}

// ---------------------------------------------------------------- SafeLabel

test('SafeLabel aceita nomes e títulos reais em português', function (string $value) {
    expect(ruleFails(new SafeLabel, $value))->toBeFalse();
})->with([
    'João da Silva',
    "Maria D'Ávila Conceição-Souza",
    'Curso: Teologia (2026)!',
    'Dúvida sobre inscrição, valores e horários?',
    'Instituto & Seminário - com hífen simples: Instituto-Seminário',
]);

test('SafeLabel rejeita HTML, caracteres proibidos e protocolos perigosos', function (string $value) {
    expect(ruleFails(new SafeLabel, $value))->toBeTrue();
})->with([
    '<b>x</b>',
    '<script>alert(1)</script>',
    'a{b}',
    'a[b]',
    'x;y',
    'x|y',
    'x\\y',
    'crase`aqui',
    'cifrão$aqui',
    "byte\x00nulo",
    "zero\u{200B}width",
    "bidi\u{202E}reverso",
    'javascript:alert(1)',
    'data:text/html,x',
    'vbscript:msgbox',
]);

test('SafeLabel sem números aceita nomes de pessoa', function (string $value) {
    expect(ruleFails(new SafeLabel(allowNumbers: false), $value))->toBeFalse();
})->with([
    'João da Silva',
    "Maria D'Ávila Conceição-Souza",
]);

test('SafeLabel sem números rejeita dígitos', function (string $value) {
    expect(ruleFails(new SafeLabel(allowNumbers: false), $value))->toBeTrue();
})->with([
    'João 123',
    'Curso: Teologia (2026)!',
    'User42',
]);

// ----------------------------------------------------------------- SafeText

test('SafeText aceita texto natural multilinha em português', function () {
    $texto = "Bom dia!\n\nTenho interesse no curso de Teologia (turma 2026).\nPodem me enviar valores, horários etc.?\n\nObrigado.";

    expect(ruleFails(new SafeText, $texto))->toBeFalse();
});

test('SafeText não bloqueia palavras SQL como texto comum', function () {
    $texto = "Pesquisei sobre SELECT e UNION na aula de banco de dados; ' OR '1'='1 é um exemplo clássico.";

    expect(ruleFails(new SafeText, $texto))->toBeFalse();
});

test('SafeText rejeita HTML, controle e invisíveis', function (string $value) {
    expect(ruleFails(new SafeText, $value))->toBeTrue();
})->with([
    'texto com <script>alert(1)</script>',
    'texto com <div>bloco</div>',
    'texto com <!-- comentário -->',
    "texto com byte\x00nulo",
    "texto com controle\x08aqui",
    "texto com zero\u{200B}width",
]);

test('SafeText rejeita excesso de linhas', function () {
    expect(ruleFails(new SafeText(maxLines: 3), "a\nb\nc\nd"))->toBeTrue()
        ->and(ruleFails(new SafeText(maxLines: 3), "a\nb\nc"))->toBeFalse();
});

// ------------------------------------------------------------------ SafeUrl

test('SafeUrl aceita URLs seguras', function (string $value) {
    expect(ruleFails(new SafeUrl, $value))->toBeFalse();
})->with([
    'https://exemplo.com',
    'https://exemplo.com/pagina?x=1&y=2',
    'http://exemplo.com.br/caminho',
    '/interna',
    '/imersao/obrigado',
]);

test('SafeUrl rejeita protocolos e formatos perigosos', function (string $value) {
    expect(ruleFails(new SafeUrl, $value))->toBeTrue();
})->with([
    'javascript:alert(1)',
    'JAVASCRIPT:alert(1)',
    'data:text/html;base64,PHNjcmlwdD4=',
    'file:///etc/passwd',
    'vbscript:msgbox',
    'ftp://exemplo.com',
    '//evil.com',
    "java\u{200B}script:alert(1)",
    'https://exemplo.com/com espaço',
    '/interna:comdoispontos',
]);

test('SafeUrl pode exigir URL absoluta', function () {
    expect(ruleFails(new SafeUrl(allowRelative: false), '/interna'))->toBeTrue()
        ->and(ruleFails(new SafeUrl(allowRelative: false), 'https://exemplo.com'))->toBeFalse();
});

// ---------------------------------------------------------------- SafePhone

test('SafePhone aceita formatos comuns de telefone', function (string $value) {
    expect(ruleFails(new SafePhone, $value))->toBeFalse();
})->with([
    '+55 (11) 91234-5678',
    '(11) 3123-4567',
    '11912345678',
    '+551131234567',
]);

test('SafePhone rejeita letras, HTML e tamanhos inválidos', function (string $value) {
    expect(ruleFails(new SafePhone, $value))->toBeTrue();
})->with([
    'abc123',
    '<11> 91234-5678',
    '123',
    '12345678901234567890123',
    "1191234\u{200B}5678",
]);

// ---------------------------------------------------------------- SafeInput

test('SafeInput normaliza linha única removendo invisíveis e espaços repetidos', function () {
    expect(SafeInput::normalizeLine("  João\u{200B}  da\tSilva\u{FEFF} \n"))->toBe('João da Silva');
});

test('SafeInput remove byte nulo, controle e bidi', function () {
    expect(SafeInput::normalizeLine("a\x00b\x08c\u{202E}d"))->toBe('abcd');
});

test('SafeInput preserva quebras de linha legítimas em multilinha', function () {
    expect(SafeInput::normalizeMultiline("linha 1\r\nlinha 2\n\n\n\nlinha 3"))
        ->toBe("linha 1\nlinha 2\n\nlinha 3");
});

test('SafeInput normaliza email com trim e lowercase', function () {
    expect(SafeInput::normalizeEmail('  Joao.Silva@Example.COM '))->toBe('joao.silva@example.com');
});

test('SafeInput normaliza telefone para dígitos preservando DDI', function () {
    expect(SafeInput::normalizePhone('+55 (11) 91234-5678'))->toBe('+5511912345678')
        ->and(SafeInput::normalizePhone('(11) 3123-4567'))->toBe('1131234567');
});

test('SafeInput ignora valores não string', function () {
    expect(SafeInput::normalizeLine(null))->toBeNull()
        ->and(SafeInput::normalizeMultiline(123))->toBe(123);
});
