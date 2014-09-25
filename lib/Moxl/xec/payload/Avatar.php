<?php
/*
 * @file Attention.php
 * 
 * @brief Handle incoming Avatar update
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

class Avatar extends XECPayload
{
    public function handle($stanza, $parent = false) {        
        $jid = current(explode('/',(string)$parent->attributes()->from));

        $evt = new \Event();
            
        $cd = new \modl\ContactDAO();
        
        $c = $cd->get($jid);
        
        if($c == null)
            $c = new \modl\Contact();

        if($c->photobin != (string)$stanza->items->item->data) {
            $c->jid       = $jid;
            $c->phototype = 'image/png';
            $c->photobin  = (string)$stanza->items->item->data;
            
            $cd->set($c);
            
            $c->createThumbnails();
                
            $evt->runEvent('vcard', $c);
            $evt->runEvent('roster');
        }
    }
}