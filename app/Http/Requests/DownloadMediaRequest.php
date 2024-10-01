<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DownloadMediaRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        // You can add custom authorization logic here if needed
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'file_id' => 'required|string', // Assuming 'files' is your table
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array
     */
    public function messages() {
        return [
            'file_id.required' => 'The file ID is required.',
            'file_id.exists' => 'The file does not exist in the database.',
        ];
    }
}
