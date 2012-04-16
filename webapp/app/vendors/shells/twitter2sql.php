<?php 
// This file imports data from sqlite to mysql

uses('model'.DS.'model');

App::import('Sanitize');
App::import('Core','Helper');
App::import('Helper','Link');

class Twitter2sqlShell extends Shell {
    var $times = array();
    var $uses = array('Search','Twitterdata');
  
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
        $this->importdb();
        
        //$this->process();
    
    }

    function importdb() {
        $page = 0;
        
        $fp = fopen('file.csv', 'w');
        fwrite($fp, "LOCK TABLES twitts WRITE;\n");
        
        while( true ) {
//            $last_id = $this->Twitterdata->query("SELECT MAX(id) AS max FROM twitts");
//            $last_id = $last_id[0][0]['max']+0;

            //$this->out("Descargando id: $last_id ");

//            $conditions = array('orderby' => 'id ASC', 'conditions' => array('Search.last_id >=' => $last_id), 'limit' => 10, 'page' => $page );
            $conditions = array('orderby' => 'id ASC', 'limit' => 10, 'page' => $page );
            $page++;

//            $count = $this->Search->find('count', $conditions);
           

            $newSearch = $this->Search->find('all', $conditions);

            if ( empty($newSearch) )
                break;

            foreach ( $newSearch as $newRes ) {
                $this->out("Descargando id: " . $newRes['Search']['id'] . " + last_id: " . $newRes['Search']['last_id']);
                $data = json_decode( $newRes['Search']['data'], true );

                $results = $data['results'];
                foreach ( $results as $res ) {

                    $twitt = array(
                        'id' => $res['id'], 
                        'user_id' => $res['from_user_id'], 
                        'content' => Sanitize::escape(utf8_decode($res['text'])),
                        'json' => Sanitize::escape(json_encode($res)),
                        'date' => date('c', strtotime($res['created_at'])),
                        'code' => mycode($res['text']),
                        'hash' => myhash($twitt['code'])  
                    );


                    //saving data
                    fwrite($fp, "INSERT INTO twitts (" . implode(",",array_keys($twitt)) . ") VALUES ('" . implode("','",array_values($twitt)) . "'); \n");
                    
                    //pr($twitt);
                    /*
                    $this->Twitterdata->create($twitt);
                    if ( $this->Twitterdata->save($twitt, false) ) {
                        //$this->out("Guardando id " . $twitt['id']);
                    }
                    else {
                        $this->out("Error: id " . $twitt['id']);
                    }
                    */
                }

               // pr( $results );

               // 
            }


            //query("SELECT Twitter.* FROM twitter as Twitter WHERE last_id > $last_id LIMIT 1");

            

            //break;
        }
        
        fwrite($fp, "UNLOCK TABLES;\n");
        fclose($fp);
        
        
    
        $this->finalize();
    }
    
    function process() {
        $page = 0;
        while( true ) {
            $conditions = array('orderby' => 'id ASC', 'limit' => 100, 'page' => $page );
            $page++;
            
            $newSearch = $this->Twitterdata->find('all', $conditions);
            $this->out("Descargando pagina: " . $page);
            if ( empty($newSearch) )
                break;
                
            foreach ( $newSearch as $twitt ) {
                $tmp = $twitt;
                $tmp['Twitterdata']['code'] = mycode($twitt['Twitterdata']['content']);
                $tmp['Twitterdata']['hash'] = myhash($tmp['Twitterdata']['code']);
                $this->Twitterdata->save($tmp);
            }
        }
        $this->finalize();
    
    }
    
    function duplicates() {
        //SELECT count(hash) as c, hash, code, content FROM `twitterdata` group by hash having c>1 order by c desc
        //también hice borrar mensajes en aleman :)
        //DELETE  FROM `twitterdata` WHERE `json` LIKE '%"iso_language_code":"de"%' 
        
        //poblar twitts
        //INSERT INTO twitts SELECT * FROM twitterdata ORDER BY RAND() LIMIT 5000
    }

}


function myhash($str) {
    return sha1($str);
}

function mycode($string, $replacement = '-') {

    $map = array(
             '/À|Á|Â/' => 'A',
             '/È|É|Ê|Ë/' => 'E',
             '/Ì|Í|Î/' => 'I',
             '/Ò|Ó|Ô/' => 'O',
             '/Ù|Ú|Û/' => 'U',
             '/Ñ/' => 'N',
             '/à|á|å|â/iu' => 'a',
             '/è|é|ê|ẽ|ë/iu' => 'e',
             '/ì|í|î/iu' => 'i',
             '/ò|ó|ô|ø/iu' => 'o',
             '/ù|ú|ů|û/iu' => 'u',
             '/ç/iu' => 'c',
             '/ñ/iu' => 'n',
             '/ä|æ/iu' => 'ae',
             '/ö/iu' => 'oe',
             '/ü/iu' => 'ue',
             '/Ä/iu' => 'Ae',
             '/Ü/iu' => 'Ue',
             '/Ö/iu' => 'Oe',
             '/ß/iu' => 'ss',
             '/[^\w\s]/iu' => ' ',
             '/\\s+/iu' => $replacement
         );
         
     $string = preg_replace(array_keys($map), array_values($map), $string);
     $string = trim($string,$replacement);
     $string = strtoupper($string);
     return $string;
}

class Search extends Model {
    var $useDbConfig = 'sqlite';
    var $useTable = 'twitter';
}

class Twitterdata extends Model {
    var $useDbConfig = 'default';
    var $useTable = 'twitts';
}


?>
