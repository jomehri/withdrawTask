<?php

namespace App\Http\Requests\Tax;

use Illuminate\Foundation\Http\FormRequest;

class TaxProcessValidator extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		/**
		 * No authentication is needed at this level
		 */
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'file' => ['required', 'mimes:csv,txt'],
		];
	}

	/**
	 * Custom message for validation
	 *
	 * @return array
	 */
	public function messages(): array
	{
		return [
			"file.required" => "Add a file to import",
			"file.mimes"    => "Upload a CSV file",
		];
	}


}
