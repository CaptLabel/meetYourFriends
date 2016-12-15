<?php

namespace CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="CommonBundle\Repository\UserRepository")
 *
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    public $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    public $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    public $password;

    /**
     * @var string
     *
     * @ORM\Column(name="key_secure", type="string", length=255, unique=true)
     */
    public $key_secure;

    /**
     * @var string
     *
     * @ORM\Column(name="key_avatar", type="string", length=255, unique=true)
     */
    public $key_avatar;

    /**
     * @ORM\ManyToMany(targetEntity="CommonBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */

    public $friends;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getKeySecure()
    {
        return $this->key_secure;
    }

    /**
     * @param string $key_secure
     */
    public function setKeySecure($key_secure)
    {
        $this->key_secure = $key_secure;
    }

    /**
     * @return mixed
     */
    public function getFriends()
    {
        return $this->friends;
    }

    /**
     * @param mixed $friends
     */
    public function setFriends($friends)
    {
        $this->friends = $friends;
    }
    public function addFriend(User $user)
    {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau
        $this->friends[] = $user;
        return $this;
    }

    public function removeFriend(User $user)
    {
        // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la catégorie en argument
        $this->friends->removeElement($user);
    }

    /**
     * @return string
     */
    public function getKeyAvatar()
    {
        return $this->key_avatar;
    }

    /**
     * @param string $key_avatar
     */
    public function setKeyAvatar($key_avatar)
    {
        $this->key_avatar = $key_avatar;
    }
}
