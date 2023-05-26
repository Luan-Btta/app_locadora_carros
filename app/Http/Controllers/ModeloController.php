<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    protected $modelo;

    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return response()->json($this->modelo->all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate($this->modelo->rules(), $this->modelo->feedback());

        $image = $request->file('imagem');
        $image_urn = $image->store('imagens/modelos', 'public');

        $modelo = $this->modelo->create([
            'modelo_id' => $request->modelo_id,
            'nome' => $request->nome,
            'imagem' => $image_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs,
        ]);

        return response()->json($modelo, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $modelo = $this->modelo->find($id);
        if ($modelo === null) {
            return response()->json(['erro' => 'Recurso pesquisado não exite'], 404);
        }
        return response()->json($modelo, 200);
    }

    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);
        if ($modelo === null) {
            return response()->json(['erro' => 'Impossível atualizar, recurso não localizado'], 404);
        }

        if ($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            foreach ($modelo->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas = [$input => $regra];
                }
            }

            $request->validate($regrasDinamicas);

        } else {
            $request->validate($modelo->rules());
        }

        if ($request->file('imagem')) {
            Storage::disk('public')->delete($modelo->imagem);
            $image = $request->file('imagem');
            $image_urn = $image->store('imagens/modelos', 'public');
        }

        if (isset($regrasDinamicas)) {
            foreach ($regrasDinamicas as $chave => $valor) {
                if ($chave == 'imagem') {
                    $modelo->update([
                        $chave => $image_urn
                    ]);
                } else {
                    $modelo->update([
                        $chave => $request->$chave
                    ]);
                }
            }
        } else {

            $modelo->update([
                'modelo_id' => $request->modelo_id,
                'nome' => $request->nome,
                'imagem' => $image_urn,
                'numero_portas' => $request->numero_portas,
                'lugares' => $request->lugares,
                'air_bag' => $request->air_bag,
                'abs' => $request->abs,
            ]);
        }

        return response()->json($modelo, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);
        if ($modelo === null) {
            //return ['erro' => 'Impossível remover, recurso não localizado'];
            return response()->json(['erro' => 'Impossível remover, recurso não localizado'], 404);
        }

        if ($modelo->imagem) {
            Storage::disk('public')->delete($modelo->imagem);
        }

        $modelo->delete();

        //return ['msg' => 'modelo removida com sucesso!'];
        return response()->json(['msg' => 'Modelo removido com sucesso!'], 200);
    }
}