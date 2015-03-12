<?php
namespace CarRived\Edmunds;

/**
 * A client for the Edmunds Vehicle API.
 */
class VehicleApiClient extends ApiClient
{
    const STATE_NEW = 'new';
    const STATE_USED = 'used';
    const STATE_FUTURE = 'future';

    /**
     * Gets all vehicle makes.
     *
     * @param  string        $state Filters makes by state.
     * @param  int           $year  Filters makes with models for a given year.
     * @return VehicleMake[]
     *
     * @see http://developer.edmunds.com/api-documentation/vehicle/spec_make/v2/01_list_of_makes/api-description.html
     */
    public function getMakes($state = null, $year = null)
    {
        $params = compact('state', 'year') + [
            'view' => 'full'
        ];

        $response = $this->makeCall('/api/vehicle/v2/makes', $params);

        // return vehicle make objects for each make found
        return array_map(function ($make) {
            return new VehicleMake($this, $make);
        }, $response->makes);
    }

    /**
     * Gets a vehicle make by name.
     *
     * @param  string      $name The name of the make.
     * @return VehicleMake
     *
     * @see http://developer.edmunds.com/api-documentation/vehicle/spec_make/v2/02_make_details/api-description.html
     */
    public function getMake($name, $state = null, $year = null)
    {
        $url = sprintf('/api/vehicle/v2/%s', $name);
        $params = compact('state', 'year') + [
            'view' => 'full'
        ];
        $response = $this->makeCall($url, $params);
        return new VehicleMake($this, $response);
    }

    /**
     * Gets all models made by a given vehicle make.
     *
     * @param  string         $makeName The name of the make.
     * @param  string         $state    Filters models by state.
     * @param  int            $year     Filters models by available years.
     * @param  string         $submodel Filters models by submodel types.
     * @param  string         $category Filters models by category.
     * @return VehicleModel[]
     *
     * @see http://developer.edmunds.com/api-documentation/vehicle/spec_model/v2/01_list_of_models/api-description.html
     */
    public function getModels($makeName, $state = null, $year = null, $submodel = null, $category = null)
    {
        $url = sprintf('/api/vehicle/v2/%s/models', $makeName);
        $params = compact('state', 'year', 'submodel', 'category') + [
            'view' => 'full'
        ];

        $response = $this->makeCall($url, $params);

        // return vehicle model objects for each model
        return array_map(function ($object) {
            return new VehicleModel($this, $object);
        }, $response->models);
    }

    /**
     * Gets a vehicle model by name and make.
     *
     * @param  string       $makeName  The name of the make.
     * @param  string       $modelName The name of the model.
     * @return VehicleModel
     *
     * @see http://developer.edmunds.com/api-documentation/vehicle/spec_model/v2/02_model_details/api-description.html
     */
    public function getModel($makeName, $modelName, $state = null, $year = null, $submodel = null, $category = null)
    {
        $url = sprintf('/api/vehicle/v2/%s/%s', $makeName, $modelName);
        $params = compact('state', 'year', 'submodel', 'category') + [
            'view' => 'full'
        ];

        $response = $this->makeCall($url, $params);
        return new VehicleModel($this, $response);
    }

    /**
     * Gets all model years for a make and model.
     *
     * @param  string             $makeName  The name of the make.
     * @param  string             $modelName The name of the model.
     * @param  string             $state     Filters models by state.
     * @param  string             $submodel  Filters models by submodel types.
     * @param  string             $category  Filters models by category.
     * @return VehicleModelYear[]
     *
     * @see http://developer.edmunds.com/api-documentation/vehicle/spec_model_year/v2/03_list_of_years/api-description.html
     */
    public function getModelYears($makeName, $modelName, $state = null, $submodel = null, $category = null)
    {
        $url = sprintf('/api/vehicle/v2/%s/%s/years', $makeName, $modelName);
        $params = compact('state', 'submodel', 'category') + [
            'view' => 'full'
        ];

        $response = $this->makeCall($url, $params);

        return array_map(function ($object) {
            return new VehicleModelYear($this, $object);
        }, $response->years);
    }

    /**
     * Gets a vehicle model year by make, model name, and year.
     *
     * @param  string           $makeName  The name of the make.
     * @param  string           $modelName The name of the model.
     * @param  int              $year      The model year.
     * @param  string             $state     Filters models by state.
     * @param  string             $submodel  Filters models by submodel types.
     * @param  string             $category  Filters models by category.
     * @return VehicleModelYear
     *
     * @see http://developer.edmunds.com/api-documentation/vehicle/spec_model_year/v2/02_year_details/api-description.html
     */
    public function getModelYear($makeName, $modelName, $year, $state = null, $submodel = null, $category = null)
    {
        $url = sprintf('/api/vehicle/v2/%s/%s/%d', $makeName, $modelName, $year);
        $params = compact('state', 'submodel', 'category') + [
            'view' => 'full'
        ];

        $response = $this->makeCall($url, $params);
        return new VehicleModelYear($this, $response);
    }

    /**
     * Gets a list of model styles by make, model name, and year.
     *
     * @param  string         $makeName  The name of the make.
     * @param  string         $modelName The name of the model.
     * @param  int            $year      The model year.
     * @param  string         $state     Filters models by state.
     * @param  string         $submodel  Filters models by submodel types.
     * @param  string         $category  Filters models by category.
     * @return VehicleStyle[]
     *
     * @see http://developer.edmunds.com/api-documentation/vehicle/spec_style/v2/01_by_mmy/api-description.html
     */
    public function getModelStyles($makeName, $modelName, $year, $state = null, $submodel = null, $category = null)
    {
        $url = sprintf('/api/vehicle/v2/%s/%s/%d/styles', $makeName, $modelName, $year);
        $params = compact('state', 'submodel', 'category') + [
            'view' => 'full'
        ];

        $response = $this->makeCall($url, $params);

        return array_map(function ($object) {
            return new VehicleStyle($this, $object);
        }, $response->styles);
    }

    /**
     * Gets a vehicle model style by its ID.
     *
     * @param  int          $styleId The ID of the vehicle style.
     * @return VehicleStyle
     *
     * @see http://developer.edmunds.com/api-documentation/vehicle/spec_style/v2/02_by_id/api-description.html
     */
    public function getModelStyle($styleId)
    {
        $url = sprintf('/api/vehicle/v2/styles/%d', $styleId);

        $response = $this->makeCall($url, ['view' => 'full']);
        return new VehicleStyle($this, $response);
    }

    /**
     * Gets a list of photos available for a vehicle model style.
     *
     * @param  int            $styleId The ID of the vehicle style.
     * @return VehiclePhoto[]
     *
     * @see http://developer.edmunds.com/api-documentation/vehicle/media_photos/v1/
     */
    public function getStylePhotos($styleId)
    {
        $response = $this->makeCall('/v1/api/vehiclephoto/service/findphotosbystyleid', [
            'styleId' => $styleId
        ]);

        return array_map(function ($object) {
            return new VehiclePhoto($this, $object);
        }, $response);
    }
}
