<?php

/**
 * MissingRequiredOptionException
 * @package iqomp/growinc-pga
 * @version 1.0.0
 */

namespace Iqomp\GrowIncPGA\Exception;

class MissingRequiredOptionException extends \Exception
{
    public function __construct($name, $collection)
    {
        parent::__construct('Option `' . $name . '` is required on calling the API');
    }
}
