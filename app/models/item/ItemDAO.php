<?php

namespace modl;

class ItemDAO extends SQL { 
    function set(Item $item) {
        $this->_sql = '
            update item
            set name   = :name,
                creator = :creator,
                created = :created,
                updated = :updated,
                description = :description
            where server = :server
                and jid  = :jid
                and node = :node';
        
        $this->prepare(
            'Item', 
            array(
                'name'          => $item->name,
                'created'       => $item->created,
                'updated'       => $item->updated,
                'server'        => $item->server,
                'jid'           => $item->jid,
                'node'          => $item->node,
                'creator'       => $item->creator,
                'description'   => $item->description
            )
        );
        
        $this->run('Item');
        
        if(!$this->_effective) {
            $this->_sql = '
                insert into item
                (server,
                creator,
                node,
                jid,
                name,
                created,
                updated,
                description
                )
                values(
                    :server,
                    :creator,
                    :node,
                    :jid,
                    :name,
                    :created,
                    :updated,
                    :description
                    )';
            
            $this->prepare(
                'Item', 
                array(
                    'name'          => $item->name,
                    'creator'       => $item->creator,
                    'created'       => $item->created,
                    'updated'       => $item->updated,
                    'server'        => $item->server,
                    'jid'           => $item->jid,
                    'node'          => $item->node,
                    'description'   => $item->description
                )
            );
            
            $this->run('Item');
        }
    }
    
    function getServers() {
        $this->_sql = '
            select server, count(node) as number 
            from item
            where node not like :node
            group by server
            order by number desc';
            
        $this->prepare(
            'Item',
            array(
                'node' => 'urn:xmpp:microblog:0:comments%'
            )
        );
            
        return $this->run('Server'); 
    }
    
    function getConferenceServers() {
        $this->_sql = '
            select server, count(node) as number 
            from item
            where node not like :node
            and node = :name
            group by server
            order by number desc';
            
        $this->prepare(
            'Item',
            array(
                'node' => 'urn:xmpp:microblog:0:comments%',
                // It's a hack to affect an empty string
                'name' => ''
            )
        );
            
        return $this->run('Server'); 
    }
    
    function getGroupServers() {
        $this->_sql = '
            select server, count(node) as number 
            from item
            where node not like :node
            and node != :name
            group by server
            order by number desc';
            
        $this->prepare(
            'Item',
            array(
                'node' => 'urn:xmpp:microblog:0:comments%',
                // Little hack here too
                'name' => ''
            )
        );
            
        return $this->run('Server'); 
    }
    
    function getItems($server) {
        
        $this->_sql = '
            select * from item
            left outer join (
                select node, count(node) as num from postn
                where jid = :server
                group by node) as p
            on p.node = item.node
            left outer join (select server, node, subscription from subscription where jid = :node) 
                as s on s.server = item.server 
                and s.node = item.node
            where item.server= :server
            order by name, item.node
            ';
            
        $this->prepare(
            'Item',
            array(
                // Dirty hack, using node param to inject the session key
                'node' => $this->_user,
                'server' => $server
            )
        );
            
        return $this->run('Item'); 
    }

    function getUpdatedItems($limitf = false, $limitr = false) {
        $this->_sql = '
            select * from item natural join (
                select distinct node, max(updated) as num from postn
                where node not like :node
                group by node
                order by node) as post
                order by num desc
            ';

        if($limitr) 
            $this->_sql = $this->_sql.' limit '.$limitr.' offset '.$limitf;
            
        $this->prepare(
            'Item',
            array(
                'node'      => 'urn:xmpp:microblog%'
            )
        );
            
        return $this->run('Item'); 
    }

    function deleteItems($server) {
        $this->_sql = '
            delete from item
            where server= :server';
            
        $this->prepare(
            'Item',
            array(
                'server' => $server
            )
        );
            
        return $this->run('Item'); 
    }

    function deleteItem($server, $item) {
        $this->_sql = '
            delete from item
            where server = :server
                and node = :node';
            
        $this->prepare(
            'Item',
            array(
                'server' => $server,
                'node' => $item
            )
        );
            
        return $this->run('Item'); 
    }
    
    function getItem($server, $item) {
        $this->_sql = '
            select * from item
            where 
                node = :node
                and server = :server';
        
        $this->prepare(
            'Item', 
            array(
                'node' => $item,
                'server' => $server
            )
        );
        
        return $this->run('Item', 'item');
    }
}
