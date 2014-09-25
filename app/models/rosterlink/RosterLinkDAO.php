<?php

namespace modl;

class RosterLinkDAO extends SQL {
    function set(RosterLink $r) { 
        $this->_sql = '
            insert into rosterlink
            (
                session, 
                jid, 
                rostername, 
                rosterask, 
                rostersubscription,
                realname,
                groupname,
                chaton)
                values (
                    :session, 
                    :jid, 
                    :rostername, 
                    :rosterask, 
                    :rostersubscription,
                    :realname,
                    :groupname,
                    :chaton
                    )';
        
        $this->prepare(
            'RosterLink', 
            array(
                'session'       => $this->_user,
                'jid'           => $r->jid,
                'rostername'    => $r->rostername,
                'rosterask'     => $r->rosterask,
                'rostersubscription'     => $r->rostersubscription,
                'realname'      => $r->realname,
                'groupname'     => $r->groupname,
                'chaton'        => $r->chaton
            )
        );
        
        return $this->run('RosterLink');
    }
    
    function setList($arr) {
        $this->_sql = '
            insert into rosterlink
            (
                session, 
                jid, 
                rostername, 
                rosterask, 
                rostersubscription,
                realname,
                groupname,
                chaton)
                values
            ';     
               
        $i = 0;
        $params = array();
            
        foreach($arr as $r) {
            $this->_sql .= "
                (
                :session_$i, 
                :jid_$i, 
                :rostername_$i, 
                :rosterask_$i, 
                :rostersubscription_$i,
                :realname_$i,
                :groupname_$i,
                :chaton_$i
                ),";
        
            $params = array_merge(
                $params,
                array(
                    "session_$i"       => $this->_user,
                    "jid_$i"           => $r->jid,
                    "rostername_$i"    => $r->rostername,
                    "rosterask_$i"     => $r->rosterask,
                    "rostersubscription_$i"     => $r->rostersubscription,
                    "realname_$i"      => $r->realname,
                    "groupname_$i"     => $r->groupname,
                    "chaton_$i"        => $r->chaton
                )
            );
            
            $i++;
        }
        
        $this->_sql = substr($this->_sql, 0, -1);
        
        $this->prepare(
            'RosterLink',
            $params
        );
        
        return $this->run('RosterLink');
    }
    
    function update(RosterLink $r) {  
        $this->_sql = '
            update rosterlink
            set rostername  = :rostername,
                rosterask   = :rosterask,
                rostersubscription = :rostersubscription,
                realname    = :realname,
                groupname   = :groupname,
                chaton      = :chaton
            where session   = :session
                and jid     = :jid';
        
        $this->prepare(
            'RosterLink', 
            array(
                'session'       => $this->_user,
                'jid'           => $r->jid,
                'rostername'    => $r->rostername,
                'rosterask'     => $r->rosterask,
                'rostersubscription'     => $r->rostersubscription,
                'realname'      => $r->realname,
                'groupname'     => $r->groupname,
                'chaton'        => $r->chaton
            )
        );
        
        return $this->run('RosterLink');
    }
    
    function setNow(RosterLink $r) {
        $this->update($r);
        
        if(!$this->_effective)
            $this->set($r);
    }
    
    function setChat($jid, $chaton) {
        $this->_sql = '
            update rosterlink
            set chaton      = :chaton
            where session   = :session
                and jid     = :jid';
        
        $this->prepare(
            'RosterLink', 
            array(
                'session'       => $this->_user,
                'jid'           => $jid,
                'chaton'        => $chaton
            )
        );
        
        return $this->run('RosterLink');
    }
    
    function get($jid) {
        $this->_sql = '
            select *
            from rosterlink
            where session=:session
                and jid = :jid';
        
        $this->prepare(
            'RosterLink', 
            array(
                'session' => $this->_user,
                'jid' => $jid,
            )
        );
        
        return $this->run('RosterLink', 'item');
    }
    
    function getGroups() {
        $this->_sql = '
            select groupname
            from rosterlink
            where session = :session
            group by groupname';
        
        $this->prepare(
            'RosterLink', 
            array(
                'session' => $this->_user
            )
        );
        
        $results = $this->run('RosterLink');

        if(is_array($results)) {
            $arr = array();
            
            foreach($results as $r)
                array_push($arr, $r->groupname);
                
            return $arr;
        } else {
            return false;
        }
    }
    
    function getRoster($to = null) {
        if($to != null) 
            $session = $to;
        else
            $session = $this->_user;

        $this->_sql = '
            select *
            from rosterlink
            where session=:session';
        
        $this->prepare(
            'RosterLink', 
            array(
                'session' => $session
            )
        );
        
        return $this->run('RosterLink');
    }
    
    function getChats() {
        $this->_sql = '
            select *
            from rosterlink
            where session=:session
            and chaton > 0';
        
        $this->prepare(
            'RosterLink', 
            array(
                'session' => $this->_user
            )
        );
        
        return $this->run('RosterLink');
    }
    
    function clearRosterLink() {
        $this->_sql = '
            delete from rosterlink
            where session = :session';

        $this->prepare(
            'RosterLink',
            array(
                'session' => $this->_user
            )
        );
            
        return $this->run('RosterLink');
    }
    
    function delete($jid) {
        $this->_sql = '
            delete from rosterlink
            where session = :session
            and jid = :jid';

        $this->prepare(
            'RosterLink',
            array(
                'session' => $this->_user,
                'jid' => $jid
            )
        );
            
        return $this->run('RosterLink');
    }
}
