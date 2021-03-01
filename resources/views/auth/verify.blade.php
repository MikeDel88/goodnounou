@extends('layouts.app')
@section('content')
    <div class="container-fluid text-center my-3 py-3">
        <h4 class="alert-heading">{{ __('Félicitations !!') }}</h4>
        <div>
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif
            <p>{{ __('Vous allez recevoir un email de confirmation pour accéder à votre profil sur :') }} <span>{{ Auth::user()->email }}</span></p>
            <hr>
            {{ __("Si vous n'avez pas reçu l'email") }}
            <form class="d-inline mb-0" method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn p-0 m-0 align-baseline">{{ __('Cliquez ici pour en recevoir un nouveau') }}</button>.
            </form>
        </div>
    </div>
@endsection
