<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * FavorisAPI
 */
class FavorisAPI extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request Requête
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (intval($request->nounou) && intval($request->parent)) {
            if ($request->favoris === true) {
                try {
                    DB::table('favoris')
                        ->insert(
                            ['parent_id' => $request->parent, 'assistante_maternelle_id' => $request->nounou]
                        );
                    return response()->json(['status' => 'ajoute']);
                } catch (\Illuminate\Database\QueryException $e) {
                    $errorCode = $e->errorInfo[1];
                    if ($errorCode == 1062) {
                        return back()->with('message', 'Cette assistante maternelle est déjà en favoris');
                    }
                }
            } elseif ($request->favoris === false) {
                DB::table('favoris')
                    ->where('parent_id', $request->parent)
                    ->where('assistante_maternelle_id', $request->nounou)
                    ->delete();
                return response()->json(['status' => 'supprime']);
            }
        }
    }
}
