<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Category as Category;

class FortifyServiceProvider extends ServiceProvider
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
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::registerView(function () {

            $data['categories'] = Category::all();
            
            $data['metadescription'] = "Inscription au site de GoodNounou en tant qu'assistante maternelle ou parents afin de se mettre en relation et profiter de l'ensemble des fonctionnalités !";
            $data['title'] = 'inscription';
            $data['boostrap'] = '';
            $data['css'][] = 'layout';
            $data['css'][] = 'inscription';
            $data['js'] = ['app-mobile', 'app', 'app-form-bs'];
            return view('auth.register', $data);
        });

        Fortify::loginView(function () {
            $data['metadescription'] = "Connectez-vous à votre espace personnalisé pour gérer votre profil sur le site GoodNounou !";
            $data['title'] = 'connexion';
            $data['boostrap'] = '';
            $data['css'][] = 'layout';
            $data['css'][] = 'connexion';
            $data['js'] = ['app-mobile', 'app', 'app-form-bs'];
            return view('auth.login', $data);
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.passwords.email');
        });

        Fortify::resetPasswordView(function ($request) {
            return view('auth.passwords.reset', ['token' => $request->token]);
        });

        Fortify::verifyEmailView(function () {
            $data['title'] = 'confirmation';
            $data['css'][] = 'layout';
            $data['js'] = ['app-mobile', 'app', 'app-form-bs'];
            return view('auth.verify', $data);
        });
    }
}
