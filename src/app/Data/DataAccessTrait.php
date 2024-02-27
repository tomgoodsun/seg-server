<?php

namespace Data;

trait DataAccessTrait
{
    const KEY_DELIMITER = '.';
    
    /**
     * Search from array given in argument No.1 with key given in argument No.2
     *
     * @param array $data
     * @param string|int|null $key
     * @return mixed
     */
    protected function searchFromData(array $data, $key = null)
    {
        // $key can be sparated by dot
        if (null !== $key) {
            $keys = explode(self::KEY_DELIMITER, $key);
            foreach ($keys as $k) {
                if (array_key_exists($k, $data)) {
                    $data = $data[$k];
                } else {
                    return null;
                }
            }
        }
        return $data;
    }
}
