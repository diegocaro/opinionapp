<?php
class MetricsController extends AppController {

    var $name = 'Metrics';
    var $uses = array('Opinion','Twitt');
    var $helpers = array('Html', 'Form', 'Ajax', 'Session');
    

    function index()
    {
        $results = null;
        $error = false;
        
        if ( isset($this->params['url']['data']) ) {
            $this->data = $this->params['url']['data'];
        }
        
        $startTime = $this->data['Metrics']['startTime'];
        $endTime = $this->data['Metrics']['endTime'];
        $period = $this->data['Metrics']['period'];
        $keywords = $this->data['Metrics']['keywords'];
        
        // Limite de veces que se puede
        $_period = strtotime($period) - time();
        $_startTime = strtotime($startTime);
        $_endTime = strtotime($endTime);// + $period;
        
        if ( ($_endTime - $_startTime)/$_period > 120 )
        {
            $error = true;
            $this->Session->setFlash('Please decrease the range between dates, or increase the time of analysis period.');
        }
        
        if ( !empty($keywords) && !empty($startTime) && !empty($endTime) && !empty($period) && !$error ) {
            $results = $this->getMetrics( $keywords, $startTime, $endTime, $period );
        }
        
        $this->set('results', &$results);
        
    }

#    function results2json(&$metrics, $nameCols) 
#    {
#     //This doesnt work becacause....... SEE ABOVE!    
##    // please, see this http://code.google.com/apis/visualization/documentation/reference.html#DataTable_literal_constructor_example
#    
#        $jj = array();
#        
#        $cols = array();
#        $cols[] = array('id' => 'date', 'label' => 'Date', 'type' => 'date');
#        foreach ($nameCols as $name) {
#            $cols[] = array('id' => $name, 'label' => $name, 'type' => 'number');
#        }
#        
#        $rows = array();
        // THIS LINE CAN't BE IN PHP!! fucking php...
#        $rows[] = array('c' => 
#                    array('v' => 'new Date(2004)', 'v' => '4')
#                    );

##        foreach ($metrics as $metric) {
##            $c = array();
##            $c['v'] = $metric['time'];
##            
##            foreach ($nameCols as $name) {
##                $c['v'] = $metric[$name];
##            }
##            
##            $rows[] = array('c' => $c);
##        }
#        
#        $jj['cols'] = $cols;
#        $jj['rows'] = $rows;
#        
#        echo json_encode($jj);
#    
#    }

    
#    
#    Example use for getMetrics method
#    
    function exampleofgetmetrics() {
        $startTime = '2009-10-08';
        $endTime = '2009-11-01';
#        $endTime = '2009-10-12';
        $period = '3 days';
        $keywords = '@marco2010 #meo marco henriquez ominami';
        
        $a = $this->getMetrics( $keywords, $startTime, $endTime, $period );
        pr($a);
        
#        echo $this->results2json($a, array('M_pos'));
        
    }
              
    function correlationTest() {
        
        $this->initialize();
        
        $t1 = array('2009-10-08','2009-11-01');
    
        echo('<h1>Encuesta CEP Octubre 2009</h1>');
        $mfrei = $this->single('frei @noticiasfrei #frei', $t1);
        $mpinera = $this->single('piñera @sebastianpinera #piñera #pinera pinera', $t1);
        $mmeo = $this->single("@marco2010 #meo marco henriquez ominami", $t1);
        $marrate = $this->single("@jorgearrate @arrate arrate #arrate", $t1);
        
        echo "MEO: ";
        pr($mmeo);
        
        echo "Arrate: ";
        pr($marrate);
        
        echo "Frei: ";
        pr($mfrei);
        
        echo "Piñera: ";
        pr($mpinera);
        
        echo "<hr/>";
        echo('<h1>Comparación de opinión de candidatos, 1er debate vs. 2do debate</h1>');

        $t1 = array('2009-11-16','2009-11-18');
        $t2 = array('2010-01-11','2010-01-13');
        
        echo '<p>';
        echo('Periodo del primer debate comprendido entre '.$t1[0].' y '.$t1[1].'. ');
        echo('Periodo del segundo debate comprendido entre '.$t2[0].' y '.$t2[1].'. ');
        echo '</p>';
    
    
        $mfrei = $this->calcule('frei @noticiasfrei #frei', $t1, $t2);
        $mpinera = $this->calcule('piñera @sebastianpinera #piñera #pinera pinera', $t1, $t2);
        $mmeo = $this->calcule("@marco2010 #meo marco henriquez ominami", $t1, $t2);
        $marrate = $this->calcule("@jorgearrate @arrate arrate #arrate", $t1, $t2);
        
        echo "MEO: ";
        pr($mmeo);
        
        echo "Arrate: ";
        pr($marrate);
        
        echo "Frei: ";
        pr($mfrei);
        
        echo "Piñera: ";
        pr($mpinera);
        
        $this->finalize();
        $this->layout = 'ajax';
    }
    
    
    
    
    function getMetrics( $terms, $startTime, $endTime, $period ) {
        $period = strtotime($period) - time();
        
        $startTime = strtotime($startTime);
        $endTime = strtotime($endTime);// + $period;
        
        $time = $startTime;
        
        $m = array();
        
        while ($time < $endTime) {
            $k = array();
            
            $t1start = date('c', $time);
            $t1end = date('c', $time + $period );
            $t2start = $t1end;
            $t2end = date('c', $time + 2*$period );
            
            $t1 = array( $t1start, $t1end);
            $t2 = array( $t2start, $t2end);
            
            $k = $this->calcule( $terms, $t1, $t2);
            $k['time'] = $t1start;
            
            array_push($m, $k);
            $time += $period;
        }
        

        return $m;
    }
    
    
    
    // Returns an array with metrics between period of $time1 and $time2
    // Users that have positive and negative opinions are discarded
    //  
    //   $terms is a string with keywords for to retrieve messages with opinion
    //   $time1 is the first period of time to calculete metrics
    //              $time1 = array( $startDate, $endDate) 
    //   $time2 is the second period of time to calculete metrics
    //              $time2 = array( $startDate, $endDate)
    //
    // The parametres $time1 and $time2 can take any period of time, for 
    //   example a day vs. one week, or one month vs. one year.
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
        
        $m['userst1'] = count($t1);
        $m['userst2'] = count($t2);
        
        $m['bothopinonst1'] = count($bothopinionst1);
        $m['bothopinonst2'] = count($bothopinionst2);
        
        return $m;
    }
    
    
    // Devuelve la cantidad de usuarios con opinion positiva o negativa durante $time.
    // Si un usuario envia un mensaje positivo y otro negativo en el periodo de análisis, se contabiliza para positivo y negativo.
    //
    // $time1 = array( $fechaINICIO, $fechaTERMINO)
    //
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
    
    
    
    
    
    function initialize() {
        $this->times['start'] = microtime(true);
    }
    
    function finalize() {
        $this->times['end'] = microtime(true);
        $duration = round($this->times['end'] - $this->times['start'], 2);
        echo "<hr/>";
        echo('Script took '.$duration.' seconds.');

    }
}
?>
