<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
 */
class Produit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomProduit;

    /**
     * @ORM\Column(type="integer")
     */
    private $qteRestante;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Activite", mappedBy="produit", orphanRemoval=true)
     */
    private $activites;

    public function __construct()
    {
        $this->activites = new ArrayCollection();
        $this->setQteRestante(0);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduit(): ?string
    {
        return $this->nomProduit;
    }

    public function setNomProduit(string $nomProduit): self
    {
        $this->nomProduit = $nomProduit;

        return $this;
    }

    public function getQteRestante(): ?int
    {
        return $this->qteRestante;
    }

    public function setQteRestante(int $qteRestante): self
    {
        $this->qteRestante = $qteRestante;

        return $this;
    }

    /**
     * @return Collection|Activite[]
     */
    public function getActivites(): Collection
    {
        return $this->activites;
    }

    public function addActivite(Activite $activite): self
    {
        if (!$this->activites->contains($activite)) {
            $this->activites[] = $activite;
            $activite->setProduit($this);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): self
    {
        if ($this->activites->contains($activite)) {
            $this->activites->removeElement($activite);
            // set the owning side to null (unless already changed)
            if ($activite->getProduit() === $this) {
                $activite->setProduit(null);
            }
        }

        return $this;
    }

    public function addQteRestante(int $qte){
        $this->setQteRestante($this->getQteRestante()+$qte);
    }

    public function subQteRestante(int $qte){
        $this->setQteRestante($this->getQteRestante()-$qte);
    }
}
