<?php
declare(strict_types=1);

namespace App\Tests\TestCases;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

abstract class ApiPlatformTestCase extends WebTestCase
{
    use ResetDatabase;
    use Factories;

    public const ENTITY = 'ENTITY';
    public const COLLECTION = 'COLLECTION';

    protected static $client;

    /**
     * This method is called before each test.
     */
    public function setUp(): void
    {
        static::ensureKernelShutdown(); // creating factories boots the kernel; shutdown before creating the client
        self::$client = self::createClient();
    }

    /**
     * Normalize list of properties to be JSON-LD valid.
     *
     * @param array $properties properties to normalize
     *
     * @return array JSON-LD normalized properties
     */
    protected static function getJSONLDProperties(array $properties): array
    {
        return array_merge($properties, ['@id', '@type']);
    }

    /**
     * Perform a jsonld request.
     *  $parameters['contentType']: mime type of the request
     *  $parameters['headers']: additional request headers
     *  $parameters['parameters']: request parameters
     *  $parameters['content']: request body
     *
     * @param string $method     request method
     * @param string $url        request url
     * @param array  $parameters request parameters
     *
     * @return Crawler the crawler
     */
    protected static function jsonld_request(string $method, string $url, array $parameters = []): Crawler
    {
        $contentType = isset($parameters['contentType']) ? ['CONTENT_TYPE' => $parameters['contentType']] : [];
        $headers = array_merge(['HTTP_ACCEPT' => 'application/ld+json'], $contentType, $parameters['headers'] ?? []);

        return self::$client->request($method, $url, $parameters['parameters'] ?? [], [], $headers, $parameters['content'] ?? null);
    }

    /**
     * Retrieves the last JSON data requested and asserts that it was a successful request and a valid collection or entity.
     *
     * @param string      $type   expected type
     * @param string      $entity entity name
     * @param string|null $iri  expected IRI of the data, requested route by default
     *
     * @return array decoded JSON data
     */
    protected static function lastJsonResponseWithAsserts(string $type, string $entity, ?string $iri = null): array
    {
        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $iri = $iri ?? self::$client->getRequest()->getPathInfo();
        $json = json_decode(self::$client->getResponse()->getContent(), true);
        switch ($type) {
            case self::ENTITY:
                self::assertJsonIsAnEntity($iri, $entity, $json);
                break;
            case self::COLLECTION:
                self::assertJsonIsACollection($iri, $entity, $json);
                break;
        }

        return $json;
    }

    /**
     * Asserts that the JSON is a collection.
     *
     * @param string $iri  expected IRI
     * @param string $entity expected entity
     * @param array  $json   decoded JSON data
     */
    protected static function assertJsonIsACollection(string $iri, string $entity, array $json): void
    {
        self::assertJsonProperties($json, [
            '@context' => '/api/contexts/'.$entity,
            '@id' => $iri,
            '@type' => 'hydra:Collection',
        ]);
    }

    /**
     * Asserts that the JSON is an entity.
     *
     * @param string $iri  expected route
     * @param string $entity expected entity
     * @param array  $json   decoded JSON data
     */
    protected static function assertJsonIsAnEntity(string $iri, string $entity, array $json): void
    {
        self::assertJsonProperties($json, [
            '@context' => '/api/contexts/'.$entity,
            '@id' => $iri,
            '@type' => $entity,
        ]);
    }

    /**
     * Asserts that JSON is an item.
     *
     * @param array $json       data
     * @param array $properties expected properties
     * @param array $values     expected values
     */
    protected static function assertJsonIsAnItem(array $json, array $properties, array $values = []): void
    {
        $properties = self::getJSONLDProperties($properties);
        foreach ($properties as $property) {
            self::assertArrayHasKey($property, $json);
            if (isset($values[$property])) {
                self::assertSame($values[$property], $json[$property]);
            }
        }
        foreach ($json as $property => $value) {
            if ('@context' !== $property) {
                self::assertContains($property, $properties, 'JSON contain property ('.$property.') not expected ['.join(', ', $properties).']');
            }
        }
    }

    /**
     * Asserts that JSON is paginated.
     *
     * @param array $json        JSON data
     * @param int   $lastPage    expected last page
     * @param int   $currentPage expected current page
     */
    protected static function assertJsonIsPaginated(array $json, int $lastPage, int $currentPage = 1): void
    {
        $route = self::$client->getRequest()->getPathInfo();
        $asserts = [
            '@id' => $route.'?page='.$currentPage,
            '@type' => 'hydra:PartialCollectionView',
            'hydra:first' => $route.'?page=1',
            'hydra:last' => $route.'?page='.$lastPage,
        ];
        if ($currentPage < $lastPage) {
            $asserts['hydra:next'] = $route.'?page='.($currentPage + 1);
        }
        if ($currentPage > 1) {
            $asserts['hydra:previous'] = $route.'?page='.($currentPage - 1);
        }
        self::assertJsonProperties($json['hydra:view'], $asserts);
    }

    /**
     * Asserts that JSON has properties.
     *
     * @param array $json    JSON data
     * @param array $asserts expected properties and values as an associative array
     * @param bool  $only    JSON data should only contain the expected values and no others
     */
    protected static function assertJsonProperties(array $json, array $asserts, bool $only = false): void
    {
        foreach ($asserts as $property => $value) {
            self::assertArrayHasKey($property, $json);
            self::assertSame($value, $json[$property]);
        }
        if ($only) {
            foreach ($json as $property => $value) {
                self::assertArrayHasKey($property, $asserts, 'JSON contain property ('.$property.') not expected ['.join(', ', $asserts).']');
            }
        }
    }
}
