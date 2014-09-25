<?php
/*
 * @file Version.php
 * 
 * @brief Handle incoming jabber:iq:version request
 * 
 * Copyright 2012 edhelas <edhelas@edhelas-laptop>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

namespace moxl;

class Version extends XECPayload
{
    public function handle($stanza, $parent = false) {        
        $to = (string)$parent->attributes()->from;
        $id = (string)$parent->attributes()->id;

        $vd = new VersionSend();
        $vd->setTo($to)
           ->setId($id)
           ->setName(ucfirst(APP_NAME))
           ->setVersion(APP_VERSION)
           ->setOs(PHP_OS)
           ->request();
    }
}
