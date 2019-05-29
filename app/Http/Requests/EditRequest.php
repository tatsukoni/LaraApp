<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'scheduleName' => 'required|max:255',
            'memo' => 'required|max:255',
            'candidates' => 'max:255',
        ];
    }

    public function messages() {
        return [
            'scheduleName.required' => '予定名は必ず入力してください',
            'scheduleName.max' => '255文字以内で入力してください',
            'memo.required' => 'メモは必ず入力してください',
            'memo.max' => '255文字以内で入力してください',
            'candidates.max' => '255文字以内で入力してください',
        ];
    }
}
