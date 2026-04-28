<?php

namespace App\Http\Requests;

use App\Enums\RegistrationStatus;
use Illuminate\Foundation\Http\FormRequest;

class DeleteRegistrationRequest extends FormRequest {
    public function authorize(): bool {
        $registration = $this->route('workshop')
            ->registrations()
            ->where('user_id', $this->user()->id)
            ->whereIn('status', [RegistrationStatus::Confirmed, RegistrationStatus::Waiting])
            ->first();

        return $registration && $this->user()->can('delete', $registration);
    }

    public function rules(): array {
        return [];
    }

    public function failedAuthorization() {
        abort(409, 'No active registration found for this workshop.');
    }
}
