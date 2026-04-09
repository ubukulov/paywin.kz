<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'accepted' => 'Поле :attribute должно быть принято.',
    'accepted_if' => 'Поле :attribute должно быть принято, когда :other равно :value.',
    'active_url' => 'Поле :attribute не является правильным URL.',
    'after' => 'В поле :attribute должна быть дата после :date.',
    'after_or_equal' => 'В поле :attribute должна быть дата после или равная :date.',
    'alpha' => 'Поле :attribute может содержать только буквы.',
    'alpha_dash' => 'Поле :attribute может содержать только буквы, цифры, тире и подчеркивания.',
    'alpha_num' => 'Поле :attribute может содержать только буквы и цифры.',
    'array' => 'Поле :attribute должно быть массивом.',
    'ascii' => 'Поле :attribute должно содержать только однобайтовые буквенно-цифровые символы и символы.',
    'before' => 'В поле :attribute должна быть дата до :date.',
    'before_or_equal' => 'В поле :attribute должна быть дата до или равная :date.',
    'between' => [
        'array' => 'Поле :attribute должно содержать от :min до :max элементов.',
        'file' => 'Размер файла в поле :attribute должен быть от :min до :max килобайт.',
        'numeric' => 'Поле :attribute должно быть между :min и :max.',
        'string' => 'Количество символов в поле :attribute должно быть между :min и :max.',
    ],
    'boolean' => 'Поле :attribute должно быть логического типа.',
    'can' => 'Поле :attribute содержит недопустимое значение.',
    'confirmed' => 'Подтверждение поля :attribute не совпадает.',
    'contains' => 'В поле :attribute отсутствует обязательное значение.',
    'current_password' => 'Неверный пароль.',
    'date' => 'Поле :attribute не является правильной датой.',
    'date_equals' => 'В поле :attribute должна быть дата, равная :date.',
    'date_format' => 'Поле :attribute не соответствует формату :format.',
    'decimal' => 'Поле :attribute должно иметь :decimal знаков после запятой.',
    'declined' => 'Поле :attribute должно быть отклонено.',
    'declined_if' => 'Поле :attribute должно быть отклонено, когда :other равно :value.',
    'different' => 'Поля :attribute и :other должны различаться.',
    'digits' => 'Длина цифрового поля :attribute должна быть :digits.',
    'digits_between' => 'Длина цифрового поля :attribute должна быть между :min и :max.',
    'dimensions' => 'Поле :attribute имеет недопустимые размеры изображения.',
    'distinct' => 'Поле :attribute содержит повторяющееся значение.',
    'doesnt_end_with' => 'Поле :attribute не должно заканчиваться одним из следующих значений: :values.',
    'doesnt_start_with' => 'Поле :attribute не должно начинаться одним из следующих значений: :values.',
    'email' => 'Поле :attribute должно быть правильным адресом электронной почты.',
    'ends_with' => 'Поле :attribute должно заканчиваться одним из следующих значений: :values.',
    'enum' => 'Выбранное значение для :attribute некорректно.',
    'exists' => 'Выбранное значение для :attribute некорректно.',
    'extensions' => 'Поле :attribute должно иметь одно из следующих расширений: :values.',
    'file' => 'Поле :attribute должно быть файлом.',
    'filled' => 'Поле :attribute обязательно для заполнения.',
    'gt' => [
        'array' => 'Поле :attribute должно содержать более :value элементов.',
        'file' => 'Размер файла в поле :attribute должен быть больше :value килобайт.',
        'numeric' => 'Поле :attribute должно быть больше :value.',
        'string' => 'Количество символов в поле :attribute должно быть больше :value.',
    ],
    'gte' => [
        'array' => 'Поле :attribute должно содержать :value элементов или более.',
        'file' => 'Размер файла в поле :attribute должен быть больше или равен :value килобайт.',
        'numeric' => 'Поле :attribute должно быть больше или равен :value.',
        'string' => 'Количество символов в поле :attribute должно быть больше или равен :value.',
    ],
    'hex_color' => 'Поле :attribute должно быть правильным шестнадцатеричным цветом.',
    'image' => 'Поле :attribute должно быть изображением.',
    'in' => 'Выбранное значение для :attribute некорректно.',
    'in_array' => 'Поле :attribute должно существовать в :other.',
    'integer' => 'Поле :attribute должно быть целым числом.',
    'ip' => 'Поле :attribute должно быть правильным IP-адресом.',
    'ipv4' => 'Поле :attribute должно быть правильным IPv4-адресом.',
    'ipv6' => 'Поле :attribute должно быть правильным IPv6-адресом.',
    'json' => 'Поле :attribute должно быть правильной JSON-строкой.',
    'list' => 'Поле :attribute должно быть списком.',
    'lowercase' => 'Поле :attribute должно быть в нижнем регистре.',
    'lt' => [
        'array' => 'Поле :attribute должно содержать менее :value элементов.',
        'file' => 'Размер файла в поле :attribute должен быть меньше :value килобайт.',
        'numeric' => 'Поле :attribute должно быть меньше :value.',
        'string' => 'Количество символов в поле :attribute должно быть меньше :value.',
    ],
    'lte' => [
        'array' => 'Поле :attribute должно содержать не более :value элементов.',
        'file' => 'Размер файла в поле :attribute должен быть меньше или равен :value килобайт.',
        'numeric' => 'Поле :attribute должно быть меньше или равен :value.',
        'string' => 'Количество символов в поле :attribute должно быть меньше или равен :value.',
    ],
    'mac_address' => 'Поле :attribute должно быть правильным MAC-адресом.',
    'max' => [
        'array' => 'Поле :attribute не должно содержать больше :max элементов.',
        'file' => 'Размер файла в поле :attribute не должен превышать :max килобайт.',
        'numeric' => 'Поле :attribute не должно быть больше :max.',
        'string' => 'Количество символов в поле :attribute не должно превышать :max.',
    ],
    'max_digits' => 'Поле :attribute должно содержать не более :max цифр.',
    'mimes' => 'Поле :attribute должно быть файлом одного из следующих типов: :values.',
    'mimetypes' => 'Поле :attribute должно быть файлом одного из следующих типов: :values.',
    'min' => [
        'array' => 'Поле :attribute должно содержать как минимум :min элементов.',
        'file' => 'Размер файла в поле :attribute должен быть не менее :min килобайт.',
        'numeric' => 'Поле :attribute должно быть не менее :min.',
        'string' => 'Количество символов в поле :attribute должно быть не менее :min.',
    ],
    'min_digits' => 'Поле :attribute должно содержать как минимум :min цифр.',
    'missing' => 'Поле :attribute должно отсутствовать.',
    'missing_if' => 'Поле :attribute должно отсутствовать, когда :other равно :value.',
    'missing_unless' => 'Поле :attribute должно отсутствовать, если :other не равно :value.',
    'missing_with' => 'Поле :attribute должно отсутствовать, если указано :values.',
    'missing_with_all' => 'Поле :attribute должно отсутствовать, если указаны :values.',
    'multiple_of' => 'Поле :attribute должно быть кратным :value.',
    'not_in' => 'Выбранное значение для :attribute некорректно.',
    'not_regex' => 'Формат поля :attribute некорректен.',
    'numeric' => 'Поле :attribute должно быть числом.',
    'password' => [
        'letters' => 'Поле :attribute должно содержать хотя бы одну букву.',
        'mixed' => 'Поле :attribute должно содержать хотя бы одну заглавную и одну строчную букву.',
        'numbers' => 'Поле :attribute должно содержать хотя бы одно число.',
        'symbols' => 'Поле :attribute должно содержать хотя бы один символ.',
        'uncompromised' => 'Указанное значение :attribute скомпрометировано в результате утечки данных. Пожалуйста, выберите другое значение.',
    ],
    'present' => 'Поле :attribute должно быть указано.',
    'present_if' => 'Поле :attribute должно быть указано, когда :other равно :value.',
    'present_unless' => 'Поле :attribute должно быть указано, если :other не равно :value.',
    'present_with' => 'Поле :attribute должно быть указано, когда указано :values.',
    'present_with_all' => 'Поле :attribute должно быть указано, когда указаны :values.',
    'prohibited' => 'Поле :attribute запрещено.',
    'prohibited_if' => 'Поле :attribute запрещено, когда :other равно :value.',
    'prohibited_if_accepted' => 'Поле :attribute запрещено, когда :other принято.',
    'prohibited_if_declined' => 'Поле :attribute запрещено, когда :other отклонено.',
    'prohibited_unless' => 'Поле :attribute запрещено, если :other не находится в :values.',
    'prohibits' => 'Поле :attribute запрещает присутствие :other.',
    'regex' => 'Формат поля :attribute некорректен.',
    'required' => 'Поле :attribute обязательно для заполнения.',
    'required_array_keys' => 'Поле :attribute должно содержать записи для: :values.',
    'required_if' => 'Поле :attribute обязательно для заполнения, когда :other равно :value.',
    'required_if_accepted' => 'Поле :attribute обязательно для заполнения, когда :other принято.',
    'required_if_declined' => 'Поле :attribute обязательно для заполнения, когда :other отклонено.',
    'required_unless' => 'Поле :attribute обязательно для заполнения, если :other не находится в :values.',
    'required_with' => 'Поле :attribute обязательно для заполнения, когда указано :values.',
    'required_with_all' => 'Поле :attribute обязательно для заполнения, когда указаны :values.',
    'required_without' => 'Поле :attribute обязательно для заполнения, когда :values не указано.',
    'required_without_all' => 'Поле :attribute обязательно для заполнения, когда ни одно из :values не указано.',
    'same' => 'Поля :attribute и :other должны совпадать.',
    'size' => [
        'array' => 'Поле :attribute должно содержать :size элементов.',
        'file' => 'Размер файла в поле :attribute должен быть :size килобайт.',
        'numeric' => 'Поле :attribute должно быть равным :size.',
        'string' => 'Количество символов в поле :attribute должно быть равным :size.',
    ],
    'starts_with' => 'Поле :attribute должно начинаться с одного из следующих значений: :values.',
    'string' => 'Поле :attribute должно быть строкой.',
    'timezone' => 'Поле :attribute должно быть правильным часовым поясом.',
    'unique' => 'Такое значение поля :attribute уже занято.',
    'uploaded' => 'Загрузка поля :attribute не удалась.',
    'uppercase' => 'Поле :attribute должно быть в верхнем регистре.',
    'url' => 'Поле :attribute должно быть правильным URL-адресом.',
    'ulid' => 'Поле :attribute должно быть правильным ULID.',
    'uuid' => 'Поле :attribute должно быть правильным UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'email' => [
            'unique' => 'Этот адрес электронной почты уже зарегистрирован.',
        ],
        'phone' => [
            'unique' => 'Этот номер телефона уже используется.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'email' => 'E-mail',
        'phone' => 'Номер телефона',
        'password' => 'Пароль',
    ],

];
