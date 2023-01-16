<?php

namespace App\Entity;

use App\Repository\TokenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TokenRepository::class)]
#[ORM\Table(name: 'tokens')]
class Token
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_rel = null;

    #[ORM\Column(length: 255)]
    private ?string $token_key = null;

    #[ORM\Column]
    private ?int $token_untill = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserRel(): ?User
    {
        return $this->user_rel;
    }

    public function setUserRel(User $user_rel): self
    {
        $this->user_rel = $user_rel;

        return $this;
    }

    public function getTokenKey(): ?string
    {
        return $this->token_key;
    }

    public function setTokenKey(string $token_key): self
    {
        $this->token_key = $token_key;

        return $this;
    }

    public function getTokenUntill(): ?int
    {
        return $this->token_untill;
    }

    public function setTokenUntill(int $token_untill): self
    {
        $this->token_untill = $token_untill;

        return $this;
    }
}
