<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComprasRequest extends FormRequest
{

    public function authorize(){
        return true;
    }
    public function rules(){
        return [
            'fecha' => 'required',
            'proveedore_id' => 'required',
            'tipospago_id' => 'required',
            'total_iva' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
        ];
    }
    public function messages(){
        return [
            'fecha.required' => 'La fecha es requerida',
            'proveedore_id.required' => 'Se necesita incluir el proveedor',
            'tipospago_id.required' => 'Es necesario incluir el tipo de pago',
        ];
    }
}
