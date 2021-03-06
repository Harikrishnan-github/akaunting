<?php

namespace App\Listeners\Auth;

use Date;
use Illuminate\Auth\Events\Login as ILogin;

class Login
{

    /**
     * Handle the event.
     *
     * @param ILogin $event
     * @return void
     */
    public function handle(ILogin $event)
    {
        // Get first company
        $company = $event->user->companies()->first();
        
        // Logout if no company assigned
        if (!$company) {
            auth()->logout();
            
            flash(trans('auth.error.no_company'))->error();
            
            return redirect('auth/login');
        }

        // Set company id
        session(['company_id' => $company->id]);

        // Save user login time
        $event->user->last_logged_in_at = Date::now();

        $event->user->save();
    }
}
