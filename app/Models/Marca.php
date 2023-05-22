<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'imagem'];

    public function rules($id = null)
    {
        return [
            'nome' => "required|unique:marcas,nome,$this->id|min:3",
            'imagem' => 'required',
        ];
        /*
            1- TABELA
            2- NOME DA COLUNA QUE SERÁ PESQUISADA NA TABELA
            3- ID DO REGISTRO QUE SERÁ DESCONSIDERADO NA PESQUISA
        */
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'nome.unique' => 'A marca informada já existe no sistema.',
            'nome.min' => 'O nome deve conter no mínimo 3 caracteres',
        ];
    }
}
