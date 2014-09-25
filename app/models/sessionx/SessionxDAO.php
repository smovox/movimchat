<?php

namespace modl;

class SessionxDAO extends SQL {
    function init(Sessionx $s) {        
        $this->_sql = '
            update sessionx
            set username    = :username,
                password    = :password,
                ressource   = :ressource,
                rid         = :rid,
                sid         = :sid,
                id          = :id,
                url         = :url,
                port        = :port,
                host        = :host,
                domain      = :domain,
                config      = :config,
                active      = :active,
                start       = :start,
                timestamp   = :timestamp
            where session = :session';
        
        $this->prepare(
            'Sessionx', 
            array(
                'session'   => $s->session,
                'username'  => $s->username,
                'password'  => $s->password,
                'ressource' => $s->ressource,
                'rid'       => $s->rid,
                'sid'       => $s->sid,
                'id'        => $s->id,
                'url'       => $s->url,
                'port'      => $s->port,
                'host'      => $s->host,
                'domain'    => $s->domain,
                'config'    => $s->config,
                'active'    => $s->active,
                'start'     => $s->start,
                'timestamp' => $s->timestamp
                )
        );
        
        $this->run('Sessionx');
        
        if(!$this->_effective) {
            $this->_sql = '
                insert into sessionx
                (session,
                 username,
                 password,
                 ressource,
                 rid,
                 sid,
                 id,
                 url,
                 port,
                 host,
                 domain,
                 config,
                 active,
                 start,
                 timestamp)
                values
                (:session,
                 :username,
                 :password,
                 :ressource,
                 :rid,
                 :sid,
                 :id,
                 :url,
                 :port,
                 :host,
                 :domain,
                 :config,
                 :active,
                 :start,
                 :timestamp)';
            
            $this->prepare(
                'Sessionx', 
                array(
                    'session'   => $s->session,
                    'username'  => $s->username,
                    'password'  => $s->password,
                    'ressource' => $s->ressource,
                    'rid'       => $s->rid,
                    'sid'       => $s->sid,
                    'id'        => $s->id,
                    'url'       => $s->url,
                    'port'      => $s->port,
                    'host'      => $s->host,
                    'domain'    => $s->domain,
                    'config'    => $s->config,
                    'active'    => $s->active,
                    'start'     => $s->start,
                    'timestamp' => $s->timestamp
                )
            );
            
            $this->run('Sessionx');
        }
    }

    function update($session, $key, $value) {
        $this->_sql = '
            update sessionx
            set
                '.$key.'  = :'.$key.',
                timestamp = :timestamp
            where 
                session = :session';
        
        $this->prepare(
            'Sessionx', 
            array(
                'session'   => $session,
                $key        => $value,
                'timestamp' => date(DATE_ISO8601)
            )
        );

        $this->run('Sessionx');
    }

    function get($session) {
        $this->_sql = '
            select * from sessionx
            where 
                session = :session';
        
        $this->prepare(
            'Sessionx', 
            array(
                'session' => $session
            )
        );

        return $this->run('Sessionx', 'item');
    }

    function getId($session) {
        $this->_sql = '
            select id from sessionx
            where 
                session = :session';
        
        $this->prepare(
            'Sessionx', 
            array(
                'session' => $session
            )
        );
        
        $value = $this->run(null, 'array');
        $value = $value[0]['id'];

        $this->_sql = '
            update sessionx
            set
                id          = :id,
                timestamp   = :timestamp
            where 
                session = :session';
        
        $this->prepare(
            'Sessionx', 
            array(
                'session'   => $session,
                'id'        => $value+1,
                'timestamp' => date(DATE_ISO8601)
            )
        );

        $this->run();
        
        return $value;
    }

    function getRid($session) {
        $this->_sql = '
            select rid from sessionx
            where 
                session = :session';
        
        $this->prepare(
            'Sessionx', 
            array(
                'session' => $session
            )
        );
        
        $value = $this->run(null, 'array');
        $value = $value[0]['rid'];

        $this->_sql = '
            update sessionx
            set
                rid         = :rid,
                timestamp   = :timestamp
            where 
                session = :session';
        
        $this->prepare(
            'Sessionx', 
            array(
                'session' => $session,
                'rid' => $value+1,
                'timestamp' => date(DATE_ISO8601)
            )
        );

        $this->run();
        
        return $value;
    }

    function delete($session) {
        $this->_sql = '
            delete from sessionx
            where 
                session = :session';
        
        $this->prepare(
            'Sessionx', 
            array(
                'session' => $session
            )
        );
        
        return $this->run('Sessionx');
    }

    function clean() {
        $this->_sql = '
            delete from sessionx
            where timestamp < :timestamp';
        
        $this->prepare(
            'Sessionx', 
            array(
                'timestamp' => date(DATE_ISO8601, time() - 3600)
            )
        );

        $this->run('Sessionx');
    }

    function getAll() {
        $this->_sql = '
            select * from sessionx';
        
        $this->prepare(
            'Sessionx', 
            array()
        );

        return $this->run('Sessionx');        
    }

    function getConnected() {
        $this->_sql = '
            select count(*) from sessionx';

        $this->prepare(
            'Sessionx', 
            array(
                
            )
        );

        $results = $this->run(null, 'array');
        $results = array_values($results[0]);
        
        return (int)$results[0]; 
    }
}
