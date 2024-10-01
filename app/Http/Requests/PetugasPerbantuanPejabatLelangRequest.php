<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PetugasPerbantuanPejabatLelangRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nip' => 'required|string',
            'nama' => 'required|string',
            'unit_kerja_id' => 'required|uuid',
            'group_id' => 'required|uuid',
            'file_nd' => 'file|mimes:pdf',
            'file_kesediaan' => 'file|mimes:pdf',
        ];
    }
}
