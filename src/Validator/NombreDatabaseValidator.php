<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NombreDatabaseValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {

        if (null === $value || '' === $value) {
            return;
        }

        /* @var App\Validator\CitaDatabase $constraint */
        if (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/', $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }

    }
}
