@extends('layouts.back')
@section('content')
    <article class="box box-lg">
        <header>
            <h4>Informations générales</h4>
        </header>
        <div class="contenu">
            <ul>
                <li>Nombre de notes : @if ($nombreNote > 0) {{ $nombreNote }} @else aucune @endif</li>
                <li>Nombre d'avis : @if ($nombreAvis > 0) {{ $nombreAvis }} @else aucun @endif</li>
                <li>Ma note moyenne :
                    @if($moyenne !== null)
                        @for ($i = 1; $i <= $noteMax; $i++)
                            <i class="fs-6 text-warning @if($i <= $moyenne) fas @else far @endif fa-star"></i>
                        @endfor
                    @endif
                </li>
            </ul>
        </div>
    </article>
    <article class="box box-lg">
        <header>
            <h4>Liste des avis</h4>
        </header>
        <div class="contenu">
            <form class="d-flex flex-wrap align-items-center">
                <label class="mx-2" for="filtre">Filtre : </label>
                <select name="filtre" id="filtre" class=" w-75 form-select form-select-sm" aria-label="Filtre de selection">
                    <option value="#" selected disabled>Selectionnez</option>
                    <option value="note_desc">Note : + vers -</option>
                    <option value="note_asc">Note :  - vers + </option>
                    <option value="avis_desc">Avis : Récents vers anciens</option>
                    <option value="avis_asc">Avis : Anciens vers récents</option>
                </select>
            </form>
            <ul>
                @foreach ($listeAvis as $avis)
                    <li class="p-2 border border-dark my-2 bg-light">
                        <p class="mb-1">Le : <span class="fw-bold">{{ \Carbon\Carbon::parse($avis->updated_at)->format('d/m/Y') }}</span>
                        </p>
                        <p class="mb-1">par : <span class="fw-bold">
                                @if ($avis->nom !== null && $avis->prenom !== null)
                                {{ $avis->nom }} {{ $avis->prenom }} @else Anonyme @endif
                            </span></p>
                        <p>Note : @if ($avis->note !== null) {{ $avis->note }} /
                            {{ $noteMax }} @else Aucune note @endif
                        </p>
                        <p>{{ $avis->avis }}</p>
                    </li>
                @endforeach
            </ul>
            <div class="d-flex justify-content-center my-3">
                {{ $listeAvis->links() }}
            </div>
        </div>
    </article>
    <script>
        const FILTRE = document.querySelector('#filtre');
        FILTRE.addEventListener('change', function(e){
            e.preventDefault();
            let value = encodeURI(this.value);
            this.parentNode.setAttribute('action', `/recommandations?filtre=${this.value}`);
            this.parentNode.submit();
        })
    </script>
@endsection
