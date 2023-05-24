<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    protected $marca;

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$marcas = Marca::all();
        $marcas = $this->marca->all();
        return response()->json($marcas, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());

        //dd($request->all());
        //$marca = Marca::create($request->all());
        //dd($marca);
        //return $marca;

        $image = $request->file('imagem');
        $image_urn = $image->store('imagens', 'public');

        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $image_urn,
        ]);

        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            //return ['erro' => 'Recurso pesquisado não exite'];
            return response()->json(['erro' => 'Recurso pesquisado não exite'], 404);
        }
        return response()->json($marca, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //print_r($marca->getAttributes());
        //dd($request->all());
        //print_r($request->nome);
        //dd($request->file('imagem'));

        $marca = $this->marca->find($id);
        if ($marca === null) {
            //return ['erro' => 'Impossível atualizar, recurso não localizado'];
            return response()->json(['erro' => 'Impossível atualizar, recurso não localizado'], 404);
        }

        if ($request->method() === 'PATCH') {


            $regrasDinamicas = array();

            //dd($marca->rules());

            foreach ($marca->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas = [$input => $regra];
                }
            }

            $request->validate($regrasDinamicas, $marca->feedback());

        } else {
            $request->validate($marca->rules(), $marca->feedback());
        }

        if ($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);
        }

        $image = $request->file('imagem');
        $image_urn = $image->store('imagens', 'public');

        $marca->update([
            'nome' => $request->nome,
            'imagem' => $image_urn,
        ]);

        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            //return ['erro' => 'Impossível remover, recurso não localizado'];
            return response()->json(['erro' => 'Impossível remover, recurso não localizado'], 404);
        }

        if ($marca->imagem) {
            Storage::disk('public')->delete($marca->imagem);
        }

        $marca->delete();

        //return ['msg' => 'Marca removida com sucesso!'];
        return response()->json(['msg' => 'Marca removida com sucesso!'], 200);
    }
}