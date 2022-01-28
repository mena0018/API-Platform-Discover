<?php
declare(strict_types=1);

namespace App\Serialization\Denormalizer;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;

/**
 * Classe permettant à l'utilisateur de pouvoir changer son mot de passe.
 */
class UserDenormalizer implements ContextAwareDenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    private const ALREADY_CALLED = 'USER_DENORMALIZER_ALREADY_CALLED';
    private UserPasswordHasherInterface $passwordHasher;
    private Security $security;

    public function __construct(UserPasswordHasherInterface $passwordHasher, Security $security)
    {
        $this->passwordHasher = $passwordHasher;
        $this->security = $security;
    }

    /**
     * Retourne vrai si la clé self::ALREADY_CALLED n'est pas définie dans le contexte
     * et que le type ciblé est la classe User sinon retourne faux.
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        $alreadyCalled = isset($context[self::ALREADY_CALLED]);
        return $type == User::class && !$alreadyCalled;
    }

    /**
     * Ajoutera la valeur true à la clé self::ALREADY_CALLED du context. Ensuite, si l'utilisateur a transmis
     * un mot de passe dans les données, elle utilisera les services pour hacher le mot de passe (méthode hashPassword()
     * du passwordHasher, dont le premier paramètre est l'utilisateur connecté récupéré à l'aide de la méthode getUser()
     * et Security) avant d'invoquer à nouveau la dénormalisation depuis la propriété denormalizer de l'instance courante.
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        //Ajoute la valeur true à la clé self::ALREADY_CALLED du context.
        $context[self::ALREADY_CALLED] = true;

        /** @var $user User */
        $user = $this->security->getUser();

        if (isset($data["password"])) {
            $data["password"] = $this->passwordHasher->hashPassword($user, $data["password"]);
        }

        return $this->denormalizer->denormalize($data, $type, $format, $context);
    }
}