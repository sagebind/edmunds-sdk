<?php
namespace Edmunds\SDK;

/**
 * Represents a particular style of a specific make/model/year vehicle and
 * includes the engine, transmission, colors, options, trim and squishVin
 * details for that style.
 *
 * @property int    $id       The style ID
 * @property string $name     The style name
 * @property array  $make     The car make details (id, name and nicename)
 * @property array  $model    The car model details (id, name and nicename)
 * @property array  $year     The car model year ID and four-digit year
 * @property array  $submodel Car submode details
 * @property string $trim     The car trim
 *
 * @see http://developer.edmunds.com/api-documentation/vehicle/spec_style/v2/
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
