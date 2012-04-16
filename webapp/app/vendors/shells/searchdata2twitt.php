<?php 
// This file imports data from sqlite to mysql

uses('model'.DS.'model');

App::import('Sanitize');
App::import('Core','Helper');
App::import('Helper','Link');

class Searchdata2twittShell extends Shell {
    var $times = array();
    var $uses = array('Twitt','Searchdata');
  
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
        $this->process();
    
    }

    function process() {
        $page = 0;
        
        $last_id = 0;
        $last_query = '';
              
        while( true ) {
//            $last_id = $this->Twitterdata->query("SELECT MAX(id) AS max FROM twitts");
//            $last_id = $last_id[0][0]['max']+0;

            //$this->out("Descargando id: $last_id ");

//            $conditions = array('orderby' => 'id ASC', 'conditions' => array('Search.last_id >=' => $last_id), 'limit' => 10, 'page' => $page );
            $conditions = array('orderby' => 'last_id ASC', 'limit' => 100, 'page' => $page );
            $page++;

//            $count = $this->Search->find('count', $conditions);
           

            $newSearch = $this->Searchdata->find('all', $conditions);

            if ( empty($newSearch) )
                break;

            foreach ( $newSearch as $newRes ) {
                if ($newRes['Searchdata']['data'] == '__controldata__please__not__use__this__') continue;
                
                $error = false;
                
                $this->out("Descargando id: " . $newRes['Searchdata']['id'] . " + last_id: " . $newRes['Searchdata']['last_id']);
                $data = json_decode( $newRes['Searchdata']['data'], true );

                $results = $data['results'];
                foreach ( $results as $res ) {
                    $twitt = array(
                        'id' => $res['id'], 
                        'user_id' => $res['from_user_id'], 
                        'content' => $res['text'],
                        'json' =>json_encode($res),
                        'date' => date('c', strtotime($res['created_at']))
                        //,
                        //'code' => mycode($res['text']),
                        //'hash' => myhash($twitt['code'])  
                    );


                    //saving data

                    //pr($twitt);


                    $this->Twitt->create($twitt);
                    if ( $this->Twitt->save($twitt, false) ) {
                        //$this->out("Guardando id " . $twitt['id']);
                    }
                    else {
                        $this->out("Error: id " . $twitt['id']);
                        $error = true;
                    }

                }
                
                if (!$error) {
                    if ($last_id != $newRes['Searchdata']['last_id'] && $last_query != $newRes['Searchdata']['query']) {
                        $last_id = $newRes['Searchdata']['last_id'];
                        $last_query = $newRes['Searchdata']['query'];
                        
                        $controldata = $newRes['Searchdata'];
                        $controldata['data'] ='__controldata__please__not__use__this__';
                        
                        $this->Searchdata->create($controldata);
                        if ($this->Searchdata->save($controldata)) {
                            $this->Searchdata->remove($newRes['Searchdata']['id']);                        
                        }
                    }
                    else {
                        $this->Searchdata->remove($newRes['Searchdata']['id']);
                    }
                }
                else {
                    $this->out("Error in Searchdata " . $newRes['Searchdata']['id'] . " this row will not be removed");
                }

               // pr( $results );

            }
            
            



            //break;
        }
        

    
        $this->finalize();
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

class Searchdata extends Model {
    var $useDbConfig = 'default';
    var $useTable = 'searchdata';
}



?>
