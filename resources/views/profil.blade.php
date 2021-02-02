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
                    <li><span>Téléphone</span><span>{{ wordwrap(Auth::user()->telephone, 2, '.', 1) ?? 'non renseigné' }}</span>
                    </li>
                    <li><span>Email</span><span>{{ Auth::user()->email_contact ?? 'non renseigné' }}</span>
                    </li>
                </ul>
                @if ($role === 'parents')
                    <div>
                        Mes enfants:
                    </div>
                @endif
            </div>
        </div>
        <footer>
            <a href="/users/{{ Auth::user()->id }}/edit" class="modifier">Modifier mes informations</a>
            <a href="#" class="supprimer">Supprimer mon compte</a>
        </footer>
    </article>
    <div class="flex">
        <article class="box box-sm">
            <header>
                <h4>Mes contrats en cours</h4>
                <div class="close"><i class="fas fa-times"></i></div>
            </header>
        </article>
        <article class="box box-md">
            <header>
                <h4>Les derniers messages</h4>
                <div class="close"><i class="fas fa-times"></i></div>
            </header>
        </article>
    </div>

@endsection
