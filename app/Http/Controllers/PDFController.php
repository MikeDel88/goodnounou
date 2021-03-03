<?php

namespace App\Http\Controllers;

use App\Models\Contrats as Contrat;
use App\Models\Horaire;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

/**
 * PDFController
 */
class PDFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $contrat Id
     * @param int $mois    Id
     * @param int $annee   Id
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF($contrat, $mois, $annee)
    {
        $getContrat = Contrat::where('id', ':contrat')->where('parent_id', ':parent')->setBindings(['contrat' => $contrat, 'parent' => Auth::user()->categorie->id])->first();
        if (!empty($getContrat)) {
            $data['horaires'] = Horaire::where('contrat_id', ':contrat')->whereMonth('jour_garde', ':mois')->whereYear('jour_garde', ':annee')->orderBy('jour_garde', 'asc')->setBindings(['contrat' => intval($contrat), 'mois' => $mois, 'annee' => $annee])->get();
            $data['contrat'] = $getContrat;
            $data['total'] = Horaire::select(DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(heure_fin, heure_debut)))) AS nombre_heures'))->where('contrat_id', ':contrat')->whereMonth('jour_garde', ':mois')->whereYear('jour_garde', ':annee')->setBindings(['contrat' => intval($contrat), 'mois' => $mois, 'annee' => $annee])->first();
            $data['mois'] = $this->recupMois($mois);
            $data['annee'] = intval($annee);

            $pdf = PDF::loadView('horairesPDF', $data);

            return $pdf->download("horaires-$mois-$annee.pdf");
        } else {
            return back()->with('message', "Ce document n'est pas accessible");
        }
    }

    /**
     * RecupMois
     *
     * @param mixed $mois Id
     *
     * @return void
     */
    public function recupMois($mois)
    {
        $listeMois = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre',
        ];

        return Arr::get($listeMois, $mois);
    }
}
