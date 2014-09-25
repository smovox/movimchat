<?php

namespace moxl;

function versionSend($to, $id, $name, $version, $os)
{
    $xml = '
        <iq type="result" xmlns="jabber:client" to="'.$to.'" id="'.$id.'">
            <query xmlns="jabber:iq:version">
                <name>'.$name.'</name>
                <version>'.$version.'</version>
                <os>'.$os.'</os>
            </query>
        </iq>
    ';
    
    request($xml);
}
