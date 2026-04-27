<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ModifyWorkshopRequest extends FormRequest {
    public function authorize(): bool {
        return $this->user()->can('update', $this->route('workshop'));
    }

    protected function prepareForValidation(): void {
        if ($this->starts_at) {
            $this->merge([
                'starts_at' => \Carbon\Carbon::parse($this->starts_at)->utc(),
            ]);
        }

        if ($this->ends_at) {
            $this->merge([
                'ends_at' => \Carbon\Carbon::parse($this->ends_at)->utc(),
            ]);
        }
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'capacity' => ['required', 'integer', 'min:1'],
        ];
    }
}
