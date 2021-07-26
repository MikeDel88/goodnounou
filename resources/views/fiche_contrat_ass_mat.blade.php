@extends('layouts.back')
@section('content')
    <article class="box box-lg">
        <header class="box__header">
            <h4 class="box__header--titre">Fiche du contrat n°{{ $contrat->id }} - Début le {{ Carbon\Carbon::parse($contrat->date_debut)->translatedFormat('j F Y') }}</h4>
            <div>
                <a href="{{ route('contrats') }}" class="px-3"><i class="fas fa-arrow-left"></i></a>
            </div>
        </header>
        <div class="box__contenu">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <h3 class="h4">Identité du titulaire du contrat</h3>
                    <span>{{ $contrat->parent->categorie->getIdentite() }}</span>
                </li>
                <li class="list-group-item">
                    <h3 class="h4">Informations de l'enfant</h3>
                    <ul>
                        <li>Nom : {{ $contrat->enfant->nom }}</li>
                        <li>Prénom : {{ $contrat->enfant->prenom }}</li>
                        <li>Age : {{ $contrat->enfant->getAge() }}</li>
                    </ul>
                </li>
                <li class="list-group-item">
                    <h3 class="h4">Informations sur mes frais</h3>
                    <ul>
                        <li>Taux horaire : {{ $contrat->taux_horaire }} €</li>
                        <li>Taux d'entretien : {{ $contrat->taux_entretien }} €</li>

                        @if ($contrat->assistanteMaternelle->criteres->repas === 1)
                        <li>Frais de repas : {{ "$contrat->frais_repas €" ?? 'non renseigné' }}</li>
                        @endif

                    </ul>
                </li>
                <li class="list-group-item">
                    <h3 class="h4">Informations sur les caractéristiques de la garde</h3>
                    <ul>
                        <li>Nombre de semaine sur l'année : {{ $contrat->nombre_semaines }}s</li>
                        <li>Nombre d'heures par semaine : {{ $contrat->nombre_heures }}h</li>
                        <li>Nombre d'heures moyenne par mois : {{ $nombre_heures_mois }}h</li>
                        <li>Salaire mensuel indicatif (ne tient pas compte des frais d'entretien ni de repas) : {{ $salaire_mensuel }} €</li>
                    </ul>
                </li>
            </ul>

            @if ($contrat->status_id === 4)
            <span class="text-warning">Contrat clos le {{ $contrat->updated_at->translatedFormat('j F Y') }}</span>
            @endif

        </div>
    </article>
@endsection
