<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = ['marca_id', 'nome', 'imagem', 'numero_portas', 'lugares','air_bag', 'abs'];

    public function rules($id = null)
    {
        return [
            'marca_id' => 'existis:marcas,id',
            'nome' => "required|unique:modelos,nome,$this->id|min:2",
            'imagem' => 'required|file|mimes:png,jpeg,jpg',
            'numero_portas' => 'required|interger|digits_between:1,5',
            'lugares' => 'required|interger|digits_between:1,20',
            'air_bag'   => 'required|boolean',
            'abs'   => 'required|boolean',
        ];
    }

    
}
