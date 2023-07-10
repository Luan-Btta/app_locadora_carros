<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = ['marca_id', 'nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs'];

    public function rules($id = null)
    {
        return [
            'marca_id' => 'exists:marcas,id',
            'nome' => "required|unique:modelos,nome,$this->id|min:2",
            'imagem' => 'required|file|mimes:png,jpeg,jpg',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'lugares' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean',
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'nome.unique' => 'O Modelo informado já existe no sistema.',
            'nome.min' => 'O nome deve conter no mínimo 3 caracteres.',
            'imagem.mimes' => 'Tipo inválido, favor utilizar imagens .png, .jpg ou .jpeg.',
            'integer' => 'A quantidade de ocupantes informada é inválida.',
            'digits_between' => 'O :attribute informado é inválido.',
            'boolean' => 'O :attribute informado é inválido.',
        ];
    }

}