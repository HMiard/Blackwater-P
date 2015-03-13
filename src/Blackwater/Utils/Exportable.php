<?php

/*
* Blackwater-P - PHP Plugin
* Copyright (C) 2014-2015 Hugo MIARD
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

namespace Blackwater\Utils;

/**
 * Class Exportable
 * @package Blackwater\Utils
 */
abstract class Exportable {

    /**
     * Exporting attributes from a class as an array.
     *
     * @return array
     */
    public function export(){
        $ret = array();
        foreach(get_object_vars($this) as $key => $value){
            $method = 'get'.str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
            if (is_callable(array($this, $method)))
                $ret[$key] = $this->$method();
        }
        return $ret;
    }
}