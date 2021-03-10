@extends('layouts.back')
@section('content')
    {{-- Renseignements générales sur l'assistante maternelle avec ses disponibilités, nombre d'avis, notes et moyenne de note --}}
    <article class="box box-lg">
        <header class="box__header">
            <h4 class="box__header--titre">Renseignements
                @if($moyenne !== null)
                    @for ($i = 1; $i <= $noteMax; $i++)
                        <i class="fs-7 text-warning @if($i <= $moyenne) fas @else far @endif fa-star"></i>
                    @endfor
                @endif
                    <span class="fs-7">({{$nombreNote}} notes et {{$nombreAvis}} avis)</span>
            </h4>
            <div>
                @if ($renseignements->categorie->disponible === 1)
                    <span class="text-success mx-1">Disponible</span>
                @else
                    <span class="text-danger mx-1">Disponible à partir du :
                        @if ($renseignements->categorie->prochaine_disponibilite === null)
                            non communiqué
                        @else
                            {{ Carbon\Carbon::parse($renseignements->categorie->prochaine_disponibilite)->format('d/m/Y') }}
                        @endif
                    </span>
                @endif
            </div>
        </header>
        <div class="box__contenu">
            <div class="favoris d-flex justify-content-end">
                <label class="form-check-label" for="favoris">
                    <i alt="ajouter ou retirer des favoris" class="@if ($favoris===true) fas @else far @endif fa-heart text-danger "></i>
                </label>
                <input data-nounou-id="{{ $renseignements->categorie_id }}" data-parent-id="{{ Auth::user()->categorie->id }}" type="hidden" id="favoris" name="favoris" />
            </div>
            <ul id="renseignements">
                <li><span class="fw-bold">Nom :</span> {{ $renseignements->nom ?? 'non renseigné' }}</li>
                <li><span class="fw-bold">Prénom :</span> {{ $renseignements->prenom ?? 'non renseigné' }}</li>
                <li><span class="fw-bold">Age :</span> {{ Carbon\Carbon::parse($renseignements->date_naissance)->age ?? 'non renseigné' }}</li>
                <li><span class="fw-bold">Exerce depuis :</span> {{ Carbon\Carbon::parse($renseignements->categorie->date_debut)->format('d/m/Y') ?? 'non renseigné' }}</li>
                <li><span class="fw-bold">Formation :</span> {{ $renseignements->categorie->formation ?? 'non renseigné' }}</li>
                <li><span class="fw-bold">Nombre d'enfants maximum :</span> {{ $renseignements->categorie->nombre_place ?? 'non renseigné' }}</li>
                <li><span class="fw-bold">Adresse d'excercice :</span> {{ "{$renseignements->categorie->adresse_pro} {$renseignements->categorie->code_postal_pro}, {$renseignements->categorie->ville_pro}" ?? 'non renseigné' }}
                <li><span class="fw-bold">Contacter :</span>
                    @if ($renseignements->telephone !== null)
                        <a href="tel:{{ $renseignements->telephone }}" class="mx-2">
                            <i alt="contact par téléphone possible" title="{{ $renseignements->telephone }}" class="fas fa-phone-alt text-success"></i>
                        </a>
                    @else
                        <i alt="contact par téléphone impossible" class="fas fa-phone-slash text-danger"></i>
                    @endif
                    @if ($renseignements->email_contact !== null)
                        <a href="mailto:{{ $renseignements->email_contact ?? '#' }}">
                            <i alt="contact par email possible" title="{{$renseignements->email_contact}}" class="fas fa-envelope text-success"></i>
                        </a>
                    @else
                        <i alt="contact par email impossible" class="fas fa-envelope text-danger"></i>
                    @endif
                </li>
            </ul>
            <div id="presentation" class="my-3 box__contenu--presentation">
                <h5 class="box__contenu--presentation-titre">Présentation :</h5>
                <p class="box__contenu--presentation-message">{{ $renseignements->categorie->description ?? 'Aucune description' }}</p>
            </div>
            <footer class="d-flex flex-wrap justify-content-between border-top py-3">
                <div>
                    <span>
                        Note :
                        @for ($i = 1; $i <= $noteMax; $i++)
                            <i id="note{{ $i }}" alt="note {{$i}} / {{$noteMax}}" data-note="{{ $i }}" class="@if (isset($recommandation) && $i <=$recommandation->note) fas note-check @endif far fa-star note"></i>
                        @endfor
                    </span>
                    <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#modalAvis">
                        @if (isset($recommandation) && $recommandation->avis !== null)
                            Voir mon avis
                        @else
                            Laissez un avis
                        @endif
                    </a>
                </div>
                <p class="fw-bold">Inscrit depuis le : {{ $renseignements->created_at->translatedFormat('j F Y') }}</p>
            </footer>
        </div>
    </article>
{{-- Tous les critères que l'assistantes maternelle accepte ou non --}}
    <article class="box box-lg">
        <header class="box__header">
            <h4 class="box__header--titre">Ses critères</h4>
        </header>
        <div class="box__contenu">
            <ul>
                @foreach ($criteres as $critere => $valeur)
                    @if ($critere !== 'id' && $critere !== 'assistante_maternelle_id' && $critere !== 'created_at' && $critere !== 'updated_at')
                        @if ($valeur === 1)
                            <li>
                                <i alt="critère accepté" class="fas fa-check-square text-success mx-3"></i><span>{{ ucFirst(strtr($critere, '_', ' ')) }}</span>
                            </li>
                        @else
                            <li>
                                <i alt="critère refusé" class="fas fa-window-close text-danger mx-3"></i><span>{{ ucFirst(strtr($critere, '_', ' ')) }}</span>
                            </li>
                        @endif
                    @endif
                @endforeach
            </ul>
        </div>
    </article>
{{-- L'ensemble de ses tarifs --}}
    <article class="box box-lg">
        <header class="box__header">
            <h4 class="box__header--titre">Ses tarifs</h4>
        </header>
        <div class="box__contenu">
            <ul>
                <li>
                    <span class="fw-bold">Taux horaire :</span> {{ "{$renseignements->categorie->taux_horaire} €" }}
                </li>
                <li>
                    <span class="fw-bold">Frais d'entretien :</span> {{ "{$renseignements->categorie->taux_entretien} €" }}
                </li>
                <li>
                    <span class="fw-bold">Frais repas :</span> {{ "{$renseignements->categorie->frais_repas} €" }}
                </li>
            </ul>
        </div>
    </article>
{{-- Modal pouur enregistrer ou supprimer un avis --}}
    <div class="modal fade" id="modalAvis" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('parent.ajout_avis')}}" class="modal-content">
                @csrf
                @if(isset($recommandation) && $recommandation->avis !== null)
                    @method('DELETE')
                @else
                    @method('POST')
                @endif
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Avis pour {{$renseignements->nom}} {{$renseignements->prenom}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body form-floating p-2">
                    <input type="hidden" name="parent" value="{{Auth::user()->categorie->id}}">
                    <input type="hidden" name="assistante-maternelle" value="{{$renseignements->categorie->id}}">
                    <textarea class="form-control" name="avis" placeholder="Avis" id="avis" style="height:200px">@if(isset($recommandation) && $recommandation->avis !== null) {{$recommandation->avis}} @else {{old('avis')}}@endif</textarea>
                    <label for="avis">Avis sur l'assistante maternelle</label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">@if(isset($recommandation) && $recommandation->avis !== null) Supprimer avis et note @else Valider @endif</button>
                </div>
            </form>
        </div>
    </div>
{{-- Contenu des avis sur l'assistante maternelle --}}
    <article id="avis" class="box box-lg">
        <header class="box__header">
            <h4 class="box__header--titre">Tous les avis</h4>
        </header>
        <div class="box__contenu">
            <div class="d-flex justify-content-center m-3 p-3">
                <div class="spinner-border" role="status"></div>
            </div>
            <form class="d-flex flex-wrap align-items-center my-3 border-bottom pb-3">
                <label class="mx-2" for="filtre">Filtre : </label>
                <select name="filtre" id="filtre" class=" w-75 form-select form-select-sm" aria-label="Filtre de selection" value="{{old('filtre')}}">
                    <option value="aucun" selected disabled>Selectionnez</option>
                    <option value="note_desc">Note : + vers -</option>
                    <option value="note_asc">Note :  - vers + </option>
                    <option value="avis_desc">Avis : Récents vers anciens</option>
                    <option value="avis_asc">Avis : Anciens vers récents</option>
                </select>
            </form>
            <div id='liste_avis' data-nounou-id="{{ $renseignements->categorie_id }}" class="visually-hidden">
                <div id="messages_avis">
                </div>
                <div id="pagination_avis" class="text-center">
                </div>
            </div>
        </div>
    </article>
{{-- Toast pour vérifier ajout ou suppression favoris --}}
<section class="toast align-items-center text-white position-absolute border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
        <div class="toast-body"></div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
</section>
@endsection
