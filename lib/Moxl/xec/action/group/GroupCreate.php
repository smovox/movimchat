<?php
/*
 * GroupNodeGetItems.php
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

class GroupCreate extends PubsubErrors
{
    private $_to;
    private $_node;
    private $_data;
    
    public function request() 
    {
        $this->store();
        groupCreate($this->_to, $this->_node);
    }
    
    public function setTo($to)
    {
        $this->_to = $to;
        return $this;
    }
    
    public function setNode($node)
    {
        $this->_node = $node;
        return $this;
    }
    
    public function setData($data)
    {
        $this->_data = $data;
        return $this;
    }
    
    public function handle($stanza) {
        if($stanza["type"] == "result"){
            $evt = new \Event();
            $evt->runEvent('creationsuccess', array($this->_to, $this->_node, $this->_data)); 
            
            //add to bookmark
            $sub = new \modl\Subscription();
            $sub->set(current(explode($stanza["to"], "/")), $this->_to, $this->_node, $stanza);
            
            $sd = new \modl\SubscriptionDAO();
            $sd->set($sub);
        }
    }
    
    public function error($error) {
        $evt = new \Event();
        $evt->runEvent('creationerror', $this->_node); 
    }
}
