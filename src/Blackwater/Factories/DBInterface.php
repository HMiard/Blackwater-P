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
 * Interfacing databases through PDO.
 *
 * Class DBInterface
 * @package Blackwater\Factories
 */
class DBInterface
{
    /**
     * @var \PDO
     */
    private $manager;
    public $serverName;

    public function __construct($parameters, $serverName){

        if (empty($parameters)){
            $this->say("Empty database credentials !");
            die();
        }

        try {
            $this->manager = new \PDO(
                $parameters["driver"].":host=".$parameters["host"].";dbname=".$parameters["dbname"],
                $parameters["user"],
                $parameters["password"]
            );
        }catch (\PDOException $e){
            die("Error : ".$e->getMessage());
        }
        $this->serverName = $serverName;
        $this->say($this->serverName." DBInterface built successfully ! Specializing...");
    }

    public function say($something){
        $date = new \DateTime();
        echo "\n$ ".$date->format(DATE_COOKIE)." > ".$this->serverName." DBI > ".$something;
    }


    public function getManager(){
        return $this->manager;
    }


    public function query($rqst, array $parameters){

        $st = $this->manager->prepare($rqst);
        $this->bindValues($st, $parameters);

        if ($st->execute())
            return $st->fetchAll(\PDO::FETCH_ASSOC);

        $this->say("Error while querying the following request : \n".$st->queryString);
        return false;
    }


    public function exec($rqst, array $parameters){

        $st = $this->manager->prepare($rqst);
        $this->bindValues($st, $parameters);
        if ($st->execute())
            return true;

        $this->say("Error while executing the following request : \n".$st->queryString);
        return false;
    }


    public function bindValues(\PDOStatement & $st, array $parameters){

        foreach($parameters as $key => $value){
            if (is_int($key)) $key++; // 1-based PDO keys

            if (is_int($value))
                $st->bindValue($key, $value, \PDO::PARAM_INT);
            else if (is_bool($value))
                $st->bindValue($key, $value, \PDO::PARAM_BOOL);
            else{
                $st->bindValue($key, $value, \PDO::PARAM_STR);
            }
        }
    }
}