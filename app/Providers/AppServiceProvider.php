<?php

namespace App\Providers;

use App\Mail\forgotPasswordMail;
use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use App\Models\Products;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // send verification email
        User::created(function ($user) {
            retry(5, function () use ($user) {
                Mail::to($user)->send(new UserCreated($user));

            });

        });

        // User email change verification
        User::updated(function ($user) {
            // retry five time after ten seconds
            if ($user->isDirty('email')) {
                retry(5, function () use ($user) {
                    Mail::to($user)->send(new UserMailChanged($user));
                }, 100);
            }
        });

        // Check if product quantity is 0 and update status
        Products::updated(function ($products) {
            if ($products->quantity == 0 && $products->isAvailable()) {
                $products->status = Products::UNAVAILABLE_PRODUCT;
                $products->save();
            }
        });

        User::code(function ($user) {
            retry(5, function () use ($user) {
                Mail::to($user)->send(new forgotPasswordMail($user));
            }, 100);

        });

    }
}
