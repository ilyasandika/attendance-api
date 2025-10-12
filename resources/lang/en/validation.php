<?php

return [
    'required' => 'The :attribute field is required.',
    'string' => 'The :attribute must be a string.',
    'email' => 'The :attribute must be a valid email address.',
    'max' => 'The :attribute may not be greater than :max characters.',
    'min' => 'The :attribute must be at least :min characters.',
    'unique' => 'The :attribute has already been taken.',
    'in' => 'The selected :attribute is invalid. Allowed values: :values.',
    'boolean' => 'The :attribute field must be true or false.',
    'integer' => 'The :attribute must be an integer.',
    'url' => 'The :attribute format is invalid.',
    'regex' => 'The :attribute format is invalid.',
    'same' => 'The :attribute and :other must match.',


    'attributes' => [
        // User
        'employeeId' => 'Employee ID',
        'name' => 'Name',
        'gender' => 'Gender',
        'birthDate' => 'Birth Date',
        'phoneNumber' => 'Phone Number',
        'email' => 'Email',
        'roleId' => 'Role',
        'departmentId' => 'Department',
        'shiftId' => 'Shift',
        'locationId' => 'Location',
        'password' => 'Password',
        'whatsapp' => 'WhatsApp',
        'linkedin' => 'LinkedIn',
        'telegram' => 'Telegram',
        'biography' => 'Biography',
        'status' => 'Account Status',
        'photo' => 'Photo',
        'confirmPassword' => 'Confirm Password',
        "latitude" => "Latitude",
        "longitude" => "Longitude",
        "radius" => "Radius",
        "address" => "Address",
        "description" => "Description",
        "default" => "Default"


    ],

    //custom

    'phoneNumber.regex' => 'The Phone Number format is invalid. Please only enter 9-20 digit numbers.',
    'employeeId.regex' => 'The Employee ID format is invalid. Please only enter numbers or letters.',
];
