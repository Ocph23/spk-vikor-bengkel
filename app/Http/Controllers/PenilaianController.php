<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Periode;
use App\Models\Kriteria;
use App\Models\Alternatif;
use Illuminate\Http\Request;
use App\Models\NilaiAlternatif;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\PenilaianRequest;
use Illuminate\Support\Facades\Redirect;

class PenilaianController extends Controller
{
    public function index(): Response
    {

        $periodeAktif = Periode::where('status', 1)->first();
        $kriteria = Kriteria::with('subkriteria')->get();
        $alternatif = Alternatif::with('nilaiAlternatif')->get();
        foreach ($kriteria as $key => $k) {
            foreach ($alternatif as $key => $a) {
                $existsData = $a->nilaiAlternatif()->where('kriteria_id', $k->id)
                    ->where('periode_id', $periodeAktif->id)->first();
                if (!$existsData) {
                    $a->nilaiAlternatif()->create([
                        'periode_id' => $periodeAktif->id,
                        'kriteria_id' => $k->id,
                        'sub_kriteria_id' => 0,
                    ]);
                }
            }
        }



        return Inertia::render('Penilaian/Index', [
            'data' => '$data',
            'status' => session('status'),
            'periodeAktif' => $periodeAktif,
            'periode' => Periode::all(),
            'kriteria' => $kriteria,
            'alternatif' => $alternatif,
        ]);
    }


    public function store(PenilaianRequest $request): RedirectResponse
    {
        $result = $request->all();
        foreach ($result['penilaian'] as $key => $alternatif) {
            foreach ($alternatif['nilai_alternatif'] as $key => $nilai) {
                $nilaiAlternatif = NilaiAlternatif::find($nilai['id']);
                $nilaiAlternatif->fill($nilai);
                $nilaiAlternatif->save();
            }

        }
        //Kriteria::create($request->validated());
        return Redirect::route('penilaian.index');
    }
}
