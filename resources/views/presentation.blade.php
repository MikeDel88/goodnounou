@extends('layouts.back')
@section('content')
    <article class="box box-lg">
        <header>
            <h4>Renseignements</h4>
            <div>
                @if ($renseignements->categorie->disponible === 1)
                    <span class="text-success">Disponible</span>
                @else
                    <span class="text-danger">Disponible à partir du
                        :
                        {{ Carbon\Carbon::parse($renseignements->categorie->prochaine_disponibilite)->format('d/m/Y') ?? 'non renseigné' }}
                    </span>
                @endif
            </div>
        </header>
        <div class="contenu">
            <div class="favoris d-flex justify-content-end">
                <label class="form-check-label" for="flexSwitchCheckDefault"><i class="@if ($favoris===true) fas @else far @endif fa-heart text-danger "></i></label>
                                                                    <input data-nounou-id="
                        {{ $renseignements->categorie_id }}" data-parent-id="{{ Auth::user()->categorie->id }}"
                        type="hidden" id="flexSwitchCheckDefault" name="favoris">
            </div>
            <ul id="renseignements">
                <li><span class="fw-bold">Nom :</span> {{ $renseignements->nom ?? 'non renseigné' }}</li>
                <li><span class="fw-bold">Prénom :</span> {{ $renseignements->prenom ?? 'non renseigné' }}</li>
                <li><span class="fw-bold">Age :</span>
                    {{ Carbon\Carbon::parse($renseignements->date_naissance)->age ?? 'non renseigné' }}
                </li>
                <li><span class="fw-bold">Exerce depuis
                        :</span>
                    {{ Carbon\Carbon::parse($renseignements->categorie->date_debut)->format('d/m/Y') ?? 'non renseigné' }}
                </li>
                <li><span class="fw-bold">Formation :</span>
                    {{ $renseignements->categorie->formation ?? 'non renseigné' }}</li>
                <li><span class="fw-bold">Nombre d'enfants maximum :</span>
                    {{ $renseignements->categorie->nombre_place ?? 'non renseigné' }}
                </li>
                <li><span class="fw-bold">Adresse d'excercice :</span>
                    {{ "{$renseignements->categorie->adresse_pro} {$renseignements->categorie->code_postal_pro}, {$renseignements->categorie->ville_pro}" ?? 'non renseigné' }}
                <li><span class="fw-bold">Contacter :</span>
                    @if ($renseignements->telephone !== null)
                        <a href="tel:{{ $renseignements->telephone }}">
                            <i class="fas fa-phone-alt text-success"></i>
                        </a>
                    @else
                        <i class="fas fa-phone-slash text-danger"></i>
                    @endif
                    @if ($renseignements->email_contact !== null)
                        <a href="mailto:{{ $renseignements->email_contact ?? '#' }}">
                            <i class="fas fa-envelope text-success"></i>
                        </a>
                    @else
                        <i class="fas fa-envelope text-danger"></i>
                    @endif
                </li>
            </ul>
            <div id="presentation" class="my-3">
                <h5>Présentation :</h5>
                <p>{{ $renseignements->categorie->description ?? 'Aucune description' }}</p>
            </div>
            <footer>
                <span class="fw-bold">Inscrit depuis le :
                    {{ $renseignements->created_at->translatedFormat('j F Y') }}</span>
            </footer>
        </div>
    </article>
    <article class="box box-lg">
        <header>
            <h4>Ses critères</h4>
        </header>
        <div class="contenu">
            <ul>
                @foreach ($criteres as $critere => $valeur)
                    @if ($critere !== 'id' && $critere !== 'assistante_maternelle_id' && $critere !== 'created_at' && $critere !== 'updated_at')
                        @if ($valeur === 1)
                            <li>
                                <i
                                    class="fas fa-check-square text-success mx-3"></i><span>{{ ucFirst(strtr($critere, '_', ' ')) }}</span>
                            </li>
                        @else
                            <li>
                                <i
                                    class="fas fa-window-close text-danger mx-3"></i><span>{{ ucFirst(strtr($critere, '_', ' ')) }}</span>
                            </li>
                        @endif
                    @endif
                @endforeach
            </ul>
        </div>
    </article>
    <article class="box box-lg">
        <header>
            <h4>Ses tarifs</h4>
        </header>
        <div class="contenu">
            <ul>
                <li>
                    <span class="fw-bold">Taux horaire :</span>
                    {{ "{$renseignements->categorie->taux_horaire} €" ?? 'non renseigné' }}
                </li>
                <li>
                    <span class="fw-bold">Frais d'entretien :</span>
                    {{ "{$renseignements->categorie->taux_entretien} €" ?? 'non renseigné' }}
                </li>
                <li>
                    <span class="fw-bold">Frais repas :</span>
                    {{ "{$renseignements->categorie->frais_repas} €" ?? 'non renseigné' }}
                </li>
            </ul>
        </div>
    </article>
@endsection
