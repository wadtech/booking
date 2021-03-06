<?php
namespace MML\Booking\Factories;

use MML\Booking;
use MML\Booking\Exceptions;
use MML\Booking\Interfaces;
use MML\Booking\Intervals;
use MML\Booking\Factories;
use MML\Booking\Models;

class Availability
{
    protected $IntervalFactory;
    protected $GeneralFactory;

    public function __construct(Interval $IntervalFactory, Factories\General $GeneralFactory)
    {
        $this->IntervalFactory = $IntervalFactory;
        $this->GeneralFactory  = $GeneralFactory;
    }

    public function getNew($name)
    {
        $Entity = new Models\Availability();
        $Entity->setType($name);

        $Doctrine = $this->GeneralFactory->getDoctrine();
        $Doctrine->persist($Entity);

        return $this->createWrapper($name, $Entity);
    }

    public function wrap(Interfaces\AvailabilityPersistence $Availability)
    {
        $type = $Availability->getType();
        return $this->createWrapper($type, $Availability);
    }

    protected function createWrapper($name, Interfaces\AvailabilityPersistence $Entity)
    {
        $class= "MML\\Booking\\Availability\\{$name}";

        if (class_exists($class)) {
            return new $class($Entity, $this->GeneralFactory);
        } else {
            throw new Exceptions\Booking("Factories\\Availability::getFrom Unknown Availability type $name requested");
        }
    }
}
