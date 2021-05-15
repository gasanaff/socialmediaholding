<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User
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
    private $token;

    /**
     * @ORM\OneToMany(targetEntity=TrackLike::class, mappedBy="_user")
     */
    private $trackLikes;

    /**
     * @ORM\OneToMany(targetEntity=PlaylistLike::class, mappedBy="_user")
     */
    private $playlistLikes;

    public function __construct()
    {
        $this->trackLikes = new ArrayCollection();
        $this->playlistLikes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

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
            $trackLike->setUser($this);
        }

        return $this;
    }

    public function removeTrackLike(TrackLike $trackLike): self
    {
        if ($this->trackLikes->removeElement($trackLike)) {
            // set the owning side to null (unless already changed)
            if ($trackLike->getUser() === $this) {
                $trackLike->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PlaylistLike[]
     */
    public function getPlaylistLikes(): Collection
    {
        return $this->playlistLikes;
    }

    public function addPlaylistLike(PlaylistLike $playlistLike): self
    {
        if (!$this->playlistLikes->contains($playlistLike)) {
            $this->playlistLikes[] = $playlistLike;
            $playlistLike->setUser($this);
        }

        return $this;
    }

    public function removePlaylistLike(PlaylistLike $playlistLike): self
    {
        if ($this->playlistLikes->removeElement($playlistLike)) {
            // set the owning side to null (unless already changed)
            if ($playlistLike->getUser() === $this) {
                $playlistLike->setUser(null);
            }
        }

        return $this;
    }
}
