<?php
namespace MML\Booking\Availability;

use MML\Booking\Interfaces;
use MML\Booking\Factories;

/**
 */
class Base
{
    protected $Entity;
    protected $Factory;

    public function __construct(Interfaces\AvailabilityPersistence $Entity, Factories\General $Factory)
    {
        $this->Entity = $Entity;
        $this->Factory = $Factory;
    }
    public function getEntity()
    {
        return $this->Entity;
    }

    public function getBookingInterval($name)
    {
        $IntervalFactory = $this->Factory->getIntervalFactory();
        $Entity = $this->Entity->getBookingInterval($ame);
        if ($Entity) {
            return $IntervalFactory->wrap($Entity);
        } else {
            return null;
        }
    }
    public function hasBookingInterval(Interfaces\Interval $Interval)
    {
        return $this->Entity->hasBookingInterval($Interval->getName());
    }
    public function addBookingInterval(Interfaces\Interval $Interval)
    {
        return $this->Entity->addBookingInterval($Interval->getEntity());
    }
    public function setAvailableInterval(Interfaces\Interval $Interval)
    {
        return $this->Entity->setAvailableInterval($Interval->getEntity());
    }
    public function getAvailableInterval()
    {
        return $this->Entity->getAvailableInterval();
    }

    public function getAvailable()
    {
        return $this->Entity->getAvailable();
    }
    public function setAvailable($boolean = true)
    {
        return $this->Entity->setAvailable($boolean);
    }

    public function setFriendlyName($name)
    {
        return $this->Entity->setFriendlyName($name);
    }
    public function getFriendlyName()
    {
        return $this->Entity->getFriendlyName();
    }

    public function setAffectedQuantity($qty)
    {
        $this->Entity->setAffectedQuantity($qty);
    }
    public function getAffectedQuantity()
    {
        return $this->Entity->getAffectedQuantity();
    }

    public function destroy()
    {
        $Doctrine = $this->Factory->getDoctrine();

        $Interval = $this->Entity->getAvailableInterval();
        if ($this->intervalHasOneRelation($Interval)) {
            $Doctrine->remove($Interval);
        }

        foreach ($this->Entity->allBookingIntervals() as $Interval) {
            if ($this->intervalHasOneRelation($Interval)) {
                $Doctrine->remove($Interval);
            }
        }
        $Doctrine->remove($this->Entity);
    }

    protected function intervalHasOneRelation(Interfaces\IntervalPersistence $Interval)
    {
        // @todo lets write some tests here! This seems shaky.
        $count = 0;
        if ($Interval->getAvailabilityWindow()) {
            ++$count;
        }
        foreach ($Interval->allBookingAvailability() as $ignored) {
            ++$count;
        }

        return ($count < 2);
    }
}
