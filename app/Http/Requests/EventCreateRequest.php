<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class EventCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        Validator::extend('check_title', function ($attribute, $value) {
            return preg_match('/\pL\pM*|./u', $value);
        });

        Validator::extend('check_location', function ($attribute, $value) {
            return preg_match('/\pL\pM*|./u', $value);
        });

        $rules = [
            'select_type'  => 'required|in:public,private,online',
            'schedule'     => 'required|in:0,1',
            'title'        => 'required|max:100|check_title',
            'description'  => 'required|max:255',
            'category'     => 'required',
            'organised_by' => 'required',
            'location'     => 'required|check_location',
            'event_date'   => 'required|date|after_or_equal:yesterday',
            'start_time'   => 'required',
            'duration'     => 'required',
            'enable_attendance' => 'nullable',
            'publish_to_web'    => 'nullable',
            'enable_gallery'    => 'nullable',
        ];

        if ($this->input('schedule') === '1') {
            $rules['freq']            = 'required|integer|min:1';
            $rules['freq_term']       = 'required|in:day,week,month,year';
            $rules['series_end_date'] = 'required|date|after:event_date';
        }

        if ($this->input('schedule') === '1' && $this->input('freq_term') === 'week') {
            $rules['days_of_week']   = 'required|array|min:1';
            $rules['days_of_week.*'] = 'integer|between:0,6';
        }

        if ($this->input('schedule') === '1' && $this->input('freq_term') === 'month') {

            $rules['month_type'] = 'required';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'select_type.required'      => 'Please select an event type.',
            'schedule.required'         => 'Please select one-time or recurring.',
            'title.required'            => 'Event title is required.',
            'title.check_title'         => 'Title contains invalid characters.',
            'description.required'      => 'Description is required.',
            'category.required'         => 'Please select a category.',
            'organised_by.required'     => 'Organiser name is required.',
            'location.required'         => 'Location is required.',
            'location.check_location'   => 'Location contains invalid characters.',
            'event_date.required'       => 'Event date is required.',
            'event_date.after_or_equal' => 'Event date cannot be in the past.',
            'start_time.required'       => 'Start time is required.',
            'duration.required'         => 'Please select a duration.',
            'freq.required'             => 'Repeat frequency is required.',
            'freq.min'                  => 'Repeat frequency must be at least 1.',
            'freq_term.required'        => 'Repeat period is required.',
            'series_end_date.required'  => 'Series end date is required.',
            'series_end_date.after'     => 'Series end date must be after the start date.',
            'days_of_week.required'     => 'Please select at least one day to repeat on.',
            'days_of_week.min'          => 'Please select at least one day to repeat on.',
            'month_type'          => 'Please select month type.'
        ];
    }
}
