<?php
namespace MML\Booking\Interfaces;

/**
 * @todo document (ideally before bumping composer version ya lazy toerag)
 */
interface ReservationPersistence
{
    public function getId();

    public function setStart(\DateTime $Date);
    public function getStart();

    public function setEnd(\DateTime $Date);
    public function getEnd();

    public function getResource();
    public function setResource(ResourcePersistence $Resource);

    public function getCreated();
    public function getModified();

    /**
     * Shorthand method to avoid having to hydrate all properties yo'sel'
     *
     * @param  ResourcePersistence  $Resource The Resource to reserve
     * @param  Period               $Period   The period to reseerve for
     * @return $this
     */
    public function hydrateFrom(ResourcePersistence $Resource, Period $Period);
}