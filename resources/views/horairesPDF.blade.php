<!DOCTYPE html>
<html>

<head>
    <title>Horaires du mois</title>
</head>
<style>
    h1,
    h2,
    h3,
    p {
        margin: 0;
    }

    body {
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        color: rgba(31, 31, 31, 0.7);
    }

    h1 {
        text-align: left;
        font-size: 24px;
        background-color: #1a4567 !important;
        padding-top: 50px;
        padding-left: 10px;
        -webkit-print-color-adjust: exact;
        color: white;
    }

    h2 {
        text-align: left;
        font-size: 18px;
        padding: 10px 0 10px 10px;
        background-color: #1a4567 !important;
        -webkit-print-color-adjust: exact;
        color: rgba(255, 255, 255, 0.9);
    }

    h3 {
        border-bottom: 1px solid #1a4567;
        padding: 5px 0;
    }

    p {
        padding: 5px 0;
    }

    table {
        border-collapse: collapse;
        font-size: 14px;
        margin-top: 20px;
    }

    td,
    th {
        font-family: sans-serif;
        border: thin solid #1a4567;
        width: 50%;
        padding: 5px;
        text-align: center;
    }

    th {
        background-color: #1a4567;
        color: #ffffff;
        border-right: thin solid #ffffff;
    }

    th:last-child {
        border-right: thin solid #6495ed;
    }

    footer {
        margin: 10px 0;
    }

</style>

<body>
    <header>
        <h1>Horaires de garde</h1>
        <h2>{{ strtoupper($contrat->enfant->nom) }} {{ ucFirst($contrat->enfant->prenom) }}</h2>
    </header>
    <main>
        <section>
            <div>
                <h3>Période</h3>
                <p>{{ $mois }} {{ $annee }}</p>
            </div>
            <div>
                <h3>Total des heures</h3>
                <p>{{ str_replace(':', 'h', substr($total->nombre_heures, 0, -3)) }}</p>
            </div>
            <div>
                <h3>Nombre de jours</h3>
                <p>{{ count($horaires) }} jours</p>
            </div>
        </section>
        <section>
            <table>
                <tr>
                    <th>Jour de garde</th>
                    <th>Début</th>
                    <th>Déposé par</th>
                    <th>Fin</th>
                    <th>Récupéré par</th>
                    <th>Nombre d'heures</th>
                    <th>Commentaires</th>
                </tr>
                @foreach ($horaires as $horaire)
                    <tr>
                        <td>{{ Carbon\Carbon::parse($horaire->jour_garde)->translatedFormat('D j F Y') }}</td> -
                        <td>{{ substr($horaire->heure_debut, 0, -3) }}</td>
                        <td>{{ $horaire->depose_par ?? 'non renseigné' }}</td>
                        <td> {{ substr($horaire->heure_fin, 0, -3) }}</td>
                        <td>{{ $horaire->recupere_par ?? 'non renseigné' }}</td>
                        <td>{{ $horaire->nombre_heures }}</td>
                        <td>{{ $horaire->description ?? '' }}</td>
                    </tr>
                @endforeach
            </table>
        </section>
    </main>
    <footer>
        <small>Généré le {{ date('d/m/Y') }} - &copy; {{ env('APP_NAME')}} {{ date('Y') }}</small>
    </footer>
</body>

</html>
