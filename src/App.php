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
     * Messy paradigm. Uses DI to get hold of a factory for ease of testing, but makes it optional so consuming
     * applications needn't worry. If you want to over-ride our config settings, pass in your key-value pairs in  the
     * settings array. If you need super-fine-grained control, pass in a factory.
     *
     * @param array   $settings
     * @param Factory $Factory
     */
    public function __construct(array $settings = null, Factories\General $Factory = null)
    {
        $this->Factory = is_null($Factory) ? new Factories\General($settings) : $Factory;
    }

    public function getResource($name)
    {
        $Doctrine = $this->Factory->getDoctrine();
        return $Doctrine->getRepository('MML\\Booking\\Models\\Resource')->findOneBy(array('name' => $name));
    }

    public function checkAvailability(Models\Resource $Resource, Interfaces\Period $Period)
    {
        $Availability = $this->Factory->getReservationAvailability();
        return $Availability->check($Resource, $Period);
    }

    public function getPeriodFor(Models\Resource $Resource, $Periodname)
    {
        $Locator = $this->Factory->getPeriodFactory();
        return $Locator->getFor($Resource, $Periodname);
    }

    public function createReservation(Models\Resource $Resource, Interfaces\Period $Period, $qty = 1)
    {
        $Availability = $this->Factory->getReservationAvailability();

        if (!$Availability->check($Resource, $Period, $qty)) {
            throw new Exceptions\Unavailable("{$Resource->getFriendlyName()} does not have enough availability for the selected period");
        }

        $Doctrine = $this->Factory->getDoctrine();

        for ($i=0; $i < $qty; $i++) {
            $Reservation = $this->Factory->getEmptyReservation();
            $Reservation->hydrateFrom($Resource, $Period);
            $Doctrine->persist($Reservation);
        }

        $Doctrine->flush();
    }

    public function createBlockReservation(Models\Resource $Resource, Interfaces\Period $Period, Interfaces\Interval $Interval)
    {
        // @todo
    }

    public function getReservations(Models\Resource $Resource, \DateTime $Start, \DateTime $End)
    {
        // @todo
    }

    public function getInterval($identifier)
    {
        $Provider = $this->Factory->getIntervalFactory();
        return $Provider->get($identifier);
    }
}
