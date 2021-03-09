@extends('layouts.back')

@section('content')
    <!-- Contenu des informations personnelles -->
    <article class="box box-lg">
        <header class="box__header">
            <h4 class="box__header--titre">Mes informations personnelles</h4>
            <div class="box__header--close"><i class="fas fa-times"></i></div>
        </header>
        <article class="box__contenu">
            <h5 class="box__contenu--inscription text-end">
                Date inscription : {{ Auth::user()->created_at->translatedFormat('j F Y') }}
            </h5>
            <div class="box__contenu--informations d-flex justify-content-start flex-wrap">
                <ul class="box__contenu--liste-identite">
                    <li class="item d-flex w-100 justify-content-start flex-wrap"><span class="fw-bold">Nom</span><span>{{ Auth::user()->nom ?? 'non renseigné' }}</span></li>
                    <li class="item d-flex w-100 justify-content-start flex-wrap"><span class="fw-bold">Prénom</span><span>{{ Auth::user()->prenom ?? 'non renseigné' }}</span></li>
                    <li class="item d-flex w-100 justify-content-start flex-wrap"><span class="fw-bold">Date de naissance</span><span>{{ date('d-m-Y', strtotime(Auth::user()->date_naissance)) ?? 'non renseigné' }}</span></li>
                    <li class="item d-flex w-100 justify-content-start flex-wrap"><span class="fw-bold">Adresse</span><span>{{ Auth::user()->adresse ?? 'non renseigné' }} {{ Auth::user()->code_postal }} {{ ucFirst(Auth::user()->ville) ?? '' }} </span></li>
                    <li class="item d-flex w-100 justify-content-start flex-wrap"><span class="fw-bold">Téléphone</span><span>{{ Auth::user()->telephone ?? 'non renseigné' }}</span></li>
                    <li class="item d-flex w-100 justify-content-start flex-wrap"><span class="fw-bold">Email</span><span>{{ Auth::user()->email_contact ?? 'non renseigné' }}</span></li>
                </ul>
                @if ($role === 'parents')
                <div class="box__contenu--liste-enfants">
                    <h5 class="fw-bold">Mes enfants:</h5>
                    <ol>
                        @foreach ($enfants as $enfant)
                        <li>{{ $enfant->nom }} {{ $enfant->prenom }}</li>
                        @endforeach
                    </ol>
                </div>
                @endif
            </div>
        </article>
        <footer class="box__footer d-flex justify-content-end">
            <a class="box__footer--lien box__footer--modifier" href="/users/{{ Auth::user()->id }}/edit">Modifier mes informations</a>
            <a class="box__footer--lien box__footer--supprimer" href="#supprimer_compte" data-bs-toggle="modal">Supprimer mon compte</a>
        </footer>
    </article>
    <section class="d-flex flex-wrap">
        <!-- Contenu de la liste des contrats en cours -->
        <article class="box box-sm align-self-start">
            <header class="box__header">
                <h4 class="box__header--titre">Mes contrats en cours</h4>
                <div class="box__header--close"><i class="fas fa-times"></i></div>
            </header>
            @if (!empty($contrats))
            <article class="box__contenu">
                <ul class="m-3">
                    @foreach ($contrats as $contrat)
                    <li>{{ "{$contrat->enfant->nom} {$contrat->enfant->prenom}" }} depuis le {{ Carbon\Carbon::parse($contrat->date_debut)->translatedFormat('j F Y') }}</li>
                    @endforeach
                </ul>
            </article>
            @endif
        </article>
        <!-- Contenu de la liste des derniers messages -->
        <article class="box box-md align-self-start">
            <header class="box__header">
                <h4 class="box__header--titre">Les derniers messages</h4>
                <div class="box__header--close"><i class="fas fa-times"></i></div>
            </header>
            @if (!empty($messages))
            <article id="messages" class="box__contenu">
                <ul class="box__contenu--liste-messages m-3">
                    @foreach ($messages as $message)
                        @if($message !== null)
                        <li class="items">
                            <span class="item-date">{{ Carbon\Carbon::parse($message->jour_garde)->translatedFormat('d/m/Y') }}</span>
                            <span class="item-enfant">{{ $message->enfant->prenom }}</span>
                            <span class="item-message">{{ Str::limit($message->contenu, 50) }}</span>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </article>
            @endif
        </article>
    </section>
    <!-- Fenêtre modal pour confirmation de la suppression du compte utilisateur -->
    <div class="modal fade show" id="supprimer_compte" tabindex="-1" aria-labelledby="modalSuppressionCompte" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSuppressionCompte">Suppression du compte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer votre compte ?</p>
                    <p>Cette action est irreversible...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non Merci</button>
                    <form id="delete-form" action="/users/{{ Auth::user()->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Confirmer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
