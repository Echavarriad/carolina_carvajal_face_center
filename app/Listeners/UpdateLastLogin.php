<?php

namespace App\Listeners;

use App\Models\User;
use Carbon\Carbon;

class UpdateLastLogin {

    public function handle($user) {
        $user->user->last_login = Carbon::now();
        $user->user->last_ip = request()->ip();
        $user->user->save();
    }
}