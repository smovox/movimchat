<?php
/*
 * PresenceMuc.php
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

class PresenceMuc extends XECAction
{
    private $_to;
    private $_nickname;
    
    public function request() 
    {
        $this->store();
        presenceMuc($this->_to);
    }

    public function setTo($to)
    {
        $this->_to = $to;
        return $this;
    }  

    public function setNickname($nickname)
    {
        $this->_nickname = $nickname;
        return $this;
    }  
    
    public function handle($stanza) {
        $p = new \modl\Presence();
        $p->setPresence($stanza);
        
        $pd = new \modl\PresenceDAO();
        $pd->set($p);

        $evt = new \Event();
        $evt->runEvent('presence', $p);
    }
}
