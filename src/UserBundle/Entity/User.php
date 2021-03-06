<?php

namespace UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{

	public function __construct()
	{
		parent::__construct ();
		$this->signInDate = new \DateTime();
		$this->events = new \Doctrine\Common\Collections\ArrayCollection();
        $this->friends = new ArrayCollection();
		// your own logic
	}
	
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="signInDate", type="datetime")
     * 
     */
    private $signInDate;
    
    /**
     * @ORM\Column(name="lastName", type="string")
     *
     */
    private $lastName;
    
    /**
     * @ORM\Column(name="firstName", type="string")
     *
     */
    private $firstName;
    
    /**
     * @ORM\ManyToMany(targetEntity="EventBundle\Entity\Event", mappedBy="users")
     *
     */
    private $events;

    /**
     * @var
     *
     * @ORM\ManyToMany(targetEntity="User")
     */
    private $friends;


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
     * Add events
     *
     * @param \UserBundle\Entity\Event $events
     * @return User
     */
    public function addEvent(\EventBundle\Entity\Event $events)
    {
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \UserBundle\Entity\Event $events
     */
    public function removeEvent(\EventBundle\Entity\Event $events)
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
