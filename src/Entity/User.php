<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_PSEUDO', fields: ['pseudo'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[Assert\Length(min: 3, max: 150)]
    #[Assert\NotBlank()]
    #[ORM\Column(length: 150)]
    private ?string $nom = null;

    #[Assert\Length(min: 3, max: 100)]
    #[Assert\NotBlank]
    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[Assert\Length(min: 3, max: 30)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^0[1-9]([-. ]?[0-9]{2}){4}$/', message: 'Le numéro de téléphone doit être au format 0X XX XX XX XX')]
    #[ORM\Column(length: 30)]
    private ?string $telephone = null;

    #[Assert\Length(min: 3, max: 150)]
    #[Assert\NotBlank]
    #[ORM\Column(length: 150)]
    private ?string $email = null;

    #[Assert\Length(min: 3, max: 180)]
    #[Assert\NotBlank]
    #[ORM\Column(length: 180)]
    private ?string $pseudo = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[Assert\Length(min: 8, max: 180)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$/', message: 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial')]
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $administrateur = null;

    #[ORM\Column]
    private ?bool $actif = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $campus = null;

    /**
     * @var Collection<int, Sorties>
     */
    #[ORM\ManyToMany(targetEntity: Sorties::class, inversedBy: 'users')]
    private Collection $sortie;

    /**
     * @var Collection<int, Sorties>
     */
    #[ORM\OneToMany(targetEntity: Sorties::class, mappedBy: 'organisateur')]
    private Collection $sortieOrganise;

    public function __construct()
    {
        $this->sortie = new ArrayCollection();
        $this->sortieOrganise = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }
    public function isAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): static
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): static
    {
        $this->campus = $campus;

        return $this;
    }
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Sorties>
     */
    public function getSortie(): Collection
    {
        return $this->sortie;
    }

    public function addSortie(Sorties $sortie): static
    {
        if (!$this->sortie->contains($sortie)) {
            $this->sortie->add($sortie);
        }

        return $this;
    }

    public function removeSortie(Sorties $sortie): static
    {
        $this->sortie->removeElement($sortie);

        return $this;
    }

    /**
     * @return Collection<int, Sorties>
     */
    public function getSortieOrganise(): Collection
    {
        return $this->sortieOrganise;
    }

    public function addSortieOrganise(Sorties $sortieOrganise): static
    {
        if (!$this->sortieOrganise->contains($sortieOrganise)) {
            $this->sortieOrganise->add($sortieOrganise);
            $sortieOrganise->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSortieOrganise(Sorties $sortieOrganise): static
    {
        if ($this->sortieOrganise->removeElement($sortieOrganise)) {
            // set the owning side to null (unless already changed)
            if ($sortieOrganise->getOrganisateur() === $this) {
                $sortieOrganise->setOrganisateur(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this-> getNom() . ' - '. $this->getPseudo() . ' - ' . $this->getPrenom() . ' (id: ' . $this->getId() . ')';
    }
}
