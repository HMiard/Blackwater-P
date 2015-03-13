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

namespace Blackwater\Factories;

/**
 * Generic methods for all QueryFactories.
 * Please note that the class name for a QueryFactory attached to a server must follow this naming convention :
 * $serverName."QueryFactory"
 *
 * Example : if you want to attach a QueryFactory to your "Air" server, name it
 * AirQueryFactory
 *
 * QueryFactories should also be placed in the same namespace as their bound server.
 *
 * Class QueryFactory
 * @package Blackwater\Factories
 */
class QueryFactory
{
    /**
     * @var DBInterface
     */
    public $dbi;

    public function __construct(DBInterface $dbi){
        $this->dbi = $dbi;
    }

    /**
     * General debug utility function
     *
     * @param $something
     */
    public function say($something){
        $date = new \DateTime();
        echo "\n$ ".$date->format(DATE_COOKIE)." > ".$this->dbi->serverName." QueryFactory > ".$something;
    }
}