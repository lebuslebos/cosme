<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
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
            'rate'=>'required|in:1,2,3,4,5,6,7|integer',
            'body'=>'nullable|string|max:300',
            'imgs'=>'array|max:5',
            'imgs.*'=>'nullable|string|max:100',
//            'imgs'=>'nullable|string|max:1024',
            'buy'=>'required|in:0,1|integer',
            'shop'=>'required|in:0,1,2,3,4|integer'
        ];
    }

}
