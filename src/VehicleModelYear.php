<?php
namespace Edmunds\SDK;

/**
 * A vehicle model year for a vehicle model is the calendar year designation
 * assigned by the manufacturer to the annual version of that model.
 *
 * @property int    $id     The Edmunds ID for the model year
 * @property object $make   Car make details (id, name and niceName)
 * @property object $model  Car model details (id, name and niceName)
 * @property array  $states The state of this model/year (e.g. NEW, USED, or FUTURE)
 * @property array  $styles The styles for the model year
 * @property int    $year   The model year
 *
 * @see http://developer.edmunds.com/api-documentation/vehicle/spec_model_year/v2/
 */
class VehicleModelYear extends RemoteObject
{
    /**
     * Gets the make of the model year.
     *
     * @return VehicleMake
     */
    public function getMake()
    {
        return new VehicleMake($this->client, $this->make);
    }

    /**
     * Gets the model of the model year.
     *
     * @return VehicleModel
     */
    public function getModel()
    {
        return new VehicleModel($this->client, $this->model);
    }

    /**
     * Gets a list of styles for the model year.
     *
     * @param  string         $state     Filters models by state.
     * @param  string         $submodel  Filters models by submodel types.
     * @param  string         $category  Filters models by category.
     * @return VehicleStyle[]
     *
     * @see VehicleApiClient::getModelStyles()
     */
    public function getStyles($state = null, $submodel = null, $category = null)
    {
        // check if years have been cached
        if (isset($this->styles)) {
            return array_map(function ($object) {
                // pass in a reference to this make, model and year
                $object->make = $this->make;
                $object->model = $this->model;
                $object->year = new \stdClass();
                $object->year->id = $this->id;
                $object->year->year = $this->year;

                return new VehicleStyle($this->client, $object);
            }, $this->styles);
        }

        return $this->client->getModelStyles($this->make->niceName, $this->model->niceName, $this->year, $state, $submodel, $category);
    }
}
