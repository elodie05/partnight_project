<?php

namespace EventBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\EventRepository")
 * @Assert\Callback(methods={"validateDate"})
 */
class Event
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(name="name", type="string")
     */
    private $name;
    
    /**
     * @ORM\Column(name="location", type="string")
     */
    private $location;

    /**
     * @ORM\Column(name="sleep_available", type="integer")
     */
    private $sleepAvailable;
    
    /**
     * @ORM\Column(name="description", type="string")
     */
    private $description;
    
    
    /**
     * @ORM\Column(name="startDate", type="datetime")
     */
    private $startDate;
    
    /**
     * @ORM\Column(name="endDate", type="datetime")
     */
    private $endDate;
    
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn

     */
    private $user;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EventBundle\Entity\Requirement", mappedBy="event")
     */
    private $requirements;
    
    

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
     *
     * @return Event
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
     * Set location
     *
     * @param string $location
     *
     * @return Event
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set sleepAvailable
     *
     * @param integer $sleepAvailable
     *
     * @return Event
     */
    public function setSleepAvailable($sleepAvailable)
    {
        $this->sleepAvailable = $sleepAvailable;

        return $this;
    }

    /**
     * Get sleepAvailable
     *
     * @return integer
     */
    public function getSleepAvailable()
    {
        return $this->sleepAvailable;
    }


    /**
     * Set description
     *
     * @param string $description
     *
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }



    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     * @return Event
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->requirements = new ArrayCollection();
    }

    /**
     * @param Requirement $requirement
     */
    public function addRequirement(Requirement $requirement)
    {
        $this->requirements->add($requirement);
    }

    /**
     * @param Requirement $requirement
     */
    public function removeRequirement(Requirement $requirement)
    {
        $this->requirements->removeElement($requirement);
    }

    /**
     * @return ArrayCollection
     */
    public function getRequirements()
    {
        return $this->requirements;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Event
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Event
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
    
    public function validateDate(ExecutionContextInterface $context)
    {
    	$start = $this->getStartDate();
    	$end = $this->getEndDate();
  		
    	if($end < $start){
    		$context->addViolationAt(
    				'endDate',
    				'End date must be > start date',
    				array(),
    				null
    				);
    	}
    
    	
    }
}
