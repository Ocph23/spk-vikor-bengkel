<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\KriteriaRequest;
use Illuminate\Support\Facades\Redirect;

class KriteriaController extends Controller
{
    public function index(): Response
    {
        $data = Kriteria::all();
        return Inertia::render('Kriteria/Index', [
            'data' => $data,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Kriteria/Create', []);
    }


    public function store(KriteriaRequest $request): RedirectResponse
    {
        Kriteria::create($request->validated());
        return Redirect::route('kriteria.index');
    }



    public function edit($id): Response
    {
        $data = Kriteria::find($id);
        return Inertia::render('Kriteria/Create', [
            'data' => $data,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(KriteriaRequest $request, $id): RedirectResponse
    {
        $data = Kriteria::find($id);
        $data->update($request->validated());
        return Redirect::route('kriteria.index');
    }

    /**
     * Delete the user's account.
     */
    public function destroy($id): RedirectResponse
    {
        $data = Kriteria::find($id);
        $data->delete();
        return Redirect::route('kriteria.index');
    }




    public function bobot(Request $request): Response
    {
        $data = Kriteria::all();
        return Inertia::render('Kriteria/Bobot', [
            'data' => $data,
        ]);
    }


    public function storeBobot(Request $request): RedirectResponse
    {

        foreach ($request->all() as $key => $value) {

            $kriteria = Kriteria::find($value['id']);
            $kriteria->bobot = $value['bobot'];
            $kriteria->save();  
        }
        return Redirect::route('kriteria.bobot');
    }
}
