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
            return Limit::perMinute(5)->by($request->email . $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::registerView(function () {
            $data['metadescription'] = "Inscription au site de GoodNounou en tant qu'assistante maternelle ou parents afin de se mettre en relation et profiter de l'ensemble des fonctionnalités !";
            $data['title'] = 'inscription';
            $data['bootstrap'] = '';
            $data['css'] = ['layout', 'inscription'];
            $data['js'] = ['app-mobile', 'app', 'app-form-bs'];
            return view('auth.register', $data);
        });

        Fortify::loginView(function () {
            $data['metadescription'] = "Connectez-vous à votre espace personnalisé pour gérer votre profil sur le site GoodNounou !";
            $data['title'] = 'connexion';
            $data['bootstrap'] = '';
            $data['css'] = ['layout', 'connexion'];
            $data['js'] = ['app-mobile', 'app', 'app-form-bs'];
            return view('auth.login', $data);
        });

        Fortify::requestPasswordResetLinkView(function () {
            $data['title'] = 'Envoi nouveau mot de passe';
            $data['bootstrap'] = '';
            $data['css'] = ['layout', 'reset-password'];
            $data['js'] = ['app-mobile', 'app'];
            return view('auth.passwords.email', $data);
        });

        Fortify::resetPasswordView(function ($request) {
            $data['title'] = 'Réinitialisation mot de passe';
            $data['bootstrap'] = '';
            $data['css'] = ['layout', 'reset-password'];
            $data['js'] = ['app-mobile', 'app'];
            $data['token'] = $request->token;
            return view('auth.passwords.reset', $data);
        });

        Fortify::verifyEmailView(function () {
            $data['title'] = 'confirmation';
            $data['css'] = ['layout', 'verify-email'];
            $data['bootstrap'] = '';
            $data['js'] = ['app-mobile', 'app'];
            return view('auth.verify', $data);
        });
    }
}
