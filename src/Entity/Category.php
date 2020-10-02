<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * Validation : le nom de la catégorie ne doit pas déjà exister en bdd
 * @UniqueEntity(fields={"name"}, message="cette catégorie existe déjà")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     * //Validation : la valeur ne doit pas etre vide
     * @Assert\NotBlank(message="Le nom est obligatoire")
     * //Validation : nombre de caractere
     * @Assert\Length(max="20", min="3",
     *                                   maxMessage="Le nom ne doit pas faire plus de {{ limit }} caracteres",
     *                                  minMessage="Le nom ne doit pas faire plus de {{ limit }} caracteres")
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
