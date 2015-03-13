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

namespace Blackwater;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class Builder
{
    /**
     * Builds and run a Blackwater server according to its name
     * on the specified port.
     *
     * @param $serverName
     * @param $port
     */
    static function buildAndRun($serverName, $port = 8080){

        $serverName = strtolower($serverName);
        $serverName = ucfirst($serverName);

        $className = $serverName."Server\\".$serverName."Server";
        $build = new $className($serverName);

        file_put_contents(
            "bin/watcher",
            getmypid()
        );


        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    $build
                )
            ),
            $port
        );
        echo "\nBlackwater > ".$serverName." server running on localhost, port ".$port." !\n";
        $server->run();
    }

    /**
     * Returning some useful constants.
     *
     * @param $serverName
     * @return array
     */
    static function getGlobalServerDescription($serverName){

        $global_name = $serverName."Server\\".$serverName."Server";
        $r = new \ReflectionClass($global_name);
        $dir = dirname($r->getFileName());

        return array(
            "name" => $serverName,
            "global_name" => $global_name,
            "src_dir" => $dir,
            "conf_dir" => $dir."/conf",
            "bin_dir" => $dir."/bin"
        );
    }
}
