<?php

namespace Model;

DEFINED ('ROOTPATH') OR exit ('Access Denied');

 /**
 * {CLASSNAME} class
 */

class {CLASSNAME}
{
    use Model;

    protected $table = '{table}';
    protected $primaryKey = 'id';
    protected $loginUniqueColumn = 'email';

    protected $allowedColumns = [
        'fullname',
        'phone',
        'email',
        'password',
    ];

    protected $onInsertValidationRules = [
        'fullname' => [
            'alpha_space',
            'required',
        ],

        'password' => [
            'not_less_than_8_chars',
            'required',
        ],

        'email' => [
            'unique',
            'email',
            'required',
        ],
    ];

    protected $onUpdateValidationRules = [
        'fullname' => [
            'alpha_space',
            'required',
        ],

        'password' => [
            'not_less_than_8_chars',
            'required',
        ],

        'email' => [
            'unique',
            'email',
            'required',
        ],
    ];
}