<?php

/**
 * @package Widgets
 *
 * @file Vcard4.php
 * This file is part of MOVIM.
 * 
 * @brief A widget which display all the infos of a contact, vcard 4 version
 *
 * @author Timothée    Jaussoin <edhelas_at_gmail_dot_com>

 * Copyright (C)2013 MOVIM project
 * 
 * See COPYING for licensing information.
 */

use Moxl\Xec\Action\Vcard4\Get;
use Moxl\Xec\Action\Vcard4\Set;

class Vcard4 extends WidgetBase
{
    function load()
    {
        $this->registerEvent('myvcard4valid', 'onMyVcard4Received');
        $this->registerEvent('myvcard4invalid', 'onMyVcard4NotReceived');
        $this->registerEvent('myvcard', 'onMyVcard4');
    }

    function display()
    {
        $cd = new \Modl\ContactDAO();
        $me = $cd->get($this->user->getLogin());
        
        if($me == null) {
            $this->view->assign(
                'getvcard',
                $this->genCallAjax('ajaxGetVcard')
                );
            $this->view->assign('form', $this->prepareForm(new \modl\Contact()));
        } else {
            $this->view->assign('form', $this->prepareForm($me));
        }
    }
    
    function prepareForm($me) {
        $vcardform = $this->tpl();

        $vcardform->assign('me',       $me);
        $vcardform->assign('desc',     trim($me->description));
        $vcardform->assign('gender',   getGender());
        $vcardform->assign('marital',  getMarital());
        $vcardform->assign('countries',getCountries());
        
        $vcardform->assign(
            'submit',
            $this->genCallAjax('ajaxVcardSubmit', "movim_form_to_json('vcard4')")
            );
            
        $vcardform->assign(
            'privacy',
            $this->genCallAjax('ajaxChangePrivacy', "this.checked")
            );

        // The datepicker arrays
        $days = $months = $years = array();
        for($i=1; $i<= 31; $i++) {
            if($i < 10){
                $j = '0'.$i;
            } else {
                $j = (string)$i;
            }
            $days[$i] = $j;
        }
        for($i=1; $i<= 12; $i++) {
            if($i < 10){
                $j = '0'.$i;
            } else {
                $j = (string)$i;
            }
            $m = getMonths();
            
            $months[$j] = $m[$i];
        }
        for($i=date('o'); $i>= 1920; $i--) { array_push($years, $i); }

        $vcardform->assign('days',   $days);
        $vcardform->assign('months', $months);
        $vcardform->assign('years',  $years);
        
        return $vcardform->draw('_vcard4_form', true);
    }
    
    function onMyVcard4($c) {
        $html = $this->prepareForm($c);

        Notification::appendNotification($this->__('vcard.updated'), 'success');
        
        RPC::call('movim_fill', 'vcard_form', $html);
        RPC::commit();
    }

    function onMyVcard4Received() {
        RPC::call('movim_button_reset', '#vcard4validate');
        Notification::appendNotification($this->__('vcard.updated'), 'success');
        RPC::commit();
    }
    
    function onMyVcard4NotReceived() {
        Notification::appendNotification($this->__('vcard.not_updated'), 'error');
        RPC::commit();
    }
    
    function ajaxGetVcard() {
        $r = new Get;
        $r->setTo($this->user->getLogin())
          ->setMe()
          ->request();
    }

    function ajaxVcardSubmit($vcard) {
        # Format it ISO 8601:
        if($vcard->year->value  != -1 
        && $vcard->month->value != -1 
        && $vcard->day->value   != -1)
            $vcard->date->value = 
                    $vcard->year->value.'-'.
                    $vcard->month->value.'-'.
                    $vcard->day->value;
            
        unset($vcard->year->value);
        unset($vcard->month->value);
        unset($vcard->day->value);

        $cd = new \Modl\ContactDAO();
        $c = $cd->get($this->user->getLogin());

        if($c == null)
            $c = new \Modl\Contact();
            
        $c->jid     = $this->user->getLogin();
        
        if(isset($vcard->date->value)) {
            $c->date = $vcard->date->value;
        } 
        
        $c->name    = $vcard->name->value;
        $c->fn      = $vcard->fn->value;
        $c->url     = $vcard->url->value;
        
        $c->gender  = $vcard->gender->value;
        $c->marital = $vcard->marital->value;

        $c->adrlocality     = $vcard->locality->value;
        $c->adrcountry      = $vcard->country->value;

        $c->email   = $vcard->email->value;

        $c->twitter = $vcard->twitter->value;
        $c->skype   = $vcard->skype->value;
        $c->yahoo   = $vcard->yahoo->value;
        
        $c->description     = trim($vcard->desc->value);
            
        $cd = new \Modl\ContactDAO();
        $cd->set($c);
        
        $r = new Set;
        $r->setData($c)->request();

        $r = new Moxl\Xec\Action\Vcard\Set;
        $r->setData($vcard)->request();
    }

    function ajaxChangePrivacy($value) {
        if($value == true) {
            \modl\Privacy::set($this->user->getLogin(), 1);
            Notification::appendNotification($this->__('vcard.public'), 'success');
        } else {
            \modl\Privacy::set($this->user->getLogin(), 0);
            Notification::appendNotification($this->__('vcard.restricted'), 'success');
        }
    }
}
