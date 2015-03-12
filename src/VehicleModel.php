<?php
namespace Edmunds\SDK;

/**
 * A vehicle model is specific vehicle brand identified by a name or number
 * (and which is usually further classified by trim or style level).
 *
 * @property int    $id       The Edmunds ID for the car make/model
 * @property object $make     The car make details (i.e. id, name, niceName)
 * @property string $name     The name of this car model
 * @property string $niceName URL-friendly car model name
 * @property array  $states   The state of this car model (i.e. NEW, USED, FUTURE)
 * @property array  $years    Array of model year details, including styles
 *
 * @see http://developer.edmunds.com/api-documentation/vehicle/spec_model/v2/
 */
class VehicleModel extends RemoteObject
{
    /**
     * Gets the make of the model.
     *
     * @return VehicleMake
     */
    public function getMake()
    {
        return new VehicleMake($this->client, $this->make);
    }

    /**
     * Gets all model years for a make and model.
     *
     * @param  string             $state     Filters models by state.
     * @param  string             $submodel  Filters models by submodel types.
     * @param  string             $category  Filters models by category.
     * @return VehicleModelYear[]
     *
     * @see VehicleApiClient::getModelYears()
     */
    public function getYears($state = null, $submodel = null, $category = null)
    {
        // check if years have been cached
        if (isset($this->years)) {
            return array_map(function ($object) {
                // pass in a reference to this make and model
                $object->make = $this->make;
                $object->model = new \stdClass();
                $object->model->id = $this->id;
                $object->model->name = $this->name;
                $object->model->niceName = $this->niceName;

                return new VehicleModelYear($this->client, $object);
            }, $this->years);
        }

        return $this->client->getModelYears($this->make->niceName, $this->niceName, $state, $submodel, $category);
    }

    /**
     * Gets a vehicle model year by make, model name, and year.
     *
     * @param  int              $year      The model year.
     * @return VehicleModelYear
     *
     * @see VehicleApiClient::getModelYear()
     */
    public function getYear($year)
    {
        // check if years have been cached
        if (isset($this->years)) {
            $object = $this->findObjectBy($this->years, 'year', (int)$year);

            if ($object === null) {
                throw new ApiException("The model year does not exist.", 404);
            }

            // pass in a reference to this make and model
            $object->make = $this->make;
            $object->model = new \stdClass();
            $object->model->id = $this->id;
            $object->model->name = $this->name;
            $object->model->niceName = $this->niceName;

            return new VehicleModelYear($this->client, $object);
        }

        return $this->client->getModelYear($this->make->niceName, $this->niceName, $year);
    }
}
