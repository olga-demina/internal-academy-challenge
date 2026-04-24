<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class RoleRedirectAsIntended implements Responsable
{
    public function __construct(public string $name)
    {
    }

    public function toResponse($request)
    {
        return redirect()->intended($request->user()->dashboardRoute());
    }
}
