<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

beforeEach(function () {
    $this->validateCsv = function (UploadedFile $file) {
        $data = array_map('str_getcsv', file($file->getRealPath()));
        $errors = [];

        foreach ($data as $key => $row) {
            if ($key === 0 && strtolower($row[0]) === 'name') {
                continue;
            }
            if (count($row) < 2 || empty($row[0]) || empty($row[1]) || !filter_var($row[1], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid data on row " . ($key + 1);
            }
        }

        return $errors;
    };
});

test('validates a CSV file with correct content', function () {
    $validCsvContent = <<<CSV
name,email
rahul verma,rahul.verma@gmail.com
demo user,demo.user@gmail.com
CSV;

    $file = UploadedFile::fake()->createWithContent('valid_contacts.csv', $validCsvContent);

    $errors = ($this->validateCsv)($file);

    expect($errors)->toBeEmpty();
});

test('invalidates a CSV file with incorrect email format', function () {
    $invalidCsvContent = <<<CSV
name,email
rahul verma,rahulvermamail.com
CSV;

    $file = UploadedFile::fake()->createWithContent('invalid_contacts.csv', $invalidCsvContent);

    $errors = ($this->validateCsv)($file);

    expect($errors)->toHaveCount(1);
    expect($errors[0])->toContain('Invalid data on row 2');
});



test('invalidates a CSV file with missing data', function () {
    $invalidCsvContent = <<<CSV
name,email
rahul verma,
,invalid@gmail.com
CSV;

    $file = UploadedFile::fake()->createWithContent('missing_data.csv', $invalidCsvContent);

    $errors = ($this->validateCsv)($file);

    expect($errors)->toHaveCount(2);
    expect($errors[0])->toContain('Invalid data on row 2');
    expect($errors[1])->toContain('Invalid data on row 3');
});

test('invalidates an empty CSV file', function () {
    $emptyCsvContent = "";
    $file = UploadedFile::fake()->createWithContent('empty_contacts.csv', $emptyCsvContent);
    $errors = ($this->validateCsv)($file);
    expect($errors)->toBeEmpty(); 
});

test('validates a CSV file with extra columns but valid data', function () {
    $validCsvContent = <<<CSV
name,email,extra
rahul verma,rahul.verma@gmail.com,extra_data
demo user,demo.user@gmail.com,more_data
CSV;

    $file = UploadedFile::fake()->createWithContent('extra_columns.csv', $validCsvContent);

    $errors = ($this->validateCsv)($file);

    expect($errors)->toBeEmpty();
});
