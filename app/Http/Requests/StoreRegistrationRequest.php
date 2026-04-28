<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest {
    public function authorize(): bool {
        return $this->user()->can('create', $this->route('workshop'));
    }

    public function rules(): array {
        return [];
    }

    public function failedAuthorization() {
        abort(409, 'You cannot register for this workshop.');
    }
}
