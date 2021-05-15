<?php

namespace App\Entity;

use App\Repository\TrackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrackRepository::class)
 */
class Track
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Playlist::class, inversedBy="tracks")
     */
    private $playlist;

    /**
     * @ORM\OneToMany(targetEntity=TrackLike::class, mappedBy="track")
     */
    private $trackLikes;

    public function __construct()
    {
        $this->trackLikes = new ArrayCollection();
    }

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

    public function getPlaylist(): ?Playlist
    {
        return $this->playlist;
    }

    public function setPlaylist(?Playlist $playlist): self
    {
        $this->playlist = $playlist;

        return $this;
    }

    /**
     * @return Collection|TrackLike[]
     */
    public function getTrackLikes(): Collection
    {
        return $this->trackLikes;
    }

    public function addTrackLike(TrackLike $trackLike): self
    {
        if (!$this->trackLikes->contains($trackLike)) {
            $this->trackLikes[] = $trackLike;
            $trackLike->setTrack($this);
        }

        return $this;
    }

    public function removeTrackLike(TrackLike $trackLike): self
    {
        if ($this->trackLikes->removeElement($trackLike)) {
            // set the owning side to null (unless already changed)
            if ($trackLike->getTrack() === $this) {
                $trackLike->setTrack(null);
            }
        }

        return $this;
    }
}
