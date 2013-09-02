<?php

namespace modl;

class NodeDAO extends ModlSQL { 
    /*function create() {
        $sql = '
        drop table if exists Node';
        
        $this->_db->query($sql);

        $sql = '
        create table if not exists Node (
          serverid varchar(45) NOT NULL,
          nodeid varchar(45) NOT NULL,
          title varchar(128) DEFAULT NULL,
          config text,
          updated datetime NOT NULL
        ) CHARACTER SET utf8 COLLATE utf8_bin';
        $this->_db->query($sql);     
    }*/
    
    function set(Node $node) {
        $this->_sql = '
            update node
            set config = :config,
                title = :title,
                updated = :updated
            where serverid = :serverid
                and nodeid = :nodeid';
        
        $this->prepare(
            'Node', 
            array(                
                'config' => $node->config,
                'title'  => $node->title,
                'updated'=> $node->updated,
                'serverid' => $node->serverid,
                'nodeid'   => $node->nodeid
            )
        );
        
        $this->run('Node');
        
        if(!$this->_effective) {
            $this->_sql = '
                insert into node
                (serverid,
                nodeid,
                config,
                title,
                updated
                )
                values(
                    :serverid,
                    :nodeid,
                    :config,
                    :title,
                    :updated
                    )';
            
            $this->prepare(
                'Node', 
                array(
                    'config' => $node->config,
                    'title'  => $node->title,
                    'updated'=> $node->updated,
                    'serverid' => $node->serverid,
                    'nodeid'   => $node->nodeid
                )
            );
            
            $this->run('Node');
        }
        
        /*$request = $this->_db->prepare('
            update Node
            set config = ?,
                title = ?,
                updated = ?
            where serverid = ?
                and nodeid = ?');
    
        $request->bind_param(
            'sssss',
                $node->config,
                $node->title,
                $node->updated,
                $node->serverid,
                $node->nodeid
            );
              
        $result = $request->execute();
        
        if($this->_db->affected_rows == 0) {
            $request = $this->_db->prepare('
                insert into Node
                (serverid,
                nodeid,
                config,
                title,
                updated
                )
                values(
                    ?,?,?,?,?
                    )');
                    
            $request->bind_param(
                'sssss',
                $node->serverid,
                $node->nodeid,
                $node->config,
                $node->title,
                $node->updated
                );
            $request->execute();
        }
        
        $request->close();*/
    }
    
    function getServers() {
        /*$sql = '
            select serverid, count(nodeid) as number 
            from Node 
            where nodeid not like \'urn:xmpp:microblog:0:comments/%\' 
            group by serverid
            order by number desc';
            
        $resultset = $this->_db->query($sql);       
        return $this->mapper('Server', $this->_db->query($sql));*/
        
        $this->_sql = '
            select serverid, count(nodeid) as number 
            from node 
            where nodeid not like \'urn:xmpp:microblog:0:comments/%\' 
            group by serverid
            order by number desc';
            
        $this->prepare(
            'Server'
        );
            
        return $this->run('Server'); 
    }
    
    function getNodes($serverid) {
        /*$serverid = $this->_db->real_escape_string($serverid); 
        $sql = '
            select Node.*, count(P.nodeid) as number from Node 
            left outer join (select * from Postn where Postn.from = \''.$serverid.'\' group by nodeid) as P 
            on Node.nodeid = P.node
            where serverid=\''.$serverid.'\' 
            group by nodeid
            order by number desc';

        return $this->mapper('Node', $this->_db->query($sql));
        */
        
        /*$this->_sql = '
            select node.*, count(P.nodeid) as number from node 
            left outer join (select * from postn where postn.jid = :serverid) as P 
            on node.nodeid = P.node
            where serverid= :serverid
            order by number desc';
        */
        
        $this->_sql = '
            select * from node 
                where serverid= :serverid';
            
        $this->prepare(
            'Node',
            array(
                'serverid' => $serverid
            )
        );
            
        return $this->run('Node'); 
    }

    function deleteNodes($serverid) {
        /*$serverid = $this->_db->real_escape_string($serverid); 
        $sql = '
            delete from Node
            where serverid=\''.$serverid.'\' ';
            
        return $this->_db->query($sql);    */ 

        $this->_sql = '
            delete from node
            where serverid= :serverid';
            
        $this->prepare(
            'Node',
            array(
                'serverid' => $serverid
            )
        );
            
        return $this->run('Node'); 
    }

    function deleteNode($serverid, $nodeid) {
        /*$serverid = $this->_db->real_escape_string($serverid); 
        $nodeid = $this->_db->real_escape_string($nodeid);
        $sql = '
            delete from Node
            where serverid=\''.$serverid.'\' and
                  nodeid=\''.$nodeid.'\'';
            
        return $this->_db->query($sql);     */

        $this->_sql = '
            delete from node
            where serverid = :serverid
                and nodeid = :nodeid';
            
        $this->prepare(
            'Node',
            array(
                'serverid' => $serverid,
                'nodeid' => $nodeid
            )
        );
            
        return $this->run('Node'); 
    }
    
    function getNode($serverid, $nodeid) {
        /*$sql = '
            select * from Node
            where nodeid = \''.$nodeid.'\'
                and serverid = \''.$serverid.'\'';
                
        return $this->mapper('Node', $this->_db->query($sql), 'item');*/
        
        $this->_sql = '
            select * from node
            where 
                nodeid = :nodeid
                and serverid = :serverid';
        
        $this->prepare(
            'Node', 
            array(
                'nodeid' => $nodeid,
                'serverid' => $serverid
            )
        );
        
        return $this->run('Node', 'item');
    }
}