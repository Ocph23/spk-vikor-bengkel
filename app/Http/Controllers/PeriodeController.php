<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Periode;
use Illuminate\Http\Request;
use App\Http\Requests\PeriodeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class PeriodeController extends Controller
{
    public function index(): Response
    {
        $data = Periode::all();
        return Inertia::render('Periode/Index', [
            'data' => $data,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Periode/Create', []);
    }


    public function store(PeriodeRequest $request): RedirectResponse
    {

        $periodeAktif = Periode::where('status', 1)->first();
        if ($periodeAktif) {
            $periodeAktif->status = false;
            $periodeAktif->save();
        }
        Periode::create($request->validated());
        return Redirect::route('periode.index');
    }



    public function edit($id): Response
    {
        $data = Periode::find($id);
        return Inertia::render('Periode/Create', [
            'data' => $data,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(PeriodeRequest $request, $id): RedirectResponse
    {
        $data = Periode::find($id);
        $data->update($request->validated());
        return Redirect::route('periode.index');
    }

    /**
     * Delete the user's account.
     */
    public function destroy($id): RedirectResponse
    {
        $data = Periode::find($id);
        $data->delete();
        return Redirect::route('periode.index');
    }


}
