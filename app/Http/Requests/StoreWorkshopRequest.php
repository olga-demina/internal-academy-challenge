<?php

namespace App\Http\Requests;

use App\Models\Workshop;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreWorkshopRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return $this->user()->can('create', Workshop::class);
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
     * Get the validation rules that apply to the request.
     *
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
