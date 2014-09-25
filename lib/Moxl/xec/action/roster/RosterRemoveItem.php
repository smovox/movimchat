<?php
/*
 * RosterRemoveItem.php
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

class RosterRemoveItem extends XECAction
{
    private $_to;
    private $_from;
    
    public function request() 
    {
        $this->store();
        rosterRemove($this->_to);
    }
    
    public function setTo($to)
    {
        $this->_to = \echapJid($to);
        return $this;
    }
    
    public function setFrom($from)
    {
        $this->_from = $from;
        return $this;
    }
    
    public function handle($stanza) 
    {        
        $rd = new \modl\RosterLinkDAO();
        $rd->delete($this->_to);

        $evt = new \Event();
        $evt->runEvent('roster');
    }

    public function errorItemNotFound($stanza) {
        $this->handle($stanza);
    }
    
    public function errorServiceUnavailable() 
    {
        var_dump('Handle the Error !');
    }
}
