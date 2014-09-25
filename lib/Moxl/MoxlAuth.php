<?php

namespace moxl;

function mechanismChoice($mec) {
    $mechanism = array(
                    //'SCRAM-SHA-1',
                    'DIGEST-MD5',
                    'CRAM-MD5',
                    'PLAIN');
    
    $mecchoice = false;
    $i = 0;
    
    while($mecchoice == false && $i <= count($mechanism)) {
        if(in_array($mechanism[$i], $mec))
            $mecchoice = true;
        else $i++;
    }

    return $mechanism[$i];
}

function mechanismPLAIN() {
    global $session;
    
    $response = base64_encode(chr(0).$session['user'].chr(0).$session['password']);

    $xml = boshWrapper(
            '<auth xmlns="urn:ietf:params:xml:ns:xmpp-sasl" mechanism="PLAIN" client-uses-full-bind-result="true">'.
                $response.
            '</auth>');

    $r = new MoxlRequest($xml);
    $xml = $r->fire();

    $xmle = new \SimpleXMLElement($xml['content']);

    if(!$xmle->success)
        return 'wrongaccount';
    else
        return 'OK';
}

function mechanismDIGESTMD5() {
    $xml = boshWrapper(
            '<auth 
                client-uses-full-bind-result="true"
                xmlns="urn:ietf:params:xml:ns:xmpp-sasl" 
                mechanism="DIGEST-MD5"/>');

    $r = new MoxlRequest($xml);
    $xml = $r->fire();

    $xmle = new \SimpleXMLElement($xml['content']);
    if($xmle->failure)
        return 'errormechanism';

    $decoded = base64_decode((string)$xmle->challenge);

    if($decoded)
        $response = createChallengeDIGESTMD5($decoded);
    else
        return 'errorchallenge';

    MoxlLogger::log("/// CHALLENGE");

        $xml = boshWrapper(
                '<response xmlns="urn:ietf:params:xml:ns:xmpp-sasl">
                    '.$response.'
                </response>');

        $r = new MoxlRequest($xml);
        $xml = $r->fire();

        $xmle = new \SimpleXMLElement($xml['content']);
        if($xmle->failure)
            return 'wrongaccount';

    if($xmle->success)
        return 'OK';

    MoxlLogger::log("/// RESPONSE");

        $xml = boshWrapper(
                '<response xmlns="urn:ietf:params:xml:ns:xmpp-sasl"/>');

        $r = new MoxlRequest($xml);
        $xml = $r->fire();

        $xmle = new \SimpleXMLElement($xml['content']);

    if(!$xmle->success)
        return 'failauth';
    else
        return 'OK';
}

function mechanismCRAMMD5() {
        $xml = boshWrapper(
                '<auth 
                    client-uses-full-bind-result="true"
                    xmlns="urn:ietf:params:xml:ns:xmpp-sasl" 
                    mechanism="CRAM-MD5"/>');

        $r = new MoxlRequest($xml);
        $xml = $r->fire();

        $xmle = new \SimpleXMLElement($xml['content']);
        if($xmle->failure)
            return 'errormechanism';

        $decoded = base64_decode((string)$xmle->challenge);

        if($decoded)
            $response = createChallengeCRAMMD5($decoded);
        else
            return 'errorchallenge';

    MoxlLogger::log("/// CHALLENGE");

        $xml = boshWrapper(
                '<response xmlns="urn:ietf:params:xml:ns:xmpp-sasl">'.$response.'</response>');

        $r = new MoxlRequest($xml);
        $xml = $r->fire();

        $xmle = new \SimpleXMLElement($xml['content']);

    if(!$xmle->success)
        return 'failauth';
    else
        return 'OK';
}

function restartRequest() {
    global $session;
    
    $xml =
        '<body
            rid="'.$session['rid'].'"
            sid="'.$session['sid'].'"
            to="'.$session['host'].'"
            xml:lang="en"
            xmpp:restart="true"
            xmlns="http://jabber.org/protocol/httpbind"
            xmlns:xmpp="urn:xmpp:xbosh"/>';

    $r = new MoxlRequest($xml);
    $xml = $r->fire();

    return $xml;
}

function ressourceRequest() {
    global $session;
    
    $xml = boshWrapper(
        '<iq type="set" id="'.$session['id'].'">
            <bind xmlns="urn:ietf:params:xml:ns:xmpp-bind">
                <resource>'.$session['ressource'].'</resource>
            </bind>
        </iq>');

    $r = new MoxlRequest($xml);
    $xml = $r->fire();

    $xmle = new \SimpleXMLElement($xml['content']);

    if($xmle->head || (string)$xmle->attributes()->type == 'terminate')
        return 'failauth';
    elseif($xmle->iq->bind->jid) {
        list($jid, $ressource) = explode('/', (string)$xmle->iq->bind->jid);
        if($ressource)
            $session['ressource'] = $ressource;
    }

    return 'OK';
}
