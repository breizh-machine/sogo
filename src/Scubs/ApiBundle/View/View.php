<?php

namespace Scubs\ApiBundle\View;

class View
{
    public function toArray()
    {
        return $this->convertObjectToArray($this);
    }

    private function convertObjectToArray($object)
    {
        if (is_array($object) || is_object($object))
        {
            $result = array();
            foreach ($object as $key => $value)
            {
                $result[$key] = $this->convertObjectToArray($value);
            }
            return $result;
        }
        return $object;
    }
}