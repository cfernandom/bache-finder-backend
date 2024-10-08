<?php

namespace App\Http\Requests;

use App\Rules\Base64ImageRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePotholeRequest extends FormRequest
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
            'address' => 'required|string|max:255',
            'image' => ['required', new Base64ImageRule],
            'locality' => 'required|string|in:Usaquén,Chapinero,Santa Fé,San Cristóbal,Usme,Tunjuelito,Bosa,Kennedy,Fontibón,Engativá,Suba,Barrios Unidos,Teusaquillo,Los Mártires,Antonio Nariño,Puente Aranda,Candelaria,Rafael Uribe Uribe,Ciudad Bolívar,Sumapaz',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'user_id' => 'required|exists:users,id',
            'type' => 'nullable|string|in:No definido,Bache,Descascaramiento,Fisura en bloque,Fisura por deslizamiento,Fisura por reflexión,Fisuras longitudinales y transversales,Fisura transversal,Hundimiento,Parche,Pérdida de agregado,Piel de cocodrilo',
            'status' => 'nullable|string|in:Pendiente de revisión,En revisión,Resuelto,Anulado',
            'description' => 'nullable|string|max:512',
        ];
    }

    protected function prepareForValidation()
    {
        if (auth()->check()) {
            $this->merge([
                'user_id' => auth()->user()->id,
                'type' => $this->input('type', 'No definido'),
                'status' => $this->input('status', 'Pendiente de revisión'),
            ]);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
