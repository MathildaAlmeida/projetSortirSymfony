<?php

namespace App\Entity;

use App\Repository\InscriptionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InscriptionsRepository::class)
 */
class Inscriptions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateInscription;

    /**
     * @ORM\ManyToOne(targetEntity=Sorties::class, inversedBy="inscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $noSortie;

    /**
     * @ORM\ManyToOne(targetEntity=Participants::class, inversedBy="inscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $noParticipant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): self
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function getNoSortie(): ?Sorties
    {
        return $this->noSortie;
    }

    public function setNoSortie(?Sorties $noSortie): self
    {
        $this->noSortie = $noSortie;

        return $this;
    }

    public function getNoParticipant(): ?Participants
    {
        return $this->noParticipant;
    }

    public function setNoParticipant(?Participants $noParticipant): self
    {
        $this->noParticipant = $noParticipant;

        return $this;
    }
}
