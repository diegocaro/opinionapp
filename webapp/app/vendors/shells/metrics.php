<?php 

class MetricsShell extends Shell {
    var $times = array();
    var $uses = array('Opinion','Twitt');

    //var $t1 = array('2009-11-16','2009-11-18');
    //var $t2 = array('2010-01-11','2010-01-13');  
      
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
        $t1 = array('2009-10-08','2009-11-01');
    
        $this->out('Encuesta CEP Octubre 2009');
        $mfrei = $this->single('frei @noticiasfrei #frei', $t1);
        $mpinera = $this->single('piñera @sebastianpinera #piñera #pinera pinera', $t1);
        $mmeo = $this->single("@marco2010 #meo marco henriquez ominami", $t1);
        $marrate = $this->single("@jorgearrate @arrate arrate #arrate", $t1);
        
        echo "MEO: ";
        print_r($mmeo);
        
        echo "Arrate: ";
        print_r($marrate);
        
        echo "Frei: ";
        print_r($mfrei);
        
        echo "Piñera: ";
        print_r($mpinera);
        
        $this->hr();
        $this->out('Comparación candidatos 1er y 2do debate');

        $t1 = array('2009-11-16','2009-11-18');
        $t2 = array('2010-01-11','2010-01-13');
    
        $mfrei = $this->calcule('frei @noticiasfrei #frei', $t1, $t2);
        $mpinera = $this->calcule('piñera @sebastianpinera #piñera #pinera pinera', $t1, $t2);
        $mmeo = $this->calcule("@marco2010 #meo marco henriquez ominami", $t1, $t2);
        $marrate = $this->calcule("@jorgearrate @arrate arrate #arrate", $t1, $t2);
        
        echo "MEO: ";
        print_r($mmeo);
        
        echo "Arrate: ";
        print_r($marrate);
        
        echo "Frei: ";
        print_r($mfrei);
        
        echo "Piñera: ";
        print_r($mpinera);
        
        $this->finalize();
    
    }


    function calcule($terms, $time1, $time2) {
        $m = array(); //metrics
        
#        $sql = "SELECT a.user_id, a.class, b.content FROM opinions as a LEFT JOIN (twitts as b) ON (a.twitt_id = b.id) 
#                    WHERE a.class != 'not' AND 
#                    MATCH (b.content) AGAINST('%s') AND 
#                    a.date BETWEEN '%s' AND '%s'
#                    GROUP BY a.user_id, a.class;";
                    
        $sql = "SELECT a.user_id, a.class FROM opinions as a LEFT JOIN (twitts as b) ON (a.twitt_id = b.id) 
                    WHERE a.class != 'not' AND 
                    MATCH (b.content) AGAINST('%s') AND 
                    a.date BETWEEN '%s' AND '%s'
                    GROUP BY a.user_id, a.class;";
    
        $t1SQL = sprintf($sql, $terms, $time1[0], $time1[1]);
        $t2SQL = sprintf($sql, $terms, $time2[0], $time2[1]);

        $t1 = $this->Opinion->query($t1SQL);
        $t2 = $this->Opinion->query($t2SQL);

        
        $t1Users = array( 'pos' => array(), 'neg' => array() );
        $t2Users = array( 'pos' => array(), 'neg' => array() );
        
        foreach ($t1 as $t) {
            $t1Users[ $t['a']['class'] ][] = $t['a']['user_id'];
        }
        
        foreach ($t2 as $t) {
            $t2Users[ $t['a']['class'] ][] = $t['a']['user_id'];
        }
        
        //cleaning users that have positive and negative opinions
        $bothopinionst1 = array_intersect( $t1Users['pos'], $t1Users['neg']);
        $t1Users['pos'] = array_diff($t1Users['pos'], $bothopinionst1);
        $t1Users['neg'] = array_diff($t1Users['neg'], $bothopinionst1);
        
        $bothopinionst2 = array_intersect( $t2Users['pos'], $t2Users['neg']);
        $t2Users['pos'] = array_diff($t2Users['pos'], $bothopinionst2);
        $t2Users['neg'] = array_diff($t2Users['neg'], $bothopinionst2);
        

        //calculating metrics
        
        $m['M_pos'] = count( array_intersect($t1Users['pos'], $t2Users['pos']) );
        $m['M_neg'] = count( array_intersect($t1Users['neg'], $t2Users['neg']) );
        $m['N_pos'] = count( $t1Users['pos'] ) - $m['M_pos'];
        $m['N_neg'] = count( $t1Users['neg'] ) - $m['M_neg'];
        $m['C_pos2neg'] = count( array_intersect($t1Users['pos'], $t2Users['neg']) );
        $m['C_neg2pos'] = count( array_intersect($t1Users['neg'], $t2Users['pos']) );                
        
        $m['post1'] = count($t1Users['pos']);
        $m['negt1'] = count($t1Users['neg']);
        $m['post2'] = count($t2Users['pos']);
        $m['negt2'] = count($t2Users['neg']);
        
        $m['t1'] = count($t1);
        $m['t2'] = count($t2);
        
        $m['bothopinonst1'] = count($bothopinionst1);
        $m['bothopinonst2'] = count($bothopinionst2);
        
        return $m;
    }
    
    
    // single comparation
    function single($terms, $time1) {
        $m = array(); //metrics
        
#        $sql = "SELECT a.user_id, a.class, b.content FROM opinions as a LEFT JOIN (twitts as b) ON (a.twitt_id = b.id) 
#                    WHERE MATCH (b.content) AGAINST('%s') AND 
#                    a.date BETWEEN '%s' AND '%s'
#                    GROUP BY a.user_id, a.class;";

        $sql = "SELECT a.user_id, a.class FROM opinions as a LEFT JOIN (twitts as b) ON (a.twitt_id = b.id) 
                    WHERE MATCH (b.content) AGAINST('%s') AND 
                    a.date BETWEEN '%s' AND '%s'
                    GROUP BY a.user_id, a.class;";
    
        $t1SQL = sprintf($sql, $terms, $time1[0], $time1[1]);

        $t1 = $this->Opinion->query($t1SQL);

        $t1Users = array( 'pos' => array(), 'neg' => array(), 'not' => array() );
        
        foreach ($t1 as $t) {
            $t1Users[ $t['a']['class'] ][] = $t['a']['user_id'];
        }
        
        $m['pos'] = count($t1Users['pos']);
        $m['neg'] = count($t1Users['neg']);
        $m['not'] = count($t1Users['not']);

        $m['total'] = count($t1);
        
        return $m;
    }
#    
#    
#    // EXTRA FUNCTIONS
#    
#    function candidatos() {
#        $t1 = array('2009-11-16','2009-11-18');
#        $t2 = array('2010-01-11','2010-01-13');  
#        
#        $mfrei = $this->calcule('frei @noticiasfrei #frei', $t1, $t2);
#        $mpinera = $this->calcule('piñera @sebastianpinera #piñera', $t1, $t2);
#        $mmeo = $this->calcule("@marco2010 #meo marco henriquez ominami", $t1, $t2);
#        $marrate = $this->calcule("@jorgearrate @arrate arrate #arrate", $t1, $t2);
#        
#        echo "MEO: ";
#        print_r($mmeo);
#        
#        echo "Arrate: ";
#        print_r($marrate);
#        
#        echo "Frei: ";
#        print_r($mfrei);
#        
#        echo "Piñera: ";
#        print_r($mpinera);
#        
#        $this->finalize();
#    }


#    
#    // ESPECIAL COMPARE FREI PIÑERA
#    function compare() {
#        $t1 = array('2009-11-16','2009-11-18');
#        $t2 = array('2010-01-11','2010-01-13');  
#        
#        $sql = "SELECT a.user_id, a.class, b.content FROM opinions as a LEFT JOIN (twitts as b) ON (a.twitt_id = b.id) 
#                    WHERE a.class != 'not' AND 
#                    MATCH (b.content) AGAINST('%s') AND 
#                    a.date BETWEEN '%s' AND '%s'
#                    GROUP BY a.user_id, a.class;";
#    
#        //FREI
#        $t1SQL = sprintf($sql, 'frei @noticiasfrei #frei', $t1, $t2);
#        $t2SQL = sprintf($sql, 'frei @noticiasfrei #frei', $t1, $t2);

#        $t1 = $this->Opinion->query($t1SQL);
#        $t2 = $this->Opinion->query($t2SQL);

#        $t1Users = array( 'pos' => array(), 'neg' => array() );
#        $t2Users = array( 'pos' => array(), 'neg' => array() );
#        
#        foreach ($t1 as $t) {
#            $t1Users[ $t['a']['class'] ][] = $t['a']['user_id'];
#        }
#        
#        foreach ($t2 as $t) {
#            $t2Users[ $t['a']['class'] ][] = $t['a']['user_id'];
#        }
#        
#        $posFrei1 = $t1Users['pos'];
#        $posFrei2 = $t2Users['pos'];
#        
#        $negFrei1 = $t1Users['neg'];
#        $negFrei2 = $t2Users['neg'];

#        //PIÑERA
#        $t1SQL = sprintf($sql, 'piñera @sebastianpinera #piñera', $t1, $t2);
#        $t2SQL = sprintf($sql, 'piñera @sebastianpinera #piñera', $t1, $t2);
#        
#        $t1 = $this->Opinion->query($t1SQL);
#        $t2 = $this->Opinion->query($t2SQL);

#        $t1Users = array( 'pos' => array(), 'neg' => array() );
#        $t2Users = array( 'pos' => array(), 'neg' => array() );
#        
#        foreach ($t1 as $t) {
#            $t1Users[ $t['a']['class'] ][] = $t['a']['user_id'];
#        }
#        
#        foreach ($t2 as $t) {
#            $t2Users[ $t['a']['class'] ][] = $t['a']['user_id'];
#        }
#        
#        $posPine1 = $t1Users['pos'];
#        $posPine2 = $t2Users['pos'];
#        
#        $negPine1 = $t1Users['neg'];
#        $negPine2 = $t2Users['neg'];        
#        
#        // Interseccion de los que votaron negativo y positivo al mismo candidato
#        $inter1 = array_intersect($posFrei1, $negFrei1);
#        $inter2 = array_intersect($posPine1, $negPine1);
#        $inter = $inter1 + $inter2;
#        $posFrei1 = array_diff($posFrei1, $inter);
#        $posPine1 = array_diff($posPine1, $inter);
#        
#        $negFrei1 = array_diff($negFrei1, $inter);
#        $negPine1 = array_diff($negPine1, $inter);
#        
#        $inter1 = array_intersect($posFrei2, $negFrei2);
#        $inter2 = array_intersect($posPine2, $negPine2);
#        $inter = $inter1 + $inter2;
#        $posFrei2 = array_diff($posFrei2, $inter);
#        $posPine2 = array_diff($posPine2, $inter);
#        
#        $negFrei2 = array_diff($negFrei2, $inter);
#        $negPine2 = array_diff($negPine2, $inter);
#    
#        // Interseccion de los que votaron por los dos candidatos
#        $inter = array_intersect($posFrei1, $posPine1);
#        $posFrei1 = array_diff($posFrei1, $inter);
#        $posPine1 = array_diff($posPine1, $inter);
#        
#        $inter = array_intersect($posFrei2, $posPine2);
#        $posFrei2 = array_diff($posFrei2, $inter);
#        $posPine2 = array_diff($posPine2, $inter);

#        $inter = array_intersect($negFrei1, $negPine1);
#        $negFrei1 = array_diff($negFrei1, $inter);
#        $negPine1 = array_diff($negPine1, $inter);
#        
#        $inter = array_intersect($negFrei2, $negPine2);
#        $negFrei2 = array_diff($negFrei2, $inter);
#        $negPine2 = array_diff($negPine2, $inter);
#                          
#    
#        $m['posFrei1'] = count($posFrei1);
#        $m['posPine1'] = count($posPine1);
#        $m['posFrei2'] = count($posFrei2);
#        $m['posPine2'] = count($posPine2);
#        
#        $m['negFrei1'] = count($negFrei1);
#        $m['negPine1'] = count($negPine1);
#        $m['negFrei2'] = count($negFrei2);
#        $m['negPine2'] = count($negPine2);
#        
#        print_r($m);
#    }

}


?>
