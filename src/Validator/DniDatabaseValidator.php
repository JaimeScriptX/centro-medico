<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DniDatabaseValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        
        if (null === $value || '' === $value) {
            return;
        }

        if (!preg_match('/^\d{8}[A-Z]$/', $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
