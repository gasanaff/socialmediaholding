<?php

namespace App\Entity;

use App\Repository\TrackLikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrackLikeRepository::class)
 */
class TrackLike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="trackLikes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $_user;

    /**
     * @ORM\ManyToOne(targetEntity=Track::class, inversedBy="trackLikes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $track;

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

    public function getTrack(): ?Track
    {
        return $this->track;
    }

    public function setTrack(?Track $track): self
    {
        $this->track = $track;

        return $this;
    }
}
