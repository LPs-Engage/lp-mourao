<?php

namespace App\Http\Requests\Site;

use App\Rules\SafeLabel;
use App\Rules\SafePhone;
use App\Rules\SafeUrl;
use App\Support\SafeInput;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class HomeLeadFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => SafeInput::normalizeLine($this->input('name')),
            'phone' => SafeInput::normalizePhone($this->input('phone')),
            'city' => SafeInput::normalizeLine($this->input('city')),
            'neighborhood' => SafeInput::normalizeLine($this->input('neighborhood')),
            'page_url' => $this->nullableLine($this->input('page_url')),
            'utm_source' => $this->nullableLine($this->input('utm_source')),
            'utm_medium' => $this->nullableLine($this->input('utm_medium')),
            'utm_campaign' => $this->nullableLine($this->input('utm_campaign')),
            'utm_term' => $this->nullableLine($this->input('utm_term')),
            'utm_content' => $this->nullableLine($this->input('utm_content')),
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
            'city' => ['required', 'string', 'min:2', 'max:80', new SafeLabel],
            'neighborhood' => ['required', 'string', 'min:2', 'max:80', new SafeLabel],
            'lgpd' => ['accepted'],
            'page_url' => ['nullable', 'string', 'max:500', new SafeUrl(allowRelative: false)],
            'utm_source' => ['nullable', 'string', 'max:120'],
            'utm_medium' => ['nullable', 'string', 'max:120'],
            'utm_campaign' => ['nullable', 'string', 'max:120'],
            'utm_term' => ['nullable', 'string', 'max:120'],
            'utm_content' => ['nullable', 'string', 'max:120'],
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
            'name.min' => 'O nome deve ter pelo menos :min caracteres.',
            'phone.required' => 'Informe seu WhatsApp.',
            'city.required' => 'Informe sua cidade.',
            'neighborhood.required' => 'Informe seu bairro.',
            'lgpd.accepted' => 'É necessário concordar em receber comunicações.',
            'website.prohibited' => 'Não foi possível enviar o formulário.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'phone' => 'WhatsApp',
            'city' => 'cidade',
            'neighborhood' => 'bairro',
            'lgpd' => 'comunicações',
        ];
    }

    private function nullableLine(mixed $value): mixed
    {
        $normalized = SafeInput::normalizeLine($value);

        return $normalized === '' ? null : $normalized;
    }
}
