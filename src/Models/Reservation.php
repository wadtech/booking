<?php
namespace MML\Booking\Models;

/**
 * Holds data for an existing reservation
 *
 * DOCTRINE CONFIG
 *
 * @Entity
 * @HasLifecycleCallbacks
 * @Table(name="booking_reservations")
 */
class Reservation
{
    /**
     * @id @Column(type="integer")
     * @GeneratedValue
    */
    private $id;
    /** @Column(type="datetime") */
    private $start;
    /** @Column(type="datetime") */
    private $end;
    /** @Column(type="datetime") */
    private $created;
    /** @Column(type="datetime") */
    private $modified;
    /** @ManyToOne(targetEntity="MML\Booking\Models\Resource", inversedBy="Reservations") */
    private $Resource;

    public function getId()
    {
        return $this->id;
    }
    public function getStart()
    {
        return $this->start;
    }
    public function getEnd()
    {
        return $this->end;
    }
    public function getCreated()
    {
        return $this->created;
    }
    public function getModified()
    {
        return $this->modified;
    }
    public function getResource()
    {
        return $this->Resource;
    }


    public function setStart(\DateTime $Date)
    {
        $this->start = $Date;
    }
    public function setEnd(\DateTime $Date)
    {
        $this->end = $Date;
    }

    /**
     *  @PrePersist
     */
    public function prePersist()
    {
        $this->created = $this->created ? $this->created : new \DateTime();
        $this->modified = new \DateTime();
    }
}
