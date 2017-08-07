<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostPriceRequest extends FormRequest
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
            'month.house_rent'    => 'integer',
            'month.water_works'   => 'integer',
            'month.gas'           => 'integer',
            'month.electrical'    => 'integer',
            'month.mobile_phone'  => 'integer',
            'month.saving'        => 'integer',
            'month.loan'          => 'integer',
            'month.insurance'     => 'integer',
            'month.credit_card'   => 'integer',
            'day.food'            => 'integer',
            'day.clothes'         => 'integer',
            'day.medical'         => 'integer',
            'day.traffic'         => 'integer',
            'day.social_expenses' => 'integer',
            'day.recreation'      => 'integer',
        ];
    }
}
