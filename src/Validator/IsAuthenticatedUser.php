<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;


#[Attribute]
class IsAuthenticatedUser extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'The value "{{ value }}" is not valid.';
}
