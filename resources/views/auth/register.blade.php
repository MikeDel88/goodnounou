@extends('layouts.app')
@section('content')
    <section id="en-tete" class="container-fluid">
        <div class="row d-flex text-center align-items-center">
            <div class="col-12">
                <h2 class="mb-3 text-center">Inscription</h2>
                <h3 class="my-3">Selectionnez une catégorie</h3>
            </div>
            <div class="col-6 order-1 position-relative">
                <img src="{{ URL::asset('assets/images/parents.png') }}" loading="lazy" alt="choix parents" title="parents" data-name="parents">
                <div class="position-absolute">Parent</div>
            </div>
            <div class="col-6 order-1 position-relative">
                <img src="{{ URL::asset('assets/images/assistante-maternelle.png') }}" loading="lazy" alt="choix assistante-maternelle" title="assistante-maternelle" data-name="assistante-maternelle">
                <div class="position-absolute">Nounou</div>
            </div>
            @error('categorie')
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @enderror
        </div>
    </section>
    <section id="formulaire" class="container-fluid">
        <form action="{{ url('register') }}" method="POST" class="g-3 needs-validation" novalidate oninput='password_confirmation.setCustomValidity(password_confirmation.value != password.value ? "Passwords do not match." : "")'>
            @csrf
            <input class="categorie" type="hidden" name="categorie" required>
            <div class="row">
                <div class="col-md-6 offset-md-3 my-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" aria-describedby="emailHelp" value="{{ old('email') }}" @error('email') is-invalid @enderror" required placeholder="email@exemple.com" autocomplete="off" name="email">
                    <div id="emailHelp" class="form-text">Une adresse mail valide pour l'envoi de la confirmation</div>
                    <div class="valid-feedback">La saisie est correcte</div>

                    @error('email')
                    <div class="feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 offset-md-3 my-3">
                    <label for="password1" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password1" aria-describedby="passwordHelp" minlength="8" maxlength="16" required autocomplete="off" name="password" aria-describedby="inputPassword" @error('password') .invalid @enderror">
                    <div id="passwordHelp" class="form-text">Mot de passe doit être compris entre 8 et 16 caractères.</div>
                    <div class="valid-feedback">Mot de passe correct</div>

                    @error('password')
                    <div id="inputPassword" class="feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 offset-md-3 my-3">
                    <label for="password2" class="form-label">Confirmation du Mot de passe</label>
                    <input type="password" class="form-control" id="password2" aria-describedby="passwordConfirmHelp" minlength="8" maxlength="16" required autocomplete="off" name="password_confirmation" @error('password_confirmation') is-invalid @enderror required>
                    <div id="passwordConfirmHelp" class="form-text">Renseignez le même mot de passe</div>
                    @error('password_confirmation')
                        <div class="feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="form-check my-3 d-flex justify-content-center flex-wrap">
                    <input class="form-check-input" type="checkbox" id="Check" required name="acceptCG" aria-describedby="inputAcceptCG" @error('acceptCG') is-invalid @enderror>
                    <label class="form-check-label" for="Check">
                        <a href="#modalConditionsGenerales" data-bs-toggle="modal">J'ai lu et j'accepte les conditions générales</a>
                    </label>
                    <div id="inputAcceptCG" class="invalid-feedback text-center">Vous devez accepter les conditions générales afin de pouvoir valider votre inscription</div>
                </div>
            </div>
            @error('acceptCG')
                <div class="row text-center my-3">
                    <div class="text-danger">{{ $message }}</div>
                </div>
            @enderror
            <div class="row">
                <div class="col-12 text-center">
                    <button class="btn btn-primary" type="submit">Valider</button>
                </div>
            </div>

        </form>
    </section>
    <!-- Modal pour les conditions générales -->
    <div class="modal fade show" id="modalConditionsGenerales" tabindex="-1" aria-labelledby="exampleModalScrollableTitle" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Conditions générales</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('conditions-generales')
                </div>
            </div>
        </div>
    </div>
@endsection
