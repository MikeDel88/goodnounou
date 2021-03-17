<?php

namespace App\Http\Controllers;

use App\Models\Recommandations;
use Illuminate\Http\Request;

/**
 * RecommandationsAPI
 */
class RecommandationsAPI extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $id Id
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $avis = Recommandations::join('users', 'users.categorie_id', '=', 'parent_id')
            ->select('users.nom', 'users.prenom', 'recommandations.updated_at', 'recommandations.note', 'recommandations.avis')
            ->where('users.categorie_type', 'App\\Models\\Parents')
            ->where('assistante_maternelle_id', $id)
            ->whereNotNull('avis')
            ->orderByDesc('recommandations.updated_at')
            ->paginate(10);

        return response()->json(['avis' => $avis]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request Requête
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->note < 6 && $request->note > 0 || $request->note === null) {
            Recommandations::updateOrCreate(
                ['parent_id' => $request->parent, 'assistante_maternelle_id' => $request->nounou],
                ['note' => $request->note]
            );
            $status = true;
        } else {
            $status = false;
        }

        return response()->json(['status' => $status]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id Id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id, $filtres)
    {
        $filtre = explode('_', $filtres);
        $infoFiltre = ($filtre[0] === 'avis') ? 'avis' : 'note';
        $filtre[0] = ($filtre[0] === 'avis') ? 'recommandations.updated_at' : 'recommandations.note';

        $avis = Recommandations::join('users', 'users.categorie_id', '=', 'parent_id')
            ->select('users.nom', 'users.prenom', 'recommandations.updated_at', 'recommandations.note', 'recommandations.avis')
            ->where('users.categorie_type', 'App\\Models\\Parents')
            ->where('assistante_maternelle_id', $id)
            ->whereNotNull($infoFiltre)
            ->orderBy($filtre[0], $filtre[1])
            ->paginate(10);

            return response()->json(['avis' => $avis]);

    }

}
