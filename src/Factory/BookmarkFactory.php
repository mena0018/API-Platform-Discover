<?php

namespace App\Factory;

use App\Entity\Bookmark;
use App\Repository\BookmarkRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\Factory;
use function Zenstruck\Foundry\faker;

/**
 * @extends ModelFactory<Bookmark>
 *
 * @method static Bookmark|Proxy createOne(array $attributes = [])
 * @method static Bookmark[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Bookmark|Proxy find(object|array|mixed $criteria)
 * @method static Bookmark|Proxy findOrCreate(array $attributes)
 * @method static Bookmark|Proxy first(string $sortedField = 'id')
 * @method static Bookmark|Proxy last(string $sortedField = 'id')
 * @method static Bookmark|Proxy random(array $attributes = [])
 * @method static Bookmark|Proxy randomOrCreate(array $attributes = [])
 * @method static Bookmark[]|Proxy[] all()
 * @method static Bookmark[]|Proxy[] findBy(array $attributes)
 * @method static Bookmark[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Bookmark[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static BookmarkRepository|RepositoryProxy repository()
 * @method Bookmark|Proxy create(array|callable $attributes = [])
 */
final class BookmarkFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            'name'         => self::faker()->company(),
            'description'  => self::faker()->paragraph(),
            'creationDate' => self::faker()->dateTimeBetween('-2 years'),
            'isPublic'     => self::faker()->boolean(),
            'url'          => self::faker()->url(),
            'rateAverage'  => 0,
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Bookmark $bookmark): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Bookmark::class;
    }
}
