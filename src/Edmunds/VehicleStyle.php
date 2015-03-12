<?php
namespace CarRived\Edmunds;

/**
 * Represents a particular style of a specific make/model/year vehicle and
 * includes the engine, transmission, colors, options, trim and squishVin
 * details for that style.
 */
class VehicleStyle extends RemoteObject
{
    /**
     * Gets the make of the vehicle style.
     *
     * @return VehicleMake The make of the vehicle style.
     */
    public function getMake()
    {
        return new VehicleMake($this->client, $this->make);
    }

    /**
     * Gets the model of the vehicle style.
     *
     * @return VehicleModel The model of the vehicle style.
     */
    public function getModel()
    {
        return new VehicleModel($this->client, $this->model);
    }

    /**
     * Gets the model year of the vehicle style.
     *
     * @return VehicleModelYear The model year of the vehicle style.
     */
    public function getModelYear()
    {
        return new VehicleModelYear($this->client, $this->year);
    }

    /**
     * Gets all available photos of the vehicle model of the current style.
     *
     * @return VehiclePhoto[] An array of vehicle photo objects.
     */
    public function getPhotos()
    {
        return $this->client->getStylePhotos($this->id);
    }
}
