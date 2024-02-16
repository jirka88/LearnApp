<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute musí být přijat.',
    'accepted_if' => ':attribute musí být přijat, pokud :other je :value.',
    'active_url' => ':attribute není platná URL adresa.',
    'after' => ':attribute musí být datum po :date.',
    'after_or_equal' => ':attribute musí být datum po nebo rovno :date.',
    'alpha' => ':attribute musí obsahovat pouze písmena.',
    'alpha_dash' => ':attribute musí obsahovat pouze písmena, čísla, pomlčky a podtržítka.',
    'alpha_num' => ':attribute musí obsahovat pouze písmena a čísla.',
    'array' => ':attribute musí být pole.',
    'ascii' => ':attribute může obsahovat pouze jednobytové alfanumerické znaky a symboly.',
    'before' => ':attribute musí být datum před :date.',
    'before_or_equal' => ':attribute musí být datum před nebo rovno :date.',
    'between' => [
        'array' => ':attribute musí mít mezi :min a :max položkami.',
        'file' => ':attribute musí být mezi :min a :max kilobajty.',
        'numeric' => ':attribute musí být mezi :min a :max.',
        'string' => ':attribute musí být mezi :min a :max znaky.',
    ],
    'boolean' => ':attribute musí být true nebo false.',
    'confirmed' => ':attribute potvrzení se neshoduje.',
    'current_password' => 'Heslo je nesprávné.',
    'date' => ':attribute není platné datum.',
    'date_equals' => ':attribute musí být datum rovno :date.',
    'date_format' => ':attribute neodpovídá formátu :format.',
    'decimal' => ':attribute musí mít :decimal desetinných míst.',
    'declined' => ':attribute musí být zamítnut.',
    'declined_if' => ':attribute musí být zamítnut, pokud :other je :value.',
    'different' => ':attribute a :other se musí lišit.',
    'digits' => ':attribute musí mít :digits číslic.',
    'digits_between' => ':attribute musí být mezi :min a :max číslicemi.',
    'dimensions' => ':attribute má neplatné rozměry obrázku.',
    'distinct' => ':attribute pole má duplicitní hodnotu.',
    'doesnt_end_with' => ':attribute nesmí končit jedním z následujících: :values.',
    'doesnt_start_with' => ':attribute nesmí začínat jedním z následujících: :values.',
    'email' => 'Musí být platná e-mailová adresa.',
    'ends_with' => ':attribute musí končit jedním z následujících: :values.',
    'enum' => 'Vybraný :attribute je neplatný.',
    'exists' => 'Vybraný :attribute je neplatný.',
    'file' => ':attribute musí být soubor.',
    'filled' => ':attribute pole musí mít hodnotu.',
    'gt' => [
        'array' => ':attribute musí mít více než :value položek.',
        'file' => ':attribute musí být větší než :value kilobajtů.',
        'numeric' => ':attribute musí být větší než :value.',
        'string' => ':attribute musí být větší než :value znaků.',
    ],
    'gte' => [
        'array' => ':attribute musí mít :value položek nebo více.',
        'file' => ':attribute musí být větší nebo rovno :value kilobajtů.',
        'numeric' => ':attribute musí být větší nebo rovno :value.',
        'string' => ':attribute musí být větší nebo rovno :value znaků.',
    ],
    'image' => ':attribute musí být obrázek.',
    'in' => 'Vybraný :attribute je neplatný.',
    'in_array' => ':attribute pole neexistuje v :other.',
    'integer' => ':attribute musí být celé číslo.',
    'ip' => ':attribute musí být platná IP adresa.',
    'ipv4' => ':attribute musí být platná IPv4 adresa.',
    'ipv6' => ':attribute musí být platná IPv6 adresa.',
    'json' => ':attribute musí být platný JSON řetězec.',
    'lowercase' => ':attribute musí být malými písmeny.',
    'lt' => [
        'array' => ':attribute musí mít méně než :value položek.',
        'file' => ':attribute musí být menší než :value kilobajtů.',
        'numeric' => ':attribute musí být menší než :value.',
        'string' => ':attribute musí být menší než :value znaků.',
    ],
    'lte' => [
        'array' => ':attribute nesmí mít více než :value položek.',
        'file' => ':attribute musí být menší nebo rovno :value kilobajtů.',
        'numeric' => ':attribute musí být menší nebo rovno :value.',
        'string' => ':attribute musí být menší nebo rovno :value znaků.',
    ],
    'mac_address' => ':attribute musí být platná MAC adresa.',
    'max' => [
        'array' => ':attribute nesmí mít více než :max položek.',
        'file' => ':attribute nesmí být větší než :max kilobajt',
        'numeric' => 'The :attribute must not be greater than :max.',
        'string' => 'The :attribute must not be greater than :max characters.',
    ],
    'max_digits' => 'The :attribute must not have more than :max digits.',
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'array' => 'The :attribute must have at least :min items.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'numeric' => 'The :attribute must be at least :min.',
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'min_digits' => 'The :attribute must have at least :min digits.',
    'missing' => 'The :attribute field must be missing.',
    'missing_if' => 'The :attribute field must be missing when :other is :value.',
    'missing_unless' => 'The :attribute field must be missing unless :other is :value.',
    'missing_with' => 'The :attribute field must be missing when :values is present.',
    'missing_with_all' => 'The :attribute field must be missing when :values are present.',
    'multiple_of' => 'The :attribute must be a multiple of :value.',
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'password' => [
        'letters' => 'The :attribute must contain at least one letter.',
        'mixed' => 'The :attribute must contain at least one uppercase and one lowercase letter.',
        'numbers' => 'The :attribute must contain at least one number.',
        'symbols' => 'The :attribute must contain at least one symbol.',
        'uncompromised' => 'The given :attribute has appeared in a data leak. Please choose a different :attribute.',
    ],
    'present' => 'The :attribute field must be present.',
    'prohibited' => 'The :attribute field is prohibited.',
    'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
    'prohibits' => 'The :attribute field prohibits :other from being present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'The :attribute field is required.',
    'required_array_keys' => 'The :attribute field must contain entries for: :values.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_if_accepted' => 'The :attribute field is required when :other is accepted.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'array' => 'The :attribute must contain :size items.',
        'file' => 'The :attribute must be :size kilobytes.',
        'numeric' => 'The :attribute must be :size.',
        'string' => 'The :attribute must be :size characters.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid timezone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'uppercase' => 'The :attribute must be uppercase.',
    'url' => 'The :attribute must be a valid URL.',
    'ulid' => 'The :attribute must be a valid ULID.',
    'uuid' => 'The :attribute must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'email' => [
            'registered' => 'Email je registrován!',
        ],
        'update' => 'Aktualizace úspěšná',
        'error' => 'Něco se nepovedlo :(',
        'create' => 'Úspěšně vytvořeno!',
        'deleted' => 'Úspěšně smazáno'
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
