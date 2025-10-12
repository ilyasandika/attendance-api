<?php
//
//return [
//    'employee_id_required' => 'ID karyawan wajib diisi.',
//    'employee_id_string' => 'ID karyawan harus berupa teks.',
//    'employee_id_unique' => 'ID karyawan sudah digunakan.',
//
//    'name_required' => 'Nama wajib diisi.',
//    'name_string' => 'Nama harus berupa teks.',
//    'name_max' => 'Nama tidak boleh lebih dari :max karakter.',
//
//    'gender_required' => 'Jenis kelamin wajib diisi.',
//    'gender_string' => 'Jenis kelamin harus berupa teks.',
//    'gender_in' => 'Jenis kelamin harus bernilai male atau female.',
//
//    'birth_date_required' => 'Tanggal lahir wajib diisi.',
//    'birth_date_integer' => 'Tanggal lahir harus berupa angka (timestamp).',
//
//    'phone_number_required' => 'Nomor telepon wajib diisi.',
//    'phone_number_integer' => 'Nomor telepon harus berupa angka.',
//    'phone_number_max' => 'Nomor telepon tidak boleh lebih dari :max karakter.',
//
//    'email_required' => 'Email wajib diisi.',
//    'email_string' => 'Email harus berupa teks.',
//    'email_email' => 'Format email tidak valid.',
//    'email_max' => 'Email tidak boleh lebih dari :max karakter.',
//    'email_unique' => 'Email sudah digunakan.',
//
//    'role_id_required' => 'Role wajib dipilih.',
//    'role_id_integer' => 'Role harus berupa angka.',
//
//    'department_id_required' => 'Departemen wajib dipilih.',
//    'department_id_integer' => 'Departemen harus berupa angka.',
//
//    'shift_id_required' => 'Shift wajib dipilih.',
//    'shift_id_integer' => 'Shift harus berupa angka.',
//
//    'location_id_required' => 'Lokasi wajib dipilih.',
//    'location_id_integer' => 'Lokasi harus berupa angka.',
//
//    'password_required' => 'Password wajib diisi.',
//    'password_string' => 'Password harus berupa teks.',
//    'password_min' => 'Password minimal :min karakter.',
//
//    'whatsapp_min' => 'Nomor WhatsApp minimal :min karakter.',
//    'whatsapp_max' => 'Nomor WhatsApp maksimal :max karakter.',
//
//    'linkedin_url' => 'URL LinkedIn tidak valid.',
//
//    'telegram_min' => 'Nomor Telegram minimal :min karakter.',
//    'telegram_max' => 'Nomor Telegram maksimal :max karakter.',
//
//    'status_required' => 'Status akun wajib dipilih.',
//
//    'photo_image' => 'Photo harus berupa gambar.',
//    'photo_mimes' => 'Photo harus berupa gambar dengan format jpg, jpeg, png.',
//    'photo_max' => 'Photo tidak boleh lebih dari :max KB.',
//
//];

return [
    'required' => ':attribute wajib diisi.',
    'string' => ':attribute harus berupa teks.',
    'email' => 'Format :attribute tidak valid.',
    'min' => [
        'numeric' => ':attribute minimal :min.',
        'file' => ':attribute minimal :min kilobytes.',
        'string' => ':attribute minimal memiliki :min karakter.',
        'array' => ':attribute minimal memiliki :min item.',
    ],
    'max' => [
        'numeric' => ':attribute maksimal :max.',
        'file' => ':attribute maksimal :max kilobytes.',
        'string' => ':attribute tidak boleh lebih dari :max karakter.',
        'array' => ':attribute tidak boleh lebih dari :max item.',
    ],
    'unique' => ':attribute sudah digunakan.',
    'in' => ':attribute harus salah satu dari: :values.',
    'boolean' => ':attribute harus true atau false.',
    'integer' => ':attribute harus berupa angka.',
    'url' => ':attribute harus berupa URL yang valid.',
    'regex' => ':attribute format tidak valid.',
    'same' => ':attribute dan :other harus sama.',

    'attributes' => [
        // User
        'employeeId' => 'ID karyawan',
        'name' => 'Nama',
        'gender' => 'Jenis Kelamin',
        'birthDate' => 'Tanggal Lahir',
        'phoneNumber' => 'Nomor Telepon',
        'email' => 'Email',
        'roleId' => 'Role',
        'departmentId' => 'Departemen',
        'shiftId' => 'Shift',
        'locationId' => 'Lokasi',
        'password' => 'Kata sandi',
        'whatsapp' => 'WhatsApp',
        'linkedin' => 'LinkedIn',
        'telegram' => 'Telegram',
        'biography' => 'Biografi',
        'status' => 'Status',
        'photo' => 'Foto',
        'confirmPassword' => 'Konfirmasi Kata sandi',
        "latitude" => "Latitude",
        "longitude" => "Longitude",
        "radius" => "Radius",
        "address" => "Alamat",
        "description" => "Deskripsi",
        "default" => "Default",
        "reason" => "Alasan",
        "attachment" => "Lampiran"
    ],


    //custom
    'phoneNumber.regex' => 'Format Nomor Telepon tidak valid. Mohon masukkan 9-20 digit angka.',
    'employeeId.regex' => 'Format ID Karyawan tidak valid. Mohon hanya masukkan angka atau huruf.',
];

