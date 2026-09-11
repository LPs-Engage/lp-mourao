<?php

namespace App\Http\Requests\LandingPage;

use App\Rules\SafeLabel;
use App\Rules\SafePhone;
use App\Rules\SafeUrl;
use App\Support\SafeInput;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LandingPageLeadFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $pageUrl = SafeInput::normalizeLine($this->input('page_url'));

        $this->merge([
            'name' => SafeInput::normalizeLine($this->input('name')),
            'phone' => SafeInput::normalizePhone($this->input('phone')),
            'page_url' => $pageUrl === '' ? null : $pageUrl,
        ]);
    }

    /**
     * @return array<string, array<int, ValidationRule|string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:120', new SafeLabel(allowNumbers: false)],
            'phone' => ['required', 'string', 'max:20', new SafePhone],
            'lgpd' => ['accepted'],
            'page_url' => ['nullable', 'string', 'max:500', new SafeUrl(allowRelative: false)],
            'website' => ['nullable', 'prohibited'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Informe seu nome.',
            'phone.required' => 'Informe seu WhatsApp.',
            'lgpd.accepted' => 'É necessário concordar em receber comunicações.',
            'website.prohibited' => 'Não foi possível enviar o formulário.',
        ];
    }
}
