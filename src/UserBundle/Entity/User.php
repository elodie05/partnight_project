<?php

namespace UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use EventBundle\Entity\Comment;
use EventBundle\Entity\Event;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="signInDate", type="datetime")
     */
    private $signInDate;
    
    /**
     * @ORM\Column(name="lastName", type="string")
     */
    private $lastName;
    
    /**
     * @ORM\Column(name="firstName", type="string")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $apiKey;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EventBundle\Entity\Event", mappedBy="user")
     */
    private $events;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EventBundle\Entity\Comment", mappedBy="user")
     */
    private $comments;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="User")
     */
    private $friends;

    public function __construct()
    {
        parent::__construct ();
        $this->signInDate = new \DateTime();
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
        $this->friends = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set signInDate
     *
     * @param \DateTime $signInDate
     *
     * @return User
     */
    public function setSignInDate($signInDate)
    {
        $this->signInDate = $signInDate;

        return $this;
    }

    /**
     * Get signInDate
     *
     * @return \DateTime
     */
    public function getSignInDate()
    {
        return $this->signInDate;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Add events
     *
     * @param Event $events
     * @return User
     */
    public function addEvent(Event $events)
    {
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param Event $events
     */
    public function removeEvent(Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param Comment $comment
     */
    public function addComment(Comment $comment)
    {
        $this->comments->add($comment);
    }

    /**
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * @param User $user
     */
    public function addFriend(User $user)
    {
        $this->friends->add($user);
    }

    /**
     * @param User $user
     */
    public function removeFriend(User $user)
    {
        $this->friends->removeElement($user);
    }

    /**
     * @return ArrayCollection
     */
    public function getFriends()
    {
        return $this->friends;
    }
}
