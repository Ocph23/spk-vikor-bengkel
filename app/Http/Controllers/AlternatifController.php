<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Periode;
use App\Models\Kriteria;
use App\Models\Alternatif;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\AlternatifRequest;
use Illuminate\Support\Facades\Redirect;

class AlternatifController extends Controller
{
    public function index(): Response
    {

        // $periodeAktif = Periode::where('status', 1)->first();   
        // $data = Alternatif::where('periode_id', $periodeAktif->id)->get();
         $data = Alternatif::all();

        // $kriteria = Kriteria::with('alternatif')->get();

        return Inertia::render('Alternatif/Index', [
            'data' => $data,
            'status' => session('status'),
            // 'periodeAktif' => $periodeAktif,
            // 'periode' => Periode::all(),    
            // 'kriteria' => $kriteria,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Alternatif/Create', []);
    }


    public function store(AlternatifRequest $request): RedirectResponse
    {
        Alternatif::create($request->validated());
        return Redirect::route('alternatif.index');
    }



    public function edit($id): Response
    {
        $data = Alternatif::find($id);
        return Inertia::render('Alternatif/Create', [
            'data' => $data,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(AlternatifRequest $request, $id): RedirectResponse
    {
        $data = Alternatif::find($id);
        $data->update($request->validated());
        return Redirect::route('alternatif.index');
    }

    /**
     * Delete the user's account.
     */
    public function destroy($id): RedirectResponse
    {
        $data = Alternatif::find($id);
        $data->delete();
        return Redirect::route('alternatif.index');
    }



}
