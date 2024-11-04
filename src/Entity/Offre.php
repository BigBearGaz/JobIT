<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: OffreRepository::class)]
class Offre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_publication = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_modification = null;

    #[ORM\ManyToOne(inversedBy: 'offres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeContrat $type_contrat = null;

    #[ORM\Column(length: 255)]
    private ?string $Lieu = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $duree = null;

    #[ORM\ManyToOne(inversedBy: 'offres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\Column(length: 255)]
    private ?string $logo = null;
    #[ORM\ManyToOne(inversedBy: 'offres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Auteur = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'tara')]
    private Collection $users;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $competences = null;

    #[ORM\ManyToOne(inversedBy: 'offres')]
    private ?Temps $temps = null;

    #[ORM\ManyToOne(inversedBy: 'offres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Distanciel $distanciel = null;

    #[ORM\Column(length: 255)]
    private ?string $salaire = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

/*     #[ORM\Column]
    private ?int $auteur = null; */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->date_publication;
    }

    public function setDatePublication(\DateTimeInterface $date_publication): static
    {
        $this->date_publication = $date_publication;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->date_modification;
    }

    public function setDateModification(\DateTimeInterface $date_modification): static
    {
        $this->date_modification = $date_modification;

        return $this;
    }

    public function getTypeContrat(): ?TypeContrat
    {
        return $this->type_contrat;
    }

    public function setTypeContrat(?TypeContrat $type_contrat): static
    {
        $this->type_contrat = $type_contrat;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->Lieu;
    }

    public function setLieu(string $Lieu): static
    {
        $this->Lieu = $Lieu;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(?string $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

/*     public function getAuteur(): ?int
    {
        return $this->auteur;
    }

    public function setAuteur(int $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    } */

public function getAuteur(): ?User
{
    return $this->Auteur;
}

public function setAuteur(?User $Auteur): static
{
    $this->Auteur = $Auteur;

    return $this;
}

/**
 * @return Collection<int, User>
 */
public function getUsers(): Collection
{
    return $this->users;
}

public function addUser(User $user): static
{
    if (!$this->users->contains($user)) {
        $this->users->add($user);
        $user->addTara($this);
    }

    return $this;
}

public function removeUser(User $user): static
{
    if ($this->users->removeElement($user)) {
        $user->removeTara($this);
    }

    return $this;
}

public function getCompetences(): ?string
{
    return $this->competences;
}

public function setCompetences(?string $competences): static
{
    $this->competences = $competences;

    return $this;
}

public function getTemps(): ?Temps
{
    return $this->temps;
}

public function setTemps(?Temps $temps): static
{
    $this->temps = $temps;

    return $this;
}

public function getDistanciel(): ?Distanciel
{
    return $this->distanciel;
}

public function setDistanciel(?Distanciel $distanciel): static
{
    $this->distanciel = $distanciel;

    return $this;
}

public function getSalaire(): ?string
{
    return $this->salaire;
}

public function setSalaire(string $salaire): static
{
    $this->salaire = $salaire;

    return $this;
}

}
