<?php

namespace App\Entity;

use App\Repository\MasterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MasterRepository::class)
 */
class Master
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Slave::class, mappedBy="master")
     */
    private $slaves;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function __construct()
    {
        $this->slaves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Slave[]
     */
    public function getSlaves(): Collection
    {
        return $this->slaves;
    }

    public function addSlave(Slave $slave): self
    {
        if (!$this->slaves->contains($slave)) {
            $this->slaves[] = $slave;
            $slave->setMaster($this);
        }

        return $this;
    }

    public function removeSlave(Slave $slave): self
    {
        if ($this->slaves->contains($slave)) {
            $this->slaves->removeElement($slave);
            // set the owning side to null (unless already changed)
            if ($slave->getMaster() === $this) {
                $slave->setMaster(null);
            }
        }

        return $this;
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
    public function __toString() {
        return $this->name;
    }
}
