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

namespace Blackwater\Servers;

use Blackwater\Kernel;
use Blackwater\Objects\User;
use Ratchet\ConnectionInterface;

/**
 * Base class for basics and advanced Blackwater servers.
 *
 * Class BlackwaterBaseServer
 * @package Blackwater\Server
 */
class BlackwaterBaseServer extends Kernel
{
    /**
     * @var \SplObjectStorage
     */
    public $users;


    public function __construct($serverName){

        parent::__construct($serverName);

        $this->users = new \SplObjectStorage();
    }


    public function attachUser(User $user){

        $this->users->attach($user);
        $this->say("New user connected !");
    }

    public function detachUser(User $user){

        $this->users->detach($user);
        $this->say("User detached from server.");
    }

    /**
     * Handy method.
     *
     * @param ConnectionInterface $conn
     */
    public function findAndDetachUser(ConnectionInterface $conn){

        $u = $this->getUserByConn($conn);
        if ($u !== false)
            $this->detachUser($u);
    }

    /**
     * Sending a message to every single connected user.
     *
     * @param $data
     */
    public function sendToEveryone($data){

        /**
         * @var $u User
         */
        foreach ($this->users as $u)
            $u->getConn()->send($data);

        $this->say("Data sent to everyone : ".$data);
    }

    /**
     * Sending a message to everyone but specified connections.
     *
     * @param $data
     * @param $connections array
     */
    public function sendToEveryoneBut($data, array $connections){

        /**
         * @var $u User
         */
        foreach ($this->users as $u){
            if (!in_array($u->getConn(), $connections))
                $u->getConn()->send($data);
        }
        $this->say("Data sent to everyone but ... : ".$data);
    }

    /**
     * Using a Connection item to identify an user
     *
     * @param ConnectionInterface $conn
     * @return User
     */
    public function getUserByConn(ConnectionInterface $conn){

        /**
         * @var User $u
         */
        foreach ($this->users as $u){
            if ($u->getConn() == null) $this->detachUser($u);
            if ($u->getConn() == $conn) return $u;
        }
        return false;
    }
}
