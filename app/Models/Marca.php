<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'imagem'];

    public function rules()
    {
        return [
            'nome' => 'required|unique:marcas|min:3',
            'imagem' => 'required',
        ];
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
