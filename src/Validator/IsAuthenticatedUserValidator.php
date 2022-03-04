<?php

namespace App\Validator;

use App\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security\Core\Security;

class IsAuthenticatedUserValidator extends ConstraintValidator
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function validate(/** @var User $value */ $value, Constraint $constraint)
    {
        /* @var $constraint IsAuthenticatedUser */

        if (null === $value || '' === $value) {
            return;
        }

        if ($this->security->getUser() !== $value)
        {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ user }}', $value->getLogin())
                ->addViolation();
        }
    }
}
