@extends('layouts.back')
@section('content')
    {{-- Renseignements générales sur l'assistante maternelle avec ses disponibilités, nombre d'avis, notes et moyenne de note --}}
    <article class="box box-lg">
        <header>
            <h4>Renseignements 
                @if($moyenne !== null)
                    @for ($i = 1; $i <= $noteMax; $i++)
                        <i class="fs-6 text-warning @if($i <= $moyenne) fas @else far @endif fa-star"></i>
                    @endfor
                @endif
                    <span class="fs-6">({{$nombreNote}} notes et {{$nombreAvis}} avis)</span>
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
        <div class="contenu">
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
            <div id="presentation" class="my-3">
                <h5>Présentation :</h5>
                <p>{{ $renseignements->categorie->description ?? 'Aucune description' }}</p>
            </div>
            <footer class="d-flex flex-wrap justify-content-between border-top">
                <div>
                    <span>
                        Note : 
                        @for ($i = 1; $i <= $noteMax; $i++)
                            <i id="note{{ $i }}" alt="note {{$i}} / {{$noteMax}}" data-note="{{ $i }}" class="@if (isset($recommandation) && $i <=$recommandation->note) fas noteCheck @endif far fa-star note"></i>
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
        <header>
            <h4>Ses critères</h4>
        </header>
        <div class="contenu">
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
        <header>
            <h4>Ses tarifs</h4>
        </header>
        <div class="contenu">
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
        <header>
            <h4>Tous les avis</h4>
        </header>
        <div class="contenu">
            <div class="d-flex justify-content-center m-3 p-3">
                <div class="spinner-border" role="status"></div>
            </div>
            <div id='liste_avis' class="visually-hidden">
                <div id="messages_avis">
                </div>
                <div id="pagination_avis" class="text-center">
                </div>
            </div>
        </div>
    </article>
<script>
    const nounouId = document.querySelector('.favoris input').getAttribute('data-nounou-id');
    const pagination = document.querySelector('#pagination_avis');
    const messages = document.querySelector('#messages_avis');
    const noteMax = document.querySelectorAll('.note').length;
    
    
    function creationMessage(message){

        let p = document.createElement('p');
        p.classList.add('avis');
        let avisDate = document.createElement('span');
        avisDate.classList.add('avis_date')
        let avisNote = document.createElement('span');
        avisNote.classList.add('avis_note');
        let avisMessage = document.createElement('span');
        avisMessage.classList.add('avis_message');

        avisDate.innerHTML = `Le ${new Date(message.updated_at).toLocaleDateString('fr-FR')} par ${message.nom} ${message.prenom}`;
        avisNote.innerHTML = (message.note !== null) ? `Note : ${message.note}/${noteMax} ` : `aucune note`;
        avisMessage.innerHTML = `${message.avis}`;

        messages.appendChild(p);
        p.appendChild(avisDate);
        p.appendChild(avisNote);
        p.appendChild(avisMessage);

    }
    function loader(){
        document.querySelector('.spinner-border').parentNode.classList.toggle('visually-hidden');
        document.querySelector('#liste_avis').classList.toggle('visually-hidden');
    }
    function resetMessages(){
        let deleteMessages = Array.from(messages.children);
        deleteMessages.forEach(message => {
            message.remove();
        })
    }
    function creationPagination(link){
        let a = document.createElement('a');
        a.href = link.url;
        a.innerHTML = link.label;
        a.classList.add(`page_number${link.label}`);
        a.style.padding = '10px';
        pagination.appendChild(a);
        if(link.active){
            a.classList.add('page_current');
        }
        

        //Evenemement sur le click d'une page
        a.addEventListener('click', async function(e){
            e.preventDefault();

            loader();
            
            document.querySelector('.page_current').classList.remove('page_current');

            fetch(`${window.origin}/api/avis/${nounouId}?page=${link.label}`).then((element) => {
                loader();
                resetMessages();
                element.json().then((response) => {

                    document.querySelector(`.page_number${response.avis.current_page}`).classList.add('page_current');
                    
                    response.avis.data.forEach(message => {
                        creationMessage(message)
                    })
                })
            })
        })
    }
   
    window.addEventListener('load', function(){

        // Récupère l'ensemble des messages d'une assistante-maternelle
        fetch(`${window.origin}/api/avis/${nounouId}`).then((response) => {

            if(response.ok){

                loader();

                // Récupère la promesse
                response.json().then((element) => {

                    if(element.avis.data.length !== 0){
                        // Boucle pour les messages
                        element.avis.data.forEach(message => {
                            creationMessage(message)
                        })
        
                        // Boucle pour les liens de pagination
                        element.avis.links.forEach(link => {

                            if(link.label !== 'Suivant &raquo;' && link.label !== '&laquo; Précédent'){
                                creationPagination(link);
                            } 
                        })
                    }else{
                        messages.innerHTML = 'Aucun avis';
                    }
                    

                })
            }
            
        })
    })
</script>
@endsection
