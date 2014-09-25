<?php
/*
 * RosterUpdateItem.php
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

class RosterUpdateItem extends XECAction
{
    private $_to;
    private $_from;
    private $_name;
    private $_group;
    
    public function request() 
    {
        $this->store();
        rosterUpdate($this->_to, $this->_name, $this->_group);
    }
    
    public function setTo($to)
    {
        $this->_to = $to;
        return $this;
    }
    
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }
    
    public function setGroup($group)
    {
        $this->_group = $group;
        return $this;
    }
    
    public function setFrom($from)
    {
        $this->_from = $from;
        return $this;
    }
    
    public function handle($stanza) {
        $r = new \modl\RosterLink();
        
        $r->session = $this->_from;
        $r->jid = $this->_to;
        $r->rostername = $this->_name;
        $r->groupname = $this->_group;
        
        $rd = new \modl\RosterLinkDAO();
        $rd->update($r);
        
        $evt = new \Event();
        $evt->runEvent('rosterupdateditem', $this->_to);
    }
}
