<?php
namespace MML\Booking\Availability;

use MML\Booking\Exceptions;
use MML\Booking\Interfaces;
use MML\Booking\Factories;

/**
 */
class Fixed extends Base implements Interfaces\Availability
{
    protected $Entity;
    protected $Factory;

    public function __construct(Interfaces\AvailabilityPersistence $Entity, Factories\General $Factory)
    {
        parent::__construct($Entity, $Factory);
        $this->Entity->setFriendlyName('Fixed Period');
    }

    public function setAvailableInterval(Interfaces\Interval $Interval)
    {
        if (strtolower($Interval->getType()) !== 'fixed') {
            throw new Exceptions\Booking("Fixed Availability requires a fixed interval.");
        }
        return parent::setAvailableInterval($Interval);
    }

    /**
     * @todo unit tests
     */
    public function overlaps(Interfaces\Period $Period)
    {
        $IntervalFactory = $this->Factory->getIntervalFactory();
        $Interval = $IntervalFactory->wrap($this->getAvailableInterval());

        $Start = $Interval->getNearestStart($Period->getEnd());
        $End   = $Interval->getNearestEnd($Period->getStart());

        // if start or end of $Period are between our start and end points, return true
        if ( ($Period->getStart() > $Start && $Period->getStart() < $End) ||
            ($Period->getEnd() > $Start && $Period->getEnd() < $End)
        ) {
            return true;
        } else {
            return false;
        }
    }
}