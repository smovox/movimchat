<?php

namespace moxl;

class PubsubSubscriptionListGet extends PubsubErrors
{
    
    public function request() 
    {
        $this->store();
        pubsubSubscriptionListGet();
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
