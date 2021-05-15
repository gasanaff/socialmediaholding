<?php

namespace App\Entity;

use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlaylistRepository::class)
 */
class Playlist
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
     * @ORM\OneToMany(targetEntity=Track::class, mappedBy="playlist")
     */
    private $tracks;

    /**
     * @ORM\OneToMany(targetEntity=PlaylistLike::class, mappedBy="playlist")
     */
    private $playlistLikes;

    public function __construct()
    {
        $this->tracks = new ArrayCollection();
        $this->playlistLikes = new ArrayCollection();
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

    /**
     * @return Collection|Track[]
     */
    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function addTrack(Track $track): self
    {
        if (!$this->tracks->contains($track)) {
            $this->tracks[] = $track;
            $track->setPlaylist($this);
        }

        return $this;
    }

    public function removeTrack(Track $track): self
    {
        if ($this->tracks->removeElement($track)) {
            // set the owning side to null (unless already changed)
            if ($track->getPlaylist() === $this) {
                $track->setPlaylist(null);
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
            $playlistLike->setPlaylist($this);
        }

        return $this;
    }

    public function removePlaylistLike(PlaylistLike $playlistLike): self
    {
        if ($this->playlistLikes->removeElement($playlistLike)) {
            // set the owning side to null (unless already changed)
            if ($playlistLike->getPlaylist() === $this) {
                $playlistLike->setPlaylist(null);
            }
        }

        return $this;
    }
}
