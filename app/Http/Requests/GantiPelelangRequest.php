<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GantiPelelangRequest extends FormRequest
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
            'permohonan_id' => 'required|uuid',
            'petugas_baru_id' => 'required|uuid',
            'id_alasan' => 'required|uuid',
            'surat_pergantian' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ];
    }
}
