<?php
namespace CarRived\Edmunds;

/**
 * Exception thrown when an error occurs during an API call.
 */
class ApiException extends \RuntimeException
{
    /**
     * Creates a new API exception.
     *
     * @param string|object $message A string describing the error or an object containing error data.
     * @param int           $code    The server response code.
     */
    public function __construct($message, $code)
    {
        if (is_object($message)) {
            $message = sprintf('%s: %s', $message->errorType, $message->message);
        }

        parent::__construct($message, $code);
    }
}
