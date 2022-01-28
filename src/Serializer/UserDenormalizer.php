<?php
declare(strict_types=1);

namespace App\Serialization\Denormalizer;

use App\Entity\User;
use ReflectionException;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;


/**
 * Classe permettant à l'utilisateur de pouvoir changer son mot de passe.
 */
abstract class UserDenormalizer implements ContextAwareDenormalizerInterface,
    DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    private const ALREADY_CALLED = 'USER_DENORMALIZER_ALREADY_CALLED';
    private $passwordHasher;
    private $security;

    public function __construct(UserPasswordHasherInterface $passwordHasher, Security $security)
    {
        $this->passwordHasher = $passwordHasher;
        $this->security = $security;
    }

    /**
     * @param $data
     * @param $type          /type de ressource cible
     * @param null $format   /format d'origine
     * @param array $context /ContextAwareDenormalizerInterface
     * @return bool          /booleen indiquant si la classe doit transformer les données.
     *
     * Retourne vrai si la clé self::ALREADY_CALLED n'est pas définie dans le contexte
     * et que le type ciblé est la classe User sinon retourne faux.
     * @throws ReflectionException
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        $reflexionClass = new \ReflectionClass($type);
        $alreadyCalled  = $context[self::ALREADY_CALLED] ?? false;

        return $reflexionClass->implementsInterface(User::class) && $alreadyCalled === false;
    }

}