<?php
namespace CarRived\Edmunds;

/**
 * A vehicle make is either the name of its manufacturer or, if the manufacturer
 * has more than one operating unit, the name of that unit.
 */
class VehicleMake extends RemoteObject
{
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
