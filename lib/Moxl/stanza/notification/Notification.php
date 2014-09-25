<?php

namespace moxl;

function notificationGet($to)
{
    $xml = '
        <pubsub xmlns="http://jabber.org/protocol/pubsub">
            <items node="urn:xmpp:inbox"/>
        </pubsub>';
    $xml = iqWrapper($xml, $to, 'get');
    request($xml);
}

function notificationItemDelete($to, $id)
{
    $xml = '
        <pubsub xmlns="http://jabber.org/protocol/pubsub">
            <retract node="urn:xmpp:inbox" notify="true">
                <item id="'.$id.'"/>
            </retract>
        </pubsub>';
    $xml = iqWrapper($xml, $to, 'set');
    request($xml);
}
