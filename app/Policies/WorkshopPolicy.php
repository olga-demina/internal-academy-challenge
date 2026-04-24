<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workshop;

class WorkshopPolicy {

    public function viewAny(User $user): bool {
        return true;
    }

    public function view(User $user, Workshop $workshop): bool {
        return true;
    }

    public function create(User $user): bool {
        return $user->role === 'admin';
    }

    public function update(User $user, Workshop $workshop): bool {
        return $user->role === 'admin' && $user->id === $workshop->user_id;
    }

    public function delete(User $user, Workshop $workshop): bool {
        return $user->role === 'admin' && $user->id === $workshop->user_id;
    }
}
