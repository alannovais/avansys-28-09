<?php

namespace App\Http\Controllers;

use App\Models\Operadora;
use Illuminate\Http\Request;

class OperadoraController extends Controller
{
    public function index()
    {
        return Operadora::all();
    }
    
    public function show($id)
    {
        return Operadora::find($id);
    }
    
    public function store(Request $request)
    {
        return Operadora::create($request->all());
    }
    
    public function update(Request $request, $id)
    {
        $operadora = Operadora::findOrFail($id);
        $operadora->update($request->all());
        
        return $operadora;
    }

    public function delete($id)
    {
        $operadora = Operadora::findOrFail($id);
        $operadora->update(['status' => false]);

        return $operadora;
    }
    
}
