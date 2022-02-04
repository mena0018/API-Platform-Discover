<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\GetAvatarController;
use App\Controller\GetMeController;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get',
        'get_me' => [
            'method' => 'GET',
            'path'   => '/me',
            'controller' => GetMeController::class,
            "security" => "is_granted('ROLE_USER') and object == user",
            'normalization_context' => [
                'groups' => [
                    'get_Me',
                    'get_User'
                ]
            ],
            'pagination_enabled' => false,
        ],
    ],
    itemOperations: [
        'get'=> [
            'normalization_context' => ['groups' => ['get_User']]
        ],
        'put' => [
            'denormalization_context' => ['groups' => ['set_User']],
            "security" => "is_granted('ROLE_USER') and object == user",
            'normalization_context' => ['groups' => ['get_User']]
        ],
        'patch' => [
            'denormalization_context' => ['groups' => ['set_User']],
            "security" => "is_granted('ROLE_USER') and object == user",
            'normalization_context' => ['groups' => ['get_User']]
        ],
        'get_avatar' => [
            'method' => 'get',
            'path' => '/users/{id}/avatar',
            'controller' => GetAvatarController::class,
            "openapi_context" => [
                'content' => [
                    'image/png' => [
                        'schema' => [
                            'type' => 'string',
                            'format' => 'binary',
                        ]
                    ]
                ]
            ],
            'formats:' => [
                'png' => 'image/png',
            ]
        ],
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['get_User', 'set_User'])]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['get_User', 'set_User'])]
    private $login;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    #[Groups(['set_User'])]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['get_User', 'set_User'])]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['get_User', 'set_User'])]
    private $lastname;

    #[ORM\Column(type: 'blob')]
    private $avatar;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['set_User', 'get_Me'])]
    private $mail;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }
}
