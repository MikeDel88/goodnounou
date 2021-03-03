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
            ->limit(100)
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
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request Requête
     * @param int                      $id      Id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id Id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
