<?php
namespace Edmunds\SDK;

/**
 * A vehicle make is either the name of its manufacturer or, if the manufacturer
 * has more than one operating unit, the name of that unit.
 *
 * @property int    $id       The Edmunds ID for the car make/brand
 * @property array  $models   List of models belonging to this car make (see below)
 * @property string $name     The name of this car make
 * @property string $niceName URL-friendly car make/brand name
 *
 * @see http://developer.edmunds.com/api-documentation/vehicle/spec_make/v2/
 */
class VehicleMake extends RemoteObject
{
    /**
     * Gets a list of models belonging to this car make.
     *
     * @param  string         $state    Filters models by state.
     * @param  int            $year     Filters models by available years.
     * @param  string         $submodel Filters models by submodel types.
     * @param  string         $category Filters models by category.
     * @return VehicleModel[]
     *
     * @see VehicleApiClient::getModels()
     */
    public function getModels($state = null, $year = null, $submodel = null, $category = null)
    {
        // check if models have been cached
        if (isset($this->models)) {
            return array_map(function ($object) {
                // pass in a reference to this make
                $object->make = new \stdClass();
                $object->make->id = $this->id;
                $object->make->name = $this->name;
                $object->make->niceName = $this->niceName;

                return new VehicleModel($this->client, $object);
            }, $this->models);
        }

        return $this->client->getModels($this->niceName, $state, $year, $submodel, $category);
    }

    /**
     * Gets a vehicle model by name and make.
     *
     * @param  string       $modelName The name of the model.
     * @return VehicleModel
     *
     * @see VehicleApiClient::getModel()
     */
    public function getModel($modelName)
    {
        // check if models have been cached
        if (isset($this->models)) {
            $object = $this->findObjectBy($this->models, 'niceName', $modelName);

            if ($object === null) {
                throw new ApiException("The model does not exist.", 404);
            }

            // pass in a reference to this make
            $object->make = new \stdClass();
            $object->make->id = $this->id;
            $object->make->name = $this->name;
            $object->make->niceName = $this->niceName;

            return new VehicleModel($this->client, $object);
        }

        return $this->client->getModel($this->niceName, $modelName);
    }
}
