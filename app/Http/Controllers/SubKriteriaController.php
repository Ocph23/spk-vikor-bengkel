<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\SubKriteriaRequest;

class SubKriteriaController extends Controller
{
    public function index(): Response
    {
        $data = Kriteria::all();
        return Inertia::render('SubKriteria/Index', [
            'kriteria' => $data,
        ]);
    }

    public function bykriteriaid($id)
    {
        $data = SubKriteria::where('kriteria_id', $id)->get();
        return $data->toJson();
    }

    public function store(SubKriteriaRequest $request)
    {
       try {
        $result =SubKriteria::create($request->validated());
        return response()->json([
            'data' =>  $result,
        ], 200);
       
       } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
       }
    }

    public function update(SubKriteriaRequest $request, $id)
    {
       try {

        $result =SubKriteria::find($id);
        $result->update($request->validated());

        return response()->json([
            'data' =>  $result,
        ], 200);
       
       } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
       }
    }

    public function destroy($id)
    {
       try {

        $result =SubKriteria::find($id);
        $result->delete();

        return response()->json([
            'data' =>  $result,
        ], 200);
       
       } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
       }
    }

}
