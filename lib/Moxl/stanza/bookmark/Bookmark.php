<?php

namespace moxl;

function bookmarkGet()
{  
    $xml = '
    <pubsub xmlns="http://jabber.org/protocol/pubsub">
        <items node="storage:bookmarks"/>
    </pubsub>';
        
    $xml = iqWrapper($xml, false, 'get');
    request($xml);
}

function bookmarkSet($arr)
{
    $xml = '';
    
    foreach($arr as $elt) {
        switch ($elt['type']) {
            case 'conference':
                $xml .= '
                    <conference name="'.$elt['name'].'"
                                autojoin="'.$elt['autojoin'].'"
                                jid="'.$elt['jid'].'">
                        <nick>'.$elt['nick'].'</nick>
                    </conference>';
                break;
            case 'url':
                $xml .= '
                <url name="'.$elt['name'].'"
                     url="'.$elt['url'].'"/>';
                break;
            case 'subscription':
                $xml .= '
                <subscription 
                    xmlns="urn:xmpp:pubsub:subscription:0"
                    server="'.$elt['server'].'" 
                    node="'.$elt['node'].'" 
                    subid="'.$elt['subid'].'">
                    <title>'.$elt['title'].'</title>
                </subscription>';
                break;
        }       
    }
    
    $xml = '
    <pubsub xmlns="http://jabber.org/protocol/pubsub">
        <publish node="storage:bookmarks">
            <item id="current">
                <storage xmlns="storage:bookmarks">
                    '.$xml.'
                </storage>
            </item>
        </publish>
        <publish-options>
            <x xmlns="jabber:x:data" type="submit">
                <field var="FORM_TYPE" type="hidden">
                    <value>http://jabber.org/protocol/pubsub#publish-options</value>
                </field>
                <field var="pubsub#persist_items">
                    <value>true</value>
                </field>
                <field var="pubsub#access_model">
                    <value>whitelist</value>
                </field>
            </x>
        </publish-options>
    </pubsub>';

    $xml = iqWrapper($xml, false, 'set');
    request($xml);
}
