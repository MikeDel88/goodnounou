@extends('layouts.app')
@section('content')
    <section id="parents">
        <h2 class="right">Pour <strong>les parents</strong></h2>
        <figure>
            <img src="{{ URL::asset('assets/images/parents.png') }}" alt="parents avec leur enfant" loading="lazy">
        </figure>
        <article>
            <h3 class="inactive">Description du site pour les parents</h3>
            <p><strong>Géolocaliser</strong> une nounou près de chez vous</p>
            <p>Avec les critères dont vous avez besoin</p>
            <p>Etablissez une relation de <strong>confiance</strong></p>
            <p>Et garder un suivi</p>
        </article>
        <a href="{{ route('register') }}" class="lien btn btn-parents">Je suis un parent</a>
    </section>
    <div class="couleur-de-fond">
        <section id="assistante-maternelle">
            <h2>Pour les <strong>assistantes maternelle</strong></h2>
            <article>
                <h3 class="inactive">Description du site pour les assistantes maternelles</h3>
                <p>Créer une <strong>fiche personnalisée</strong></p>
                <p>Renseignez votre manière de travailler</p>
                <p>Et soyez contacter par des parents qui vous ont choisis</p>
                <p>Mettez à jour le <strong>carnet de suivi des enfants</strong> dont vous avez la garde</p>
            </article>
            <a href="{{ route('register') }}" class="lien btn btn-ass-mat">Je suis une nounou</a>
            <figure class="right">
                <img src="{{ URL::asset('assets/images/assistante-maternelle.png') }}" alt="assistante maternelle"
                    loading="lazy">
            </figure>
        </section>
    </div>
    <section id="simple-gratuit" class="description">
        <h2>Simple et gratuit</h2>
        <div class="contenu right">
            <p>Inscription sans frais en quelques clics</p>
            <p>Gérer votre profil personnalisé en toute simplicité</p>
            <p>Rechercher et contacter les nounous proche de chez vous</p>
        </div>
    </section>
    <section id="suivi-personnalise" class="description">
        <h2 class="right">Un suivi personnalisé</h2>
        <div class="contenu left">
            <p>Garder une trace des évolutions de votre enfant dans un carnet de bord</p>
            <p>Retrouver les informations essentielles à votre contrat</p>
            <p>Restez en contact avec l’assistante maternelle</p>
        </div>
    </section>
@endsection
