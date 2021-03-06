<?php

/**
 +-----------------------------------------------------------------------+
 | This file is part of the Roundcube Webmail client                     |
 |                                                                       |
 | Copyright (C) The Roundcube Dev Team                                  |
 |                                                                       |
 | Licensed under the GNU General Public License version 3 or            |
 | any later version with exceptions for skins & plugins.                |
 | See the README file for a full license statement.                     |
 |                                                                       |
 | PURPOSE:                                                              |
 |   Class representing an address directory result set                  |
 +-----------------------------------------------------------------------+
 | Author: Thomas Bruederli <roundcube@gmail.com>                        |
 +-----------------------------------------------------------------------+
*/

/**
 * Roundcube result set class
 *
 * Representing an address directory result set.
 * Implenets Iterator and thus be used in foreach() loops.
 *
 * @package    Framework
 * @subpackage Addressbook
 */
class rcube_result_set implements Iterator, ArrayAccess
{
    public $count      = 0;
    public $first      = 0;
    public $searchonly = false;
    public $records    = [];

    private $current = 0;

    function __construct($count = 0, $first = 0)
    {
        $this->count = (int) $count;
        $this->first = (int) $first;
    }

    function add($rec)
    {
        $this->records[] = $rec;
    }

    function iterate()
    {
        $current = $this->current();

        $this->current++;

        return $current;
    }

    function first()
    {
        $this->current = 0;
        return $this->current();
    }

    function seek($i)
    {
        $this->current = $i;
    }

    /*** Implement PHP ArrayAccess interface ***/

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $offset = count($this->records);
            $this->records[] = $value;
        }
        else {
            $this->records[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->records[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->records[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->records[$offset];
    }

    /***  PHP 5 Iterator interface  ***/

    function rewind()
    {
        $this->current = 0;
    }

    function current()
    {
        return isset($this->records[$this->current]) ? $this->records[$this->current] : null;
    }

    function key()
    {
        return $this->current;
    }

    function next()
    {
        return $this->iterate();
    }

    function valid()
    {
        return isset($this->records[$this->current]);
    }
}
