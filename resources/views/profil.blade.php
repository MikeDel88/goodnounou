@extends('layouts.back')

@section('content')
    <article class="box box-lg">
        <header>
            <h4>Mes informations personnelles</h4>
            <div class="close"><i class="fas fa-times"></i></div>
        </header>
        <div class="contenu">
            <div class="date-inscription">
                <span>Date inscription : {{ Auth::user()->created_at->translatedFormat('j F Y') }}</span>
            </div>
            <div class="informations">
                <ul>
                    <li><span>Nom</span><span>{{ Auth::user()->nom ?? 'non renseigné' }}</span></li>
                    <li><span>Prénom</span><span>{{ Auth::user()->prenom ?? 'non renseigné' }}</span></li>
                    <li><span>Date de
                            naissance</span><span>{{ date('d-m-Y', strtotime(Auth::user()->date_naissance)) ?? 'non renseigné' }}</span>
                    </li>
                    <li><span>Adresse</span><span>{{ Auth::user()->adresse ?? 'non renseigné' }}
                            {{ Auth::user()->code_postal }} {{ ucFirst(Auth::user()->ville) ?? '' }} </span></li>
                    <li><span>Téléphone</span><span>{{ Auth::user()->telephone ?? 'non renseigné' }}</span>
                    </li>
                    <li><span>Email</span><span>{{ Auth::user()->email_contact ?? 'non renseigné' }}</span>
                    </li>
                </ul>
                @if ($role === 'parents')
                    <div>
                        <span>Mes enfants:</span>
                        <ol>
                            @foreach ($enfants as $enfant)
                                <li>
                                    {{ $enfant->nom }}
                                    {{ $enfant->prenom }}
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif
            </div>
        </div>
        <footer>
            <a href="/users/{{ Auth::user()->id }}/edit" class="modifier">Modifier mes informations</a>
            <a href="#supprimer_compte" class="supprimer" data-bs-toggle="modal">Supprimer mon compte</a>
        </footer>
    </article>
    <div class="flex">
        <article class="box box-sm">
            <header>
                <h4>Mes contrats en cours</h4>
                <div class="close"><i class="fas fa-times"></i></div>
            </header>
            @if (!empty($contrats))
                <div class="contenu">
                    <ul class="m-3">
                        @foreach ($contrats as $contrat)
                            <li>
                                {{ "{$contrat->enfant->nom} {$contrat->enfant->prenom}" }}
                                depuis le
                                {{ Carbon\Carbon::parse($contrat->date_debut)->translatedFormat('j F Y') }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </article>
        <article class="box box-md">
            <header>
                <h4>Les derniers messages</h4>
                <div class="close"><i class="fas fa-times"></i></div>
            </header>
            @if (!empty($messages))
                <div id="messages" class="contenu">
                    <ul class="m-3">
                        @foreach ($messages as $message)
                            <li>
                                <span
                                    class="date">{{ Carbon\Carbon::parse($message->jour_garde)->translatedFormat('d/m/Y') }}</span>
                                <span class="enfant">{{ $message->enfant->prenom }}</span>
                                <span class="message">{{ Str::limit($message->contenu, 50) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </article>
    </div>

    <div class="modal fade show" id="supprimer_compte" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Suppression du compte</h5>
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
