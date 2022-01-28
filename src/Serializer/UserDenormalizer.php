<?php
declare(strict_types=1);

namespace App\Serialization\Denormalizer;

use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;

abstract class UserDenormalizer implements ContextAwareDenormalizerInterface,
    DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    private const ALREADY_CALLED = 'USER_DENORMALIZER_ALREADY_CALLED';
    private $passwordHasher;
    private $security;


}