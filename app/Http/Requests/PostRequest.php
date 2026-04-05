<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
      return [
        'category' => 'required|string',
        // ქართული ვერსია (სავალდებულო)
        'title_ka' => 'required|string|max:255',
        'description_ka' => 'required|string',
        'image_ka' => 'nullable|image|max:2048',

        // ინგლისური ვერსია (არასავალდებულო)
        'title_en' => 'nullable|string|max:255',
        'description_en' => 'nullable|string',
        'image_en' => 'nullable|image|max:2048',
    ];
    }
}
