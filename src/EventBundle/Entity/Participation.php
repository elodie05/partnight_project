<?php

namespace EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;

/**
 * Event
 *
 * @ORM\Table(name="participation")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\ParticipationRepository")
 */
class Participation
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
     * @ORM\ManyToOne(targetEntity="EventBundle\Entity\Event", inversedBy="participations")
     * @ORM\JoinColumn
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn
     */
    private $user;

    /**
     * @ORM\Column(name="response", type="boolean", nullable=true)
     */
    private $response;

    /**
     * @ORM\Column(name="sleep", type="boolean", nullable=true)
     */
    private $sleep;

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
     * Set event
     *
     * @param Event $event
     * @return Participation
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;
        $this->event->addParticipation($this);

        return $this;
    }

    /**
     * Get event
     *
     * @return \EventBundle\Entity\Event 
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Participation
     */
    public function setUser(User $user = null)
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
     * Set response
     *
     * @param boolean $response
     * @return Participation
     */
    public function setResponse($response = null)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get response
     *
     * @return boolean
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param $sleep
     */
    public function setSleep($sleep)
    {
        $this->sleep = $sleep;
    }

    /**
     * @return mixed
     */
    public function getSleep()
    {
        return $this->sleep;
    }
}
