<?php

namespace App\Entity;

use App\Repository\HeroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HeroRepository::class)]
class Hero
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?bool $visible = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $age = null;

    #[ORM\OneToMany(mappedBy: 'hero', targetEntity: Company::class, cascade: ["persist"])]
    private Collection $companies;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $wikiLink = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    public function __construct()
    {
        $this->companies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(?bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return Collection<int, Company>
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    public function addCompany(Company $company): self
    {
        if (!$this->companies->contains($company)) {
            $this->companies->add($company);
            $company->setHero($this);
        }

        return $this;
    }

    public function removeCompany(Company $company): self
    {
        if ($this->companies->removeElement($company)) {
            // set the owning side to null (unless already changed)
            if ($company->getHero() === $this) {
                $company->setHero(null);
            }
        }

        return $this;
    }

    public function getWikiLink(): ?string
    {
        return $this->wikiLink;
    }

    public function setWikiLink(?string $wikiLink): self
    {
        $wikiLink = str_replace("https://", "", $wikiLink);
        $this->wikiLink = $wikiLink;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function uploadPhoto($file, $targetDirectory)
    {
        $originalName = $file->getClientOriginalName();
        $fileName = $originalName . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move(
                $targetDirectory,
                $fileName
            );
        } catch (FileException $e) {
            dump($e);
        }

        $this->setPhoto($targetDirectory . $fileName);

    }
}
