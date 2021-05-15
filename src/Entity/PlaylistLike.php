<?php

namespace App\Entity;

use App\Repository\PlaylistLikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlaylistLikeRepository::class)
 */
class PlaylistLike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="playlistLikes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $_user;

    /**
     * @ORM\ManyToOne(targetEntity=Playlist::class, inversedBy="playlistLikes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $playlist;

    /**
     * @ORM\ManyToOne(targetEntity=Track::class)
     */
    private $byTrack;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->_user;
    }

    public function setUser(?User $_user): self
    {
        $this->_user = $_user;

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

    public function getByTrack(): ?Track
    {
        return $this->byTrack;
    }

    public function setByTrack(?Track $byTrack): self
    {
        $this->byTrack = $byTrack;

        return $this;
    }
}
