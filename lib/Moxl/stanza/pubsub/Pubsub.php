<?php

namespace moxl;

function pubsubSubscribe($to, $from, $node)
{
    $xml = '
        <pubsub xmlns="http://jabber.org/protocol/pubsub">
            <subscribe
                node="'.$node.'"
                jid="'.$from.'"/>
        </pubsub>
        ';
        
    $xml = iqWrapper($xml, $to, 'set');
    request($xml);
}

function pubsubUnsubscribe($to, $from, $node, $subid)
{
    if($subid != '')
        $subid = 'subid="'.$subid.'"';
    $xml = '
        <pubsub xmlns="http://jabber.org/protocol/pubsub">
            <unsubscribe
                node="'.$node.'"
                jid="'.$from.'"
                '.$subid.'/>
        </pubsub>';      
    
    $xml = iqWrapper($xml, $to, 'set');  
        
    request($xml);
}

function pubsubGetSubscriptions($to, $node)
{
    $xml = '
        <pubsub xmlns="http://jabber.org/protocol/pubsub#owner">
            <subscriptions node="'.$node.'"/>
        </pubsub>';
    $xml = iqWrapper($xml, $to, 'get');
    request($xml);
}

function pubsubSetSubscriptions($to, $node, $data)
{
    $subscriptions = "";
    foreach($data as $jid_subid => $subscription){
        list($jid, $subid) = split("_", $jid_subid);
        $subscriptions .= '
			<subscription
				jid="'.$jid.'" ';
            if($subid != null)
                $subscriptions .=
                    'subid="'.$subid.'" ';
            $subscriptions .= '
				subscription="'.$subscription.'" />';
    }
    
    $xml = '
        <pubsub xmlns="http://jabber.org/protocol/pubsub#owner">
            <subscriptions node="'.$node.'">
            '.$subscriptions.'
            </subscriptions>
        </pubsub>';
    
    $xml = iqWrapper($xml, $to, 'set');
    request($xml);  
}

function pubsubGetItems($to, $node)
{
    $xml = '
        <pubsub xmlns="http://jabber.org/protocol/pubsub">
            <items node="'.$node.'" max_items="40"/>
        </pubsub>';
    $xml = iqWrapper($xml, $to, 'get');
    request($xml);
}

function postPublish($to, $node, $atom)
{
    $xml = '
        <pubsub xmlns="http://jabber.org/protocol/pubsub">
            <publish node="'.$node.'">
            <item id="'.$atom->id.'">
                '.$atom.'
            </item>
            </publish>
        </pubsub>';
    $xml = iqWrapper($xml, $to, 'set');
    request($xml);
}

function pubsubPostDelete($to, $node, $id)
{
    $xml = '
        <pubsub xmlns="http://jabber.org/protocol/pubsub">
            <retract node="'.$node.'" notify="true">
                <item id="'.$id.'"/>
            </retract>
        </pubsub>';
    $xml = iqWrapper($xml, $to, 'set');
    request($xml);
}

function pubsubGetConfig($to, $node)
{
    $xml = '
        <pubsub xmlns="http://jabber.org/protocol/pubsub#owner">
            <configure node="'.$node.'"/>
        </pubsub>';
    
    $xml = iqWrapper($xml, $to, 'get');
    request($xml);
}

function pubsubSetConfig($to, $node, $data)
{
    $xmpp = new \FormtoXMPP();
    $stream = '
        <pubsub xmlns="http://jabber.org/protocol/pubsub#owner">
            <configure node="'.$node.'">
                <x xmlns="jabber:x:data" type="submit"></x>
            </configure>
        </pubsub>';
    $xml = $xmpp->getXMPP($stream, $data)->asXML();
    $xml = iqWrapper(strstr($xml, '<pubsub'), $to, 'set');
    request($xml);
}


function pubsubGetAffiliations($to, $node)
{
    $xml = '
		<pubsub xmlns="http://jabber.org/protocol/pubsub#owner">
            <affiliations node="'.$node.'"/>
        </pubsub>';
    
    $xml = iqWrapper($xml, $to, 'get');
    request($xml);  
}

function pubsubSetAffiliations($to, $node, $data)
{
    $affiliations = "";
    foreach($data as $jid_subid => $affiliation){
        $split = split("_", $jid_subid);
        $affiliations .= '
			<affiliation 
				jid="'.$split[0].'" 
				subid="'.$split[1].'" 
				affiliation="'.$affiliation.'" />';
    }
    
    $xml = '<pubsub xmlns="http://jabber.org/protocol/pubsub#owner">
            <affiliations node="'.$node.'">
            '.$affiliations.'
            </affiliations>
        </pubsub>';
    
    $xml = iqWrapper($xml, $to, 'set');
    request($xml);  
}
