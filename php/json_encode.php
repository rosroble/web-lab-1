/** latest versions of PHP have standard json_encode() function
* to encode array to JSON for further transferring to JS script
* however, helios.se doesn't have one, so I borrowed this one
*/
<?php

function json_encode($o)
{
    switch (gettype($o)) {
        case 'NULL':
            return 'null';
        case 'integer':
        case 'double':
            return strval($o);
        case 'string':
            return '"' . addslashes($o) . '"';
        case 'boolean':
            return $o ? 'true' : 'false';
        case 'object':
            $o = (array)$o;
        case 'array':
            $foundKeys = false;

            foreach ($o as $k => $v) {
                if (!is_numeric($k)) {
                    $foundKeys = true;
                    break;
                }
            }

            $result = array();

            if ($foundKeys) {
                foreach ($o as $k => $v) {
                    $result [] = json_encode($k) . ':' . json_encode($v);
                }

                return '{' . implode(',', $result) . '}';
            } else {
                foreach ($o as $k => $v) {
                    $result [] = json_encode($v);
                }
                return '[' . implode(',', $result) . ']';
            }
    }
}