<?php
/*
 * BookmarkGet.php
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

class BookmarkGet extends XECAction
{
    public function request() 
    {
        $this->store();
        bookmarkGet();
    }
    
    public function handle($stanza) {
        if($stanza->pubsub->items->item->storage) {
            $arr = array();
            foreach($stanza->pubsub->items->item->storage->children() as $c) {
                $tmp = array();
                $tmp['type'] = $c->getName();
                foreach($c->attributes() as $key => $value)
                    $tmp[$key] = (string)$value;

                foreach($c as $key => $value)
                    $tmp[$key] = (string)$value;
                    
                array_push($arr, $tmp);
            }
            
            $evt = new \Event();
            $evt->runEvent('bookmark', $arr);
        }
        
    }

    public function error($stanza) {
        $evt = new \Event();
        $evt->runEvent('bookmarkerror', t('Error')); 
    }

    public function errorItemNotFound($stanza) {
        $evt = new \Event();
        $evt->runEvent('bookmarkerror', t('Item Not Found')); 
    }
    
    public function errorFeatureNotImplemented($stanza) {
        $evt = new \Event();
        $evt->runEvent('bookmarkerror', '501 '.t('Feature Not Implemented')); 
    }
}
