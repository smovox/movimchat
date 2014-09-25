<?php

/**
 * @package Widgets
 *
 * @file Ack.php
 * This file is part of MOVIM.
 *
 * @brief Send ack for each incoming requests.
 *
 * @author Timothée Jaussoin <edhelas@gmail.com>
 *
 * @version 1.0
 *
 * Copyright (C)2013 MOVIM project
 *
 * See COPYING for licensing information.
 */

use Moxl\Xec\Action\Ack\Send;
 
class Ack extends WidgetBase {
    function load()
    {
        $this->registerEvent('ack', 'onAckRequest');
        
        $this->view->assign('ack', 
                            $this->genCallAjax(
                                "ajaxAckRequest", 'to', 'id')
                        );
    }
    
    function onAckRequest($ack) {
        $to = $ack[0];
        $id = $ack[1];
        RPC::call('ackRequest', $to, $id);
    }
    
    function ajaxAckRequest($to, $id) {       
        $ack = new Send;
        $ack->setTo($to)
            ->setId($id)
            ->request();
    }
}
