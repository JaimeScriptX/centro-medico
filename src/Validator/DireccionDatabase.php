<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class DireccionDatabase extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'La direccion "{{ string }}" no es valida.';

    public function validatedBy()
    {
        return DireccionDatabaseValidator::class;
    }
}
