<?php
namespace Edmunds\SDK;

/**
 * Represents a particular photo of a specific vehicle style.
 *
 * @property string $title                The media item title
 * @property string $category             The media item category
 * @property array  $tags                 The list of tags
 * @property string $provider             The media item provider
 * @property array  $sources              List of photo sources
 * @property string $shotTypeAbbreviation Shot type abbreviation for photo
 * @property string $color                The vehicle color
 * @property array  $submodels            The list of submodels
 *
 * @see http://developer.edmunds.com/api-documentation/media/photos/v2/
 */
class VehiclePhoto extends RemoteObject
{
    /**
     * Gets the URL for the photo of a given size.
     *
     * @param  int    $size The width dimension of the photo.
     * @return string
     */
    public function getUrl($size)
    {
        foreach ($this->sources as $source) {
            if ($source->size->width === $size) {
                return 'https://media.ed.edmunds-media.com' . $source->link->href;
            }
        }

        return null;
    }

    /**
     * Gets the URL of the best quality image.
     *
     * @return string
     */
    public function getBestQualityUrl()
    {
        $bestUrl = null;
        $bestSize = 0;

        foreach ($this->sources as $source) {
            if ($source->size->width > $bestSize) {
                $bestUrl = $source->link->href;
                $bestSize = $source->size->width;
            }
        }

        return 'https://media.ed.edmunds-media.com' . $bestUrl;
    }
}
