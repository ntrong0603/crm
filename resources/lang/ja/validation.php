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

    'accepted'        => 'この:attributeをご承認お願いします。',
    'active_url'      => 'この:attributeがURLのフォーマットではなくてURLをご入力願いします。',
    'after'           => 'この:attributeが:dateの以降でお願いします。',
    'after_or_equal'  => 'この:attributeが:dateの以降でお願いします。',
    'alpha'           => 'この:attributeが英文字のみでお願いします。',
    'alpha_dash'      => 'この:attributeが英文字、英数字と"-"のみ許可になります。',
    'alpha_num'       => 'この:attributeが英文字と英数字のみ許可になります。',
    'array'           => 'この:attributeは配列でお願いします。',
    'before'          => 'この:attributeが:dateの以前でお願いします。',
    'before_or_equal' => 'この:attributeが:dateの以前でお願いします。',
    'between'         => [
        'numeric' => 'この:attributeの値が:minと:maxの間にご入力お願いします。',
        'file'    => 'この:attributeの容量は:minから:maxKbまでお願いします。',
        'string'  => 'この:attributeの桁数は:minから:maxまでお願いします。',
        'array'   => 'この:attributeのアイテム数は:minから:maxまでお願いします。',
    ],
    'boolean'        => 'この:attributeの値がTRUEとFALSEのみ許可です。',
    'confirmed'      => 'The :attribute confirmation does not match.',
    'date'           => 'この:attributeが不正な日付です。',
    'date_equals'    => 'The :attribute must be a date equal to :date.',
    'date_format'    => 'この:attributeのフォーマットは:formatでお願いします。',
    'different'      => 'この:attributeと:otherは違う値でお願いします。',
    'digits'         => 'この:attributeの桁数は:digitsでお願いします。',
    'digits_between' => 'この:attributeの桁数は:minから:maxまでお願いします。',
    'dimensions'     => 'The :attribute has invalid image dimensions.',
    'distinct'       => 'この:attributeは重複になっています。',
    'email'          => 'この:attributeがメールアドレスでお願いします。',
    'ends_with'      => 'The :attribute must end with one of the following: :values.',
    'exists'         => 'ご選択された:attributeがよくないです。',
    'file'           => 'この:attributeがファイルでお願いします。',
    'filled'         => 'The :attribute field must have a value.',
    'gt'             => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file'    => 'The :attribute must be greater than :value kilobytes.',
        'string'  => 'The :attribute must be greater than :value characters.',
        'array'   => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file'    => 'The :attribute must be greater than or equal :value kilobytes.',
        'string'  => 'The :attribute must be greater than or equal :value characters.',
        'array'   => 'The :attribute must have :value items or more.',
    ],
    'image'    => 'この:attributeは画像でお願いします。',
    'in'       => 'ご選択された:attributeがよくないです。',
    'in_array' => 'この:attributeが:otherに存在していないです。',
    'integer'  => 'この:attributeの値はINTでお願いします。',
    'ip'       => 'この:attributeはIPアドレスでお願いします。',
    'ipv4'     => 'この:attributeはIPv4のアドレスでお願いします。',
    'ipv6'     => 'この:attributeはIPv6のアドレスでお願いします。',
    'json'     => 'この:attributeはJSONのフォーマットでお願いします。',
    'lt'       => [
        'numeric' => 'The :attribute must be less than :value.',
        'file'    => 'The :attribute must be less than :value kilobytes.',
        'string'  => 'The :attribute must be less than :value characters.',
        'array'   => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file'    => 'The :attribute must be less than or equal :value kilobytes.',
        'string'  => 'The :attribute must be less than or equal :value characters.',
        'array'   => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'     => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min'       => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'ご選択された:attributeがよくないです。',
    'not_regex'            => 'The :attribute format is invalid.',
    'numeric'              => 'この:attributeが英数字でお願いします。',
    'password'             => 'The password is incorrect.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'この:attributeのフィルドは必須です。',
    'required_if'          => ':otherが:valueの場合、この:attributeが必須です。',
    'required_unless'      => ':otherの値が:valuesにある場合、この:attributeが必須です。',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values are present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'この:attributeと:otherが一致しないといけないです。',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'starts_with'  => 'The :attribute must start with one of the following: :values.',
    'string'       => 'この:attributeが配列でお願いします。',
    'timezone'     => 'The :attribute must be a valid zone.',
    'unique'       => 'この:attributeが既に存在しています。',
    'uploaded'     => 'この:attributeがアップロードできなかった。ご確認お願いします。',
    'url'          => 'この:attributeはエラーです。ご確認お願いします。',
    'uuid'         => 'The :attribute must be a valid UUID.',
    'phone_number' => 'この:attributeはエラーです。ご確認お願いします。',
    'zip_code'     => 'この:attributeはエラーです。ご確認お願いします。',
    'fax_number'   => 'この:attributeはエラーです。ご確認お願いします。',

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
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
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
