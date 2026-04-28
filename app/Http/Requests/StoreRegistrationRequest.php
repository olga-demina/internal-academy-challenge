<?php

namespace App\Http\Requests;

use App\Models\Registration;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest {
    private string $authorizationError = 'You cannot register for this workshop.';

    public function authorize(): bool {
        $response = Gate::inspect('create', [
            Registration::class,
            $this->route('workshop')
        ]);

        if ($response->denied()) {
            $this->authorizationError = $response->message() ??
                $this->authorizationError;
        }

        return $response->allowed();
    }

    public function rules(): array {
        return [];
    }

    public function failedAuthorization() {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            redirect()->back()->with('error', $this->authorizationError)
        );
    }
}
