<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isEdit = (bool) $this->route('apartment');
        return [
            'title' => ['required', 'string', 'min:5', 'max:120'],
            'owner_id' => ['required', 'exists:owners,id'],
            'monthly_rent' => ['required', 'numeric', 'min:1000', 'max:200000'],
            'address' => ['required', 'string', 'min:6', 'max:200'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'state' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'municipality' => ['nullable', 'string', 'max:100'],
            'locality' => ['nullable', 'string', 'max:150'],
            'nearby_tipo' => ['nullable', 'array'],
            'nearby_tipo.*' => ['nullable', 'string', 'max:80'],
            'nearby_nombre' => ['nullable', 'array'],
            'nearby_nombre.*' => ['nullable', 'string', 'max:120'],
            'nearby_metros' => ['nullable', 'array'],
            'nearby_metros.*' => ['nullable', 'integer', 'min:0', 'max:50000'],
            'description' => ['required', 'string', 'min:30', 'max:3000'],
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'available_from' => ['required', 'date', 'after_or_equal:today'],
            'is_furnished' => ['required', 'in:0,1'],
            'has_ac' => ['nullable', 'boolean'],
            'has_tv' => ['nullable', 'boolean'],
            'has_wifi' => ['nullable', 'boolean'],
            'has_kitchen' => ['nullable', 'boolean'],
            'has_parking' => ['nullable', 'boolean'],
            'has_laundry' => ['nullable', 'boolean'],
            'has_heating' => ['nullable', 'boolean'],
            'has_balcony' => ['nullable', 'boolean'],
            'pets_allowed' => ['nullable', 'boolean'],
            'smoking_allowed' => ['nullable', 'boolean'],
            'max_people' => ['required', 'integer', 'min:1', 'max:50'],
            'status' => ['required', 'in:activo,inactivo'],
            'rules' => ['required', 'array', 'min:3', 'max:10'],
            'rules.*' => ['required', 'string', 'min:3', 'max:200'],
            'photos' => [$isEdit ? 'nullable' : 'required', 'array', 'min:1', 'max:10'],
            'photos.*' => ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'rules.min' => 'Debes añadir al menos 3 reglas.',
            'rules.max' => 'Máximo 10 reglas.',
            'rules.*.required' => 'Cada regla debe tener al menos 3 caracteres.',
            'photos.required' => 'Debes subir al menos una foto del inmueble.',
            'photos.min' => 'Debes subir al menos una foto.',
            'photos.max' => 'Máximo 10 fotos.',
            'photos.*.image' => 'Cada archivo debe ser una imagen (JPG, PNG o WebP).',
            'photos.*.max' => 'Cada foto debe pesar menos de 4 MB. Si no se guardan, sube imágenes más ligeras o aumenta upload_max_filesize en PHP.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'título',
            'owner_id' => 'dueño',
            'monthly_rent' => 'renta mensual',
            'address' => 'dirección',
            'description' => 'descripción del inmueble',
            'lat' => 'ubicación en mapa',
            'lng' => 'ubicación en mapa',
            'available_from' => 'fecha de disponibilidad',
            'is_furnished' => 'amueblado',
            'rules' => 'reglas y políticas',
            'rules.*' => 'regla',
            'max_people' => 'máximo de personas',
            'photos' => 'fotos',
            'photos.*' => 'foto',
        ];
    }

    /** Asegura que los booleanos lleguen como 0/1 para la BD. */
    protected function prepareForValidation(): void
    {
        $booleans = [
            'has_ac', 'has_tv', 'has_wifi', 'has_kitchen', 'has_parking',
            'has_laundry', 'has_heating', 'has_balcony', 'pets_allowed', 'smoking_allowed',
        ];
        foreach ($booleans as $key) {
            if (! $this->has($key)) {
                $this->merge([$key => false]);
            }
        }
    }
}
