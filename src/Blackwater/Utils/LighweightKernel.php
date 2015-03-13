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

use Blackwater\Builder;

/**
 * Use this kernel if you need no database interactions.
 *
 * Class LighweightKernel
 * @package Blackwater\Utils
 */
class LighweightKernel
{
    /**
     * @var String
     */
    public $serverName;
    /**
     * @var array
     */
    public $serverDescr;


    public function __construct($serverName){

        $this->serverDescr = Builder::getGlobalServerDescription($serverName);
        $this->serverName = $serverName;
        $this->say($serverName . " server initialized !");
    }

    /**
     * Utility debug function
     *
     * @param $something
     */
    public function say($something){

        if (!empty($this->serverName)){
            $date = new \DateTime();
            echo "\n$ ".$date->format(DATE_COOKIE)." > ".$this->serverName." Server > ".$something;
        }
    }
}