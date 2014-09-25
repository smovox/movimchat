<?php

namespace moxl;

class PubsubSubscriptionListAdd extends PubsubErrors
{
    private $_to;
    private $_from;
    private $_node;
    private $_data;
    
    public function request() 
    {
        $this->store();
        pubsubSubscriptionListAdd($this->_to, $this->_from, $this->_node, $this->_data['title']);
    }
    
    public function setTo($to)
    {
        $this->_to = $to;
        return $this;
    }
    
    public function setFrom($from)
    {
        $this->_from = $from;
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
    }
}
