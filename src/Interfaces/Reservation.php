<?php
namespace MML\Booking\Interfaces;

use MML\Booking\Factories;

/**
 * @todo document (ideally before bumping composer version ya lazy toerag)
 */
interface Reservation
{
    public function __construct(ReservationPersistence $Entity, Factories\General $GeneralFactory);

    public function setStart(\DateTime $Date);
    public function getStart();

    public function setEnd(\DateTime $Date);
    public function getEnd();

    public function getResource();
    /**
     * @param Resource $Resource [description]
     */
    public function setResource(Resource $Resource);

    public function getCreated();
    public function getModified();
    public function setupFrom(Resource $Resource, Period $Period);

    public function addMeta($name, $value);
    public function getMeta($name, $default = null);
    public function allMeta();

    public function getId();
    public function getEntity();
}
