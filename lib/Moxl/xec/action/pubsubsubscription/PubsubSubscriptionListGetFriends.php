<?php

namespace moxl;

class PubsubSubscriptionListGetFriends extends PubsubErrors
{
    private $_to;
    
    public function request() 
    {
        $this->store();
        pubsubSubscriptionListGetFriends($this->_to);
    }
    
    public function setTo($to)
    {
        $this->_to = $to;
        return $this;
    }
    
    public function handle($stanza) {
        $evt = new \Event();
        $tab = array();
        foreach($stanza->pubsub->items->children() as $i) {
            $sub = array((string)$i->subscription["node"], (string)$i->subscription["server"], (string)$i->subscription->title);
            array_push($tab, $sub);
        }
        
        $evt->runEvent('groupsubscribedlist', $tab); 
    }
}
