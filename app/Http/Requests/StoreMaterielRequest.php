<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreMaterielRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nom'            => 'required|string|max:255',
            'description'    => 'nullable|string',
            'quantite_stock' => 'required|integer|min:0',
            'seuil_alerte'   => 'required|integer|min:0',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Erreur de validation.',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }

    public function messages(): array
    {
        return [
            'nom.required'            => 'Le nom est obligatoire.',
            'quantite_stock.required' => 'La quantité en stock est obligatoire.',
            'seuil_alerte.required'   => 'Le seuil d\'alerte est obligatoire.',
        ];
    }
}
