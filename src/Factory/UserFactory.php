<?php

namespace App\Factory;

use App\Entity\User;
use App\Repository\UserRepository;
use Jdenticon\Identicon;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;



/**
 * @extends ModelFactory<User>
 *
 * @method static User|Proxy createOne(array $attributes = [])
 * @method static User[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static User|Proxy find(object|array|mixed $criteria)
 * @method static User|Proxy findOrCreate(array $attributes)
 * @method static User|Proxy first(string $sortedField = 'id')
 * @method static User|Proxy last(string $sortedField = 'id')
 * @method static User|Proxy random(array $attributes = [])
 * @method static User|Proxy randomOrCreate(array $attributes = [])
 * @method static User[]|Proxy[] all()
 * @method static User[]|Proxy[] findBy(array $attributes)
 * @method static User[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static User[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static UserRepository|RepositoryProxy repository()
 * @method User|Proxy create(array|callable $attributes = [])
 */
final class UserFactory extends ModelFactory
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->passwordHasher = $passwordHasher;
    }

    protected function getDefaults(): array
    {
        $lastName = self::faker()->lastName();
        $firstName = self::faker()->firstName();
        $name = $lastName.$firstName;

        return [
            'login'     => self::faker()->numerify('user###'),
            'roles'     => [],
            'password'  => "test",
            'firstname' => $firstName,
            'lastname'  => $lastName,
            'avatar'    => self::createAvatar($name),
            'mail'      => self::faker()->email(),
        ];
    }

    protected function initialize(): self
    {
        return $this
            ->afterInstantiate(function(User $user) {
                $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
            })
            ;
    }

    protected static function getClass(): string
    {
        return User::class;
    }

    protected static function createAvatar(string $value)
    {
        $icon = new Identicon();
        $icon->setSize(50);
        $icon->setValue($value);
        return fopen($icon->getImageDataUri(), 'r');
    }
}
