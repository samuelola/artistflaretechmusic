<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CloudflareTurnstile;

class SubscriptionRequest extends FormRequest
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
            'subscription_name'=>'required|string',
            'artist_no'=>'required|string',
            'stock_keeping_unit'=>'required',
            'no_of_tracks'=>'required',
            'no_of_products'=>'required',
            'max_no_of_tracks_per_products'=>'required',
            'max_no_of_artists_per_products' => 'required',
            'subscription_for'=>'required',
            'track_file_quality' => 'required',
            'currency' => 'required',
            'subscription_amount' => 'required',
            'plan_info_text' => 'required',
            'include_tax' => 'required',
            'subscription_duration' => 'required',
            'subscription_limit_per_year' => 'required',
            // 'is_this_free_subscription' => 'required',
            // 'is_cancellation_enable' => 'required',
            // 'is_one_time_subscription' => 'required',
           
        ];
    }

     public function messages()
    {
        return [
            'subscription_name.required' => 'Subscription Name field is required',
            'artist_no.required' => 'No. of Artists field is required.',
            'stock_keeping_unit.required' => 'Stock Keeping Unit field is required',
            'no_of_tracks.required' => 'No. of Tracks field is requird.',
            "no_of_products.required" => "No. of Products field is required",
            "max_no_of_tracks_per_products.required" => "Max No. of Tracks Per Product field is is required",
            "max_no_of_artists_per_products.required" => "Max No. of Artists Per Product field is required",
            "subscription_for.required" => "Subscription for field is required",
            "track_file_quality.required" => "Track File Quality field is required",
            "currency.required" => "Currency field is required",
            "subscription_amount.required" => "Subscription Amount field is requird.",
            "plan_info_text.required" => "Plan Info Text field is required",
            "include_tax.required" => "Include Tax is required",
            "subscription_duration.required" => "Subscription Duration is required",
            "subscription_limit_per_year.required" => "Subscription Limit per year field is required",
            // "is_this_free_subscription.required" => "Free Subscription field is required",
            // "is_cancellation_enable.required" => "Cancellation field is required",
            // "is_one_time_subscription.required" => "One Time Subscription field is required",
            
        ];
    }
}
