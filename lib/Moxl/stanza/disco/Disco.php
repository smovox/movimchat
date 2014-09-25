<?php

namespace moxl;

function discoAnswer($to, $id)
{
    global $session;
    $xml = '
        <iq type="result" xmlns="jabber:client" to="'.$to.'" id="'.$id.'">
            <query 
                xmlns="http://jabber.org/protocol/disco#info"
                node="http://moxl.movim.eu/#'.generateMovimCaps().'">
                <identity category="client" type="web" name="Movim"/>';

        foreach(getSupportedServices() as $service)
            $xml .= '<feature var="'.$service.'"/>'."\n";
            
    $xml .= '
            </query>
        </iq>';
    request($xml);
}

function discoRequest($to, $node)
{
    $xml = '
        <query xmlns="http://jabber.org/protocol/disco#info"
            node="'.$node.'"/>';
    $xml = iqWrapper($xml, $to, 'get');
    request($xml); 
}

function discoItems($to)
{
    $xml = '
        <query xmlns="http://jabber.org/protocol/disco#items"/>';
    $xml = iqWrapper($xml, $to, 'get');
    request($xml); 
}
