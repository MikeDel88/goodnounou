@extends('layouts.back')

@section('content')
    <!-- Informations sur le profil de connexion utilisateur -->
    <article class="box box-lg">
        <header class="box__header">
            <h4 class="box__header--titre">Mes informations de connexion</h4>
        </header>
        <div class="box__contenu">
            <ul>
                <li><span>Dernière mise à jour du profil : </span><span>{{ Auth::user()->updated_at->translatedFormat('j F Y à H:i') }}</span></li>
                <li><span>Email de connexion : </span><span>{{ Auth::user()->email }}</span></li>
                <li><span>Ma catégorie : </span><span>{{ ucFirst($role) }}</span></li>
            </ul>
        </div>
    </article>
    <!-- Modification du profil utilisateur -->
    <article class="box box-lg">
        <header class="box__header">
            <h4 class="box__header--titre">Mon profil</h4>
        </header>
        <div class="box__contenu">
            <form action="/users/{{ Auth::user()->id }}" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="mb-3 col-md-3">
                    <label for="formFile" class="form-label">Choisir une photo de profil</label>
                    <input class="form-control" type="file" id="formFile" name="photo">
                </div>
                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" value="{{ Auth::user()->nom ?? old('nom') }}" name="nom" required>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" value="{{ Auth::user()->prenom ?? old('prenom') }}" name="prenom" required>
                    </div>
                </div>
                <div class="mb-3 col-md-4">
                    <label for="date_naissance" class="form-label">Date de naissance</label>
                    <input type="date" class="form-control" id="date_naissance" value="{{ Auth::user()->date_naissance ?? old('date_naissance') }}" min="1950-01-01" max="{{ date('Y-m-d') }}" name="date_naissance" required>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="adresse" class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="adresse" value="{{ Auth::user()->adresse ?? old('adresse') }}" name="adresse" required>
                    </div>
                    <div class="mb-3 col-md-2">
                        <label for="code_postal" class="form-label">Code Postal</label>
                        <input type="text" class="form-control" id="code_postal" value="{{ Auth::user()->code_postal ?? old('code_postal') }}" name="code_postal" required>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="ville" class="form-label">Ville</label>
                        <input type="text" class="form-control" id="ville" value="{{ Auth::user()->ville ?? old('ville') }}" name="ville" required>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="phone" class="form-control" id="telephone" value="{{ Auth::user()->telephone ?? old('telephone') }}" name="telephone">
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="email_contact" class="form-label">Email de contact</label>
                        <input type="email" class="form-control" id="email_contact" value="{{ Auth::user()->email_contact ?? old('email_contact') }}" name="email_contact">
                    </div>
                </div>
                <div class="row px-2 d-flex d-flex justify-content-end">
                    <button class="col-md-4" type="submit">Enregistrer les modifications</button>
                </div>
            </form>
            @if ($errors->any())
                <div class="mt-3 alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </article>
@endsection
