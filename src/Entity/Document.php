<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[ORM\Table(name: 'documents')]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_rel = null;

    #[ORM\Column(length: 255)]
    private ?string $document_key = null;

    #[ORM\Column(length: 25)]
    private ?string $document_status = null;

    #[ORM\Column]
    private ?string $document_payload = null;

    #[ORM\Column]
    private ?int $createAt = null;

    #[ORM\Column]
    private ?int $modifyAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserRel(): ?User
    {
        return $this->user_rel;
    }

    public function setUserRel(?User $user_rel): self
    {
        $this->user_rel = $user_rel;

        return $this;
    }

    public function getDocumentKey(): ?string
    {
        return $this->document_key;
    }

    public function setDocumentKey(string $document_key): self
    {
        $this->document_key = $document_key;

        return $this;
    }

    public function getDocumentStatus(): ?string
    {
        return $this->document_status;
    }

    public function setDocumentStatus(string $document_status): self
    {
        $this->document_status = $document_status;

        return $this;
    }

    public function getDocumentPayload(): ?string
    {
        return $this->document_payload;
    }

    public function setDocumentPayload(string $document_payload): self
    {
        $this->document_payload = $document_payload;

        return $this;
    }

    public function getCreateAt(): ?int
    {
        return $this->createAt;
    }

    public function setCreateAt(int $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getModifyAt(): ?int
    {
        return $this->modifyAt;
    }

    public function setModifyAt(int $modifyAt): self
    {
        $this->modifyAt = $modifyAt;

        return $this;
    }
}
