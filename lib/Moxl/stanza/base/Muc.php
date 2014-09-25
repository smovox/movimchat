<?php

namespace moxl;

function messageMuc($to, $content)
{
    global $session;
    $xml = '
        <message to="'.str_replace(' ', '\40', $to).'" type="groupchat" id="'.$session['id'].'">
            <body>'.$content.'</body>
        </message>';
    request($xml);
}
