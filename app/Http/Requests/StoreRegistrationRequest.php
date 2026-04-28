<?php

namespace App\Http\Requests;

use App\Models\Workshop;
use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest {
    public function authorize(): bool {
        /** @var Workshop $workshop */
        $workshop = $this->route('workshop');
        return $workshop->available_seats > 0;
    }

    public function rules(): array {
        return [];
    }

    public function failedAuthorization() {
        abort(409, 'No available seats for this workshop.');
    }
}
