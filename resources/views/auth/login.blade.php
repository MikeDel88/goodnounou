@extends('layouts.app')

@section('content')
    <section class="container justify-content-center">
        <h2 class="text-center">{{ __('Login') }}</h2>

        <div class="card-body">
            <form id="formulaire" method="POST" action="{{ route('login') }}" class="g-3 needs-validation" novalidate>
                @csrf

                <div class="row">
                    <div class="col-md-6 offset-md-3 my-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" aria-describedby="emailHelp"
                            value="{{ old('email') }}" @error('email') is-invalid @enderror" required
                            placeholder="email@exemple.com" autocomplete="off" name="email">
                        <div class="valid-feedback">
                            La saisie est correcte
                        </div>
                        @error('email')
                            <div class="feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 offset-md-3 my-3">
                        <label for="password1" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password1" aria-describedby="passwordHelp"
                            minlength="8" maxlength="16" required autocomplete="off" name="password"
                            aria-describedby="inputPassword" @error('password') .invalid @enderror">
                        <div class="valid-feedback">
                            Mot de passe correct
                        </div>
                        @error('password')
                            <div id="inputPassword" class="feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-md-6 offset-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Se souvenir de moi') }}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-12 text-center my-3">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Login') }}
                        </button>
                    </div>
                    <div class="col-12 text-center mt-1">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">
                                {{ __('Mot de passe oublié ?') }}
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
