@extends('layouts.back')
@section('content')
    <article class="box box-lg">
        <header>
            <h4>
                Fiche du contrat n°{{ $contrat->id }} - Début le
                {{ Carbon\Carbon::parse($contrat->date_debut)->translatedFormat('j F Y') }}
            </h4>
            <div>
                <a href="{{ route('contrats') }}" class="px-3"><i class="fas fa-arrow-left"></i></a>
            </div>
        </header>
        <div class="contenu">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <h6>Identité du titulaire du contrat</h6>
                    <span>{{ "{$contrat->parent->categorie->nom} {$contrat->parent->categorie->prenom}" }}</span>
                </li>
                <li class="list-group-item">
                    <h6>Informations de l'enfant</h6>
                    <ul>
                        <li>Nom : {{ $contrat->enfant->nom }}</li>
                        <li>Prénom : {{ $contrat->enfant->prenom }}</li>
                        <li>Age : {{ Carbon\Carbon::parse($contrat->enfant->date_naissance)->age }}</li>
                    </ul>
                </li>
                <li class="list-group-item">
                    <h6>Informations sur mes frais</h6>
                    <ul>
                        <li>Taux horaire : {{ $contrat->taux_horaire }} €</li>
                        <li>Taux d'entretien : {{ $contrat->taux_entretien }} €</li>
                        @if ($contrat->assistanteMaternelle->criteres->repas === 1)
                            <li>Frais de repas : {{ "$contrat->frais_repas €" ?? 'non renseigné' }}</li>
                        @endif
                    </ul>
                </li>
                <li class="list-group-item">
                    <h6>Informations sur les caractéristiques de la garde</h6>
                    <ul>
                        <li>Nombre de semaine sur l'année : {{ $contrat->nombre_semaines }}s</li>
                        <li>Nombre d'heures par semaine : {{ $contrat->nombre_heures }}h</li>
                        <li>Nombre d'heures moyenne par mois : {{ $nombre_heures_mois }}h</li>
                        <li>Salaire mensuel indicatif (ne tient pas compte des frais d'entretien ni de repas) :
                            {{ $salaire_mensuel }} €</li>
                    </ul>
                </li>
            </ul>
        </div>
    </article>
@endsection
