<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
{
    protected $csv_data;
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
            'campaign_name' => 'required|string|max:255',
            'csv_file' => 'required|file|mimes:csv,txt',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->hasFile('csv_file')) {
                $file = $this->file('csv_file');
                $data = array_map('str_getcsv', file($file->getRealPath()));

                foreach ($data as $key => $row) {
                    if($key == 0 && strtolower($row[0]) == 'name') {
                        unset($data[0]);
                    } else if (count($row) < 2 || empty($row[0]) || empty($row[1]) || !filter_var($row[1], FILTER_VALIDATE_EMAIL)) {
                        $validator->errors()->add('csv_file', "Invalid data on row " . ($key + 1));
                        break;
                    }
                }
                $this->csv_data = $data;
            }
        });
    }

    public function getCsvData(): array
    {
        return $this->csv_data ?? [];
    }
}
