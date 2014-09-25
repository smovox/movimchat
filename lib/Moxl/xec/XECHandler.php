<?php
/*
 * @file XECHandler.php
 * 
 * @brief Handle incoming XMPP request and dispatch them to the correct 
 * XECElement
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

class XECHandler {

    /**
     * Constructor of class XECHandler.
     *
     * @return void
     */
    static public function handle($array)
    {
        // We get the cached instances
        $sess = \Session::start(APP_NAME);
        $_instances = $sess->get('xecinstances');
        
        $user = new \User();
        
        $db = \modl\Modl::getInstance();
        $db->setUser($user->getLogin());
        
        foreach($array as $child) {
            $id = 0;
            $element = '';
            
            // Id verification in the returned stanza
            if($child->getName() == 'iq') {
                $id = (int)$child->attributes()->id;
                $element = 'iq';
            }

            if($child->getName() == 'presence') {
                $id = (int)$child->attributes()->id;
                $element = 'presence';
            }

            if($child->getName() == 'message') {
                $id = (int)$child->attributes()->id;
                $element = 'message';
            }

            if(
                $id != 0 && 
                $_instances != false && 
                array_key_exists($id, $_instances)
              ) {
                // We search an existent instance
                if(!array_key_exists($id, $_instances))
                    MoxlLogger::log('XECHandler : Memory instance not found');
                else {
                    $instance = $_instances[$id];
                    
                    $action = unserialize($instance['object']);
        
                    $error = false;
                    
                    // Handle specific query error
                    if($child->query->error)
                        $error = $child->query->error;
                    elseif($child->error)
                        $error = $child->error;
        
                    // XMPP returned an error
                    if($error) {
                        $errors = $error->children();

                        $errorid = XECHandler::formatError($errors->getName());

                        MoxlLogger::log('XECHandler : '.$id.' - '.$errorid);

                        /* If the action has defined a special handler
                         * for this error
                         */
                        if(method_exists($action, $errorid))
                            $action->$errorid($errorid);
                        // We also call a global error handler
                        if(method_exists($action, 'error'))
                            $action->error($errorid);
                    } else {
                        // We launch the object handle
                        $action->handle($child);
                    }
                    // We clean the object from the cache
                    unset($_instances[$id]);
                    
                    $sess->set('xecinstances', $_instances);
                }
            } else {                                
                MoxlLogger::log('XECHandler : Not an XMPP ACK');

                XECHandler::handleNode($child);
                
                foreach($child->children() as $s1) {
                    XECHandler::handleNode($s1, $child);  
                    foreach($s1->children() as $s2) 
                        XECHandler::handleNode($s2, $child);  
                }
            }
        }
        
        $evt = new \Event();
        $evt->launchEvents();
    }
    
    static public function handleNode($s, $sparent = false) {
        $name = $s->getName();
        $ns = $s->getNamespaces();

		$node = false;
		
		if($s->items && $s->items->attributes()->node)
			$node = (string)$s->items->attributes()->node;
        
        if(is_array($ns))
            $ns = current($ns);
 
        if($node != false) {
            $hash = md5($name.$ns.$node);
            MoxlLogger::log('XECHandler : Searching a payload for "'.$name . ':' . $ns . ' [' . $node . ']", "'.$hash.'"'); 
            XECHandler::searchPayload($hash, $s, $sparent);
        } else {      
			$hash = md5($name.$ns);
			MoxlLogger::log('XECHandler : Searching a payload for "'.$name . ':' . $ns . ' ", "'.$hash.'"'); 
			$more = XECHandler::searchPayload($hash, $s, $sparent);
		}

    }
    
    static public function searchPayload($hash, $s, $sparent = false) {       
        $base = __DIR__.'/';
        
        $hashToClass = getHashToClass();
        
        if(isset($hashToClass[$hash])) {
            if(file_exists($base.'payload/'.$hashToClass[$hash].'.php')) {
                require_once($base.'payload/'.$hashToClass[$hash].'.php');
                $classname = '\\moxl\\'.$hashToClass[$hash];
                
                if(class_exists($classname)) {
                    $payload_class = new $classname();
                    $payload_class->handle($s, $sparent);
                } else {
                   MoxlLogger::log('XECHandler : Payload class "'.$hashToClass[$hash].'" not found'); 
                }
            } else {
                MoxlLogger::log('XECHandler : Payload file "'.$hashToClass[$hash].'" not found');
            }
        } else {
            MoxlLogger::log('XECHandler : This event is not listed');
            return true;
        }
    }
    
    static public function handleError($number, $message) {
        $payload_class = new MoxlRequestError();
        $payload_class->handle($number, $message);
    }

    /* A simple function to format a error-string-text to a
     * camelTypeText 
     */
    static public function formatError($string) {

        $words = explode('-', $string);
        $f = 'error';
        foreach($words as $word)
            $f .= ucfirst($word);

        return $f;
    }

}
