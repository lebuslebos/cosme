<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImgRequest extends FormRequest
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
            'file'=>'required|image|max:3072'
        ];
    }

    public function messages()
    {
        return [
            'file.required'=>'图片无效',
            'file.image'=>'只能传图片',
            'file.max'=>'图片太大了',
//            'file.dimensions'=>'图片宽度太大了',
        ];
    }
}
