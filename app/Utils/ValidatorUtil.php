<?php
namespace App\Utils;

class ValidatorUtil{
	public function orderValidation(){
		return [
            'buyer_mobile_number' => ['sometimes','required','regex:/^(09|\+639)\d{9}$/'],
            'user_id' => 'nullable|exists:users,id',
            'pickup_location' => 'sometimes|required',
            'warehouse' => 'sometimes|required',
            'supplier' => 'sometimes|required|max:30',
            'email' => 'sometimes|required|email|max:50',
            'location' => 'sometimes|required',
            'import_details' => 'sometimes|required',
            'buyer_name' => 'sometimes|required',
            'buyer_email' => 'sometimes|required|email',
            'product.*.type' => 'sometimes|required',
            'product.*.measurement' => 'required',
            'product.*.qty' => 'required|integer|min:1',
            'product.*.length' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'product.*.width' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'product.*.height' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'product.*.weight' => 'required|regex:/^\d*(\.\d{1,2})?$/',
        ];
	}

    public function validateAddType(){
        return [
            'warehouse' => 'required',
            'product.*.type' => 'required',
            'product.*.qty' => 'required|integer|min:1',
        ];
    }
    public function validateAddTypeMessages(){
        return [
                'product.*.qty.required' => 'This field is required.',
                'product.*.type.required' => 'This field is required.',
        ];
    }

    public function validateBoxes(){
        return [
            'product.*.qty' => 'required|integer|min:1',
            'product.*.length' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'product.*.width' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'product.*.height' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'product.*.weight' => 'required|regex:/^\d*(\.\d{1,2})?$/',
        ];
    }

    public function guessOrderValidation(){
        return [
            // 'buyer_last_name' => 'required',
            // 'supplier_last_name' => 'required',
            'buyer_mobile_number' => ['sometimes','required','regex:/^(09|\+639)\d{9}$/'],
            'pickup_location' => 'required',
            'warehouse' => 'sometimes|required',
            'supplier' => 'required|max:30',
            'email' => 'required|email|max:50',
            'location' => 'required',
            'import_details' => 'required',
            'product.*.measurement' => 'required',
            'product.*.qty' => 'required|integer|min:1',
            'product.*.length' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'product.*.width' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'product.*.height' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'product.*.weight' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'user_type' => 'required|in:buyer,supplier',
            'buyer_name' => 'required|max:30',
            'buyer_email' => 'required|email|max:50',
        ];
    }
	public function orderValidationMessages(){
		return [
                'product.*.qty.required' => 'This field is required.',
                'product.*.length.required' => 'This field is required.',
                'product.*.width.required' => 'This field is required.',
                'product.*.height.required' => 'This field is required.',
                'product.*.weight.required' => 'This field is required.',
                'product.*.measurement.required' => 'This field is required.',
                'product.*.type.required' => 'This field is required.',
                'cbm.regex' => 'This field must be formatted as ##.##',
                'weight.regex' => 'This field must be formatted as ##.##',
                'cbm.required_if' => 'This field is required',
                'weight.required_if' => 'This field is required',
                'product.*.length.regex' => 'This field must be formatted as ##.##',
                'product.*.width.regex' => 'This field must be formatted as ##.##',
                'product.*.height.regex' => 'This field must be formatted as ##.##',
                'product.*.weight.regex' => 'This field must be formatted as ##.##',
                'buyer_email.unique' => 'This email already exist. <a href="'. route("login"). '">  Login here</a>',
                'email.unique' => 'This email already exist. <a href="'. route("login"). '">  Login here</a>',
                'buyer_last_name.required' => 'This field is required',
                'supplier_last_name.required' => 'This field is required',
        ];
	}

    public function paymentValidation(){
        return [
            'bank_name' => 'required',
            'account_name' => 'required',
            'bank_address' => 'required',
            'supplier_address' => 'required',
            'invoice' => 'image',
            'amount' => 'required|regex:/^\d{0,8}(\.\d{1,2})?$/',
            'swift_code' => 'required',
            'supplier_name' => 'required',
            'supplier_email' => 'required|email',
        ];
    }

    public function paymentValidationMessages(){
        return [
            'amount.regex' => 'The amount format should be ##.##',
            'invoice.required' => 'The Proforma Invoice field is required.'
        ];
    }


    public function clearanceValidation(){
        return [
            'fullname' => 'required',
            'email' => 'required|email',
            'mobile_number' => ['required','regex:/^(09|\+639)\d{9}$/'],
            'delivery_address' => 'required',
            'shipping_company' => 'required',
            'supplier_name' => 'required',
            'supplier_email' => 'required|email',
            'invoice' => 'required|mimes:jpeg,png',
            'waybill' => 'required|mimes:jpeg,png'
        ];
    }

    public function clearanceValidationMessages(){
        return [
            'mobile_number.regex' => 'The mobile number format is invalid. (+639######### or 09#########)'
        ];
    }
}
