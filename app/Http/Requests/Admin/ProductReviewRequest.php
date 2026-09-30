<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],
            'order_id' => [
                'required',
                'integer',
                Rule::exists('orders', 'id')->where(
                    fn ($query) => $query->where('platform', 'website')
                ),
            ],
            'rating' => [
                'required',
                'integer',
                'between:1,5',
            ],
            'review' => [
                'required',
                'string',
                'min:3',
                'max:5000',
            ],
            'size' => [
                'nullable',
                'string',
                'max:100',
            ],
            'color' => [
                'nullable',
                'string',
                'max:100',
            ],
            'images' => [
                'nullable',
                'array',
                'max:5',
            ],
            'images.*' => [
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
            'video' => [
                'nullable',
                'file',
                'mimes:mp4,mov,avi,webm',
                'max:20480',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Please select a product.',
            'product_id.exists' => 'Selected product does not exist.',
            'user_id.required' => 'Please select a customer.',
            'user_id.exists' => 'Selected customer does not exist.',
            'order_id.required' => 'Please select an order.',
            'order_id.exists' => 'Selected order is not a valid website order.',
            'rating.required' => 'Please select a rating.',
            'rating.between' => 'Rating must be between 1 and 5.',
            'review.required' => 'Review text is required.',
            'review.min' => 'Review must be at least 3 characters.',
            'review.max' => 'Review may not exceed 5000 characters.',
            'size.max' => 'Size may not exceed 100 characters.',
            'color.max' => 'Color may not exceed 100 characters.',
            'images.array' => 'Invalid image selection.',
            'images.max' => 'You can upload a maximum of 5 images.',
            'images.*.image' => 'Each review image must be a valid image.',
            'images.*.mimes' => 'Review images must be JPG, JPEG, PNG, or WEBP.',
            'images.*.max' => 'Each review image may not exceed 5 MB.',
            'video.file' => 'Invalid review video.',
            'video.mimes' => 'Review video must be MP4, MOV, AVI, or WEBM.',
            'video.max' => 'Review video may not exceed 20 MB.',
        ];
    }
}