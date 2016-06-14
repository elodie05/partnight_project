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
     * @ORM\Column(name="street", type="string")
     */
    private $street;
    
    /**
     * @ORM\Column(name="zipcode", type="string")
     */
    private $zipcode;
    
    /**
     * @ORM\Column(name="city", type="string")
     */
    private $city;
    
    /**
     * @ORM\Column(name="lat", type="string", nullable=true)
     */
    private $lat;
    
    /**
     * @ORM\Column(name="lng", type="string", nullable=true)
     */
    private $lng;

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
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="events")
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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Participation", mappedBy="event")
     */
    private $participations;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Document", mappedBy="event")
     */
    private $documents;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="event")
     */
    private $comments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->requirements = new ArrayCollection();
        $this->participations = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

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
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->street.' '.$this->zipcode.' '.$this->city;
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

    /**
     * Set street
     *
     * @param string $street
     * @return Event
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     * @return Event
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string 
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Event
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set lat
     *
     * @param string $lat
     * @return Event
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param string $lng
     * @return Event
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return string 
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * @param Participation $participation
     */
    public function addParticipation(Participation $participation)
    {
        $this->participations->add($participation);
    }

    /**
     * @param Participation $participation
     */
    public function removeParticipation(Participation $participation) {
        $this->participations->removeElement($participation);
    }

    /**
     * @return ArrayCollection
     */
    public function getParticipations()
    {
        return $this->participations;
    }

    /**
     * @param Document $document
     */
    public function addDocument(Document $document)
    {
        $this->documents->add($document);
    }

    /**
     * @param Document $document
     */
    public function removeDocument(Document $document)
    {
        $this->documents->removeElement($document);
    }

    /**
     * @return ArrayCollection
     */
    public function getDocuments()
    {
        return $this->documents;
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
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }
}