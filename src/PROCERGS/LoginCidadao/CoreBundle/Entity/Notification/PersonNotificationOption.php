<?php

namespace PROCERGS\LoginCidadao\CoreBundle\Entity\Notification;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PersonOption
 *
 * @ORM\Table(name="person_notification_option")
 * @ORM\Entity
 */
class PersonNotificationOption
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * Determines if this category of notification should be sent by email
     *
     * @var boolean
     * @ORM\Column(name="send_email", type="boolean")
     */
    private $sendEmail;

    /**
     * Determines if this category of notification should be sent via push
     * notification
     *
     * @var boolean
     * @ORM\Column(name="send_push", type="boolean")
     */
    private $sendPush;

    /**
     * @var Person
     *
     * @ORM\ManyToOne(targetEntity="PROCERGS\LoginCidadao\CoreBundle\Entity\Person", inversedBy="notificationOptions")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;

    /**
     * @var Category
     *
     * @ORM\OneToOne(targetEntity="Category", inversedBy="personNotificationOption")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($var)
    {
        $this->id = $var;
        return $this;
    }    

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return PersonNotificationOption
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    public function setCategory($var)
    {
        $this->category = $var;
        return $this;
    }
    
    public function getCategory()
    {
        return $this->category;
    }    
    
    public function setPerson($var) 
    {
        $this->person = $var;
        return $this;
    }
    
    public function getPerson()
    {
        return $this->person;
    }    
    
    public function setSendEmail($var)
    {
        $this->sendEmail = $var;
        return $this;
    }
    
    public function getSendEmail()
    {
        return $this->sendEmail;
    }    
    
    public function setSendPush($var)
    {
        $this->sendPush = $var;
        return $this;
    }
    
    public function getSendPush()
    {
        return $this->sendPush;
    }    

}