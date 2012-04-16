<?php 

class DetectionShell extends Shell {
    var $times = array();
    var $uses = array('Opinion','Twitt');
    var $host = "localhost";
    var $port = "50000";
    
    var $socket;
    var $connection;
  
    function initialize() {
        $this->times['start'] = microtime(true);
        parent::initialize();
    }
    
    function finalize() {
        $this->times['end'] = microtime(true);
        $duration = round($this->times['end'] - $this->times['start'], 2);
        $this->hr();
        $this->out('Script took '.$duration.' seconds.');

    }


    function main() {
        $this->opendetection();
            
        $page = 1;
        $limit = 200;
        
#        $conditions = array('Twitt.date BETWEEN ? AND ?' => array('2009-11-16','2009-11-18')); //primer debate
#        $conditions = array('Twitt.date BETWEEN ? AND ?' => array('2010-01-11','2010-01-13'));  //segundo debate
#        $conditions = array('Twitt.date BETWEEN ? AND ?' => array('2009-10-08','2009-11-01')); //encuesta CEP
        
        
        $conditions = array('Opinion.id IS NULL');        //all twitts
        $joins = array(
                        array(
                            'table' => 'opinions',
                            'alias' => 'Opinion',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'Opinion.twitt_id = Twitt.id',
                            )
                        )
                    );

        
        $allcount = $this->Twitt->find('count', array('conditions' => $conditions, 'joins' => $joins));
        $allpages = ceil($allcount/$limit);

        while ( true ) {
            $params = array(
	                'conditions' => $conditions, //array of conditions
	                'recursive' => 0,
	                'fields' => array('id', 'user_id', 'content', 'date'),
                    'limit' => $limit,
                    'joins' => $joins,
	                'page' => $page
                );
            
            
                
            $twitts = $this->Twitt->find('all', $params);
            if ( empty($twitts) )
                break;
            
            $this->out("\nPage $page/$allpages\n");

            foreach ($twitts as $t) {
                $class = $this->detection($t['Twitt']['content']);
                print $class;
                $data = array('twitt_id' => $t['Twitt']['id'], 'user_id' => $t['Twitt']['user_id'], 'date' => $t['Twitt']['date'], 'class' => $class);
                $this->Opinion->create($data);
                if ($this->Opinion->save($data, false)) {
                
                }
                else
                {
                    $this->out("Error in ".$data['twitt_id']);   
                }
            }            
            
            
            $page++;
        }
        $this->closedetection();
        $this->finalize();
    }
 
    
    function opendetection() {
        $this->socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
        $this->connection = socket_connect($this->socket,$this->host,$this->port); //connect
    }
    
    function closedetection() {
        socket_close($this->socket); 
    }
        
    function detection($tweet) {
        $class = "";
        
        socket_write($this->socket, $tweet);
        $class = socket_read($this->socket,1024);

        return $class;
    }

}


?>
