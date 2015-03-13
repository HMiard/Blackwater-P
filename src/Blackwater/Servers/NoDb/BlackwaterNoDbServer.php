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

namespace Blackwater\Servers\NoDb;


use Blackwater\Objects\User;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;


/**
 * Convenient wrapper class for servers that require no db interactions.
 * Note that all the Blackwater servers must follow the naming convention :
 * $serverName."Server"
 *
 * Example : the class for a server named "Air" should be :
 * AirServer
 *
 * Class BlackwaterNoDbServer
 * @package Blackwater\Servers\NoDb
 */
abstract class BlackwaterNoDbServer extends BlackwaterNoDbBaseServer implements MessageComponentInterface {

    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    function onOpen(ConnectionInterface $conn)
    {
        $this->attachUser(new User($conn));
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    function onClose(ConnectionInterface $conn)
    {
        $this->findAndDetachUser($conn);
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        $this->say("An error occured : {$e->getMessage()}");
        $this->findAndDetachUser($conn);
        $conn->close();
    }

    /**
     * Triggered when a client sends data through the socket
     * This is the only method that must be overrided. The core logic for the server goes here.
     *
     * @param  \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */
    abstract function onMessage(ConnectionInterface $from, $msg);
}