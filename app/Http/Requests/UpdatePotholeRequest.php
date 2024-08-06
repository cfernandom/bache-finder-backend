<?php

namespace App\Http\Requests;

use App\Rules\Base64ImageRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePotholeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'address' => 'sometimes|string|max:255',
            'type' => 'nullable|string|in:No definido,Bache,Descascaramiento,Fisura en bloque,Fisura por deslizamiento,Fisura por reflexión,Fisuras longitudinales y transversales,Fisura transversal,Hundimiento,Parche,Pérdida de agregado,Piel de cocodrilo',
            'locality' => 'sometimes|string|in:Usaquén,Chapinero,Santa Fe,San Cristóbal,Usme,Tunjuelito,Bosa,Kennedy,Fontibón,Engativá,Suba,Barrios Unidos,Teusaquillo,Los Mártires,Antonio Nariño,Puente Aranda,Candelaria,Rafael Uribe Uribe,Ciudad Bolívar,Sumapaz',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'status' => 'nullable|string|in:Pendiente de revisión,En revisión,Resuelto,Anulado',
            'description' => 'nullable|string|max:512',
            'solution_description' => 'nullable|string|max:512',
            'image' => [new Base64ImageRule],
            'predictions' => 'nullable|json', // TODO: validate numeric array
        ];
    }
}
