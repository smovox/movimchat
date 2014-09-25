<?php

namespace moxl;

function message($to, $content)
{
    global $session;
    $xml = '
        <message to="'.str_replace(' ', '\40', $to).'" type="chat" id="'.$session['id'].'">
            <body>'.$content.'</body>
            <active xmlns="http://jabber.org/protocol/chatstates"/>
            <request xmlns="urn:xmpp:receipts"/>
        </message>';
    request($xml);
}

function messageComposing($to)
{
    global $session;
    $xml = '
        <message to="'.str_replace(' ', '\40', $to).'" type="chat" id="'.$session['id'].'">
            <composing xmlns="http://jabber.org/protocol/chatstates"/>
        </message>';
    request($xml);
}

function messagePaused($to)
{
    global $session;
    $xml = '
        <message to="'.str_replace(' ', '\40', $to).'" type="chat" id="'.$session['id'].'">
            <paused xmlns="http://jabber.org/protocol/chatstates"/>
        </message>';
    request($xml);
}
