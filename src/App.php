<?php
namespace MML\Booking;

/**
 * This is the front-controller for the bookings plugin. Most major functionality can be accessed through here for
 * ease of use.
 *
 * @todo  Could I make this nicer? This will become a God object in very short order! Maybe force devs to use the
 * factory themselves? Or implement a __call to the factory?
 */
class App
{
    protected $Factory;

    /**
     * @param Factory $Factory
     */
    public function __construct(Factories\General $Factory)
    {
        $this->Factory = $Factory;
    }

    // @todo search resources. eg get all starting with 1_ etc

    public function getResource($name)
    {
        $Doctrine = $this->Factory->getDoctrine();
        return $Doctrine->getRepository('MML\\Booking\\Models\\Resource')->findOneBy(array('name' => $name));
    }

    public function checkAvailability(Interfaces\ResourcePersistence $Resource, Interfaces\Period $Period)
    {
        $Availability = $this->Factory->getReservationAvailability();
        return $Availability->check($Resource, $Period);
    }

    public function getPeriodFor(Interfaces\ResourcePersistence $Resource, $periodName)
    {
        $Locator = $this->Factory->getPeriodFactory();
        return $Locator->getFor($Resource, $periodName);
    }

    public function createReservation(Interfaces\ResourcePersistence $Resource, Interfaces\Period $Period, $qty = 1)
    {
        $Availability = $this->Factory->getReservationAvailability();

        if (!$Availability->check($Resource, $Period, $qty)) {
            throw new Exceptions\Unavailable("{$Resource->getFriendlyName()} does not have enough availability for the selected period");
        }

        $Doctrine = $this->Factory->getDoctrine();

        $reservations = array();
        for ($i=0; $i < $qty; $i++) {
            $Reservation = $this->Factory->getEmptyReservation();
            $Reservation->setupFrom($Resource, $Period);
            $Doctrine->persist($Reservation->getEntity());
            $reservations[] = $Reservation;
        }

        $Doctrine->flush();

        return ($qty === 1) ? $reservations[0] : $reservations;
    }

    public function createBlockReservation(Interfaces\ResourcePersistence $Resource, Interfaces\Period $Period, Interfaces\Interval $Interval)
    {
        // @todo
    }

    public function getReservations(Interfaces\ResourcePersistence $Resource, \DateTime $Start, \DateTime $End)
    {
        $Finder = $this->Factory->getReservationFinder();
        return $Finder->resourceBetween($Resource, $Start, $End);
    }

    public function getInterval($identifier)
    {
        $Provider = $this->Factory->getIntervalFactory();
        return $Provider->get($identifier);
    }
}
