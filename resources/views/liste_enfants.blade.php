@extends('layouts.back')
@section('content')
    <article class="box box-lg">
        <header>
            <h4>Ajouter un enfant</h4>
        </header>
        <div class="contenu">
            <form action="/liste/enfants" method="POST">
                @csrf
                <div class="row d-flex flex-wrap">
                    <div class="mb-3 col-md-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" value="{{ old('nom') }}" name="nom" required>
                    </div>
                    <div class="mb-3 col-md-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" value="{{ old('prenom') }}" name="prenom"
                            required>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="date_naissance" class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" id="date_naissance" value="{{ old('date_naissance') }}"
                            min="1950-01-01" max="{{ date('Y-m-d') }}" name="date_naissance" required>
                    </div>
                    <div class="mb-3 col-md-2 d-flex align-items-end">
                        <button type="submit" class="px-3"><i class="fas fa-user-plus fa-1x p-2"></i></button>
                    </div>
                </div>
            </form>
            @if ($errors->any())
                <div class="mt-3 alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </article>
    <article class="box box-md">
        <header>
            <h4>Liste de mes enfants</h4>
        </header>
        <div class="contenu">
            <ul>
                @foreach ($enfants as $enfant)
                    <li>
                        <a href="fiche/enfant/{{ $enfant->id }}">
                            {{ $enfant->nom }}
                            {{ $enfant->prenom }}
                            ({{ Carbon\Carbon::parse($enfant->date_naissance)->age }} ans)
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </article>
@endsection
