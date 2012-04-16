
<div class="metrics index">



<?php

echo $javascript->codeBlock('$($.date_input.initialize);');

echo $form->create('Metrics', array('action' => 'index'));


echo $form->input('keywords', array('readonly' => 'true' ) ) ;
//echo $form->input('keywords');

echo $form->input('startTime', array('class' => 'date_input', 
'readonly' => 'true'));

echo $form->input('endTime', array('class' => 'date_input', 
'readonly' => 'true'));

$options = array('5 mins', '10 mins', '1 hour', '12 hour', '1 day', '3 days', '1 week', '2 weeks', '1 month');
$options = array_combine($options, $options);
echo $form->input('period',array('options' => $options, '3 days'));

#Activate this if you want to show the original data in a table
#echo $form->input('showTable', array('type' => 'checkbox'));

echo $form->submit('Analyze Opinions');
echo $form->end();


//pr($results);

if ($results != null):

?>
  <script type="text/javascript" src="http://www.google.com/jsapi"></script>
  <script type="text/javascript">
    google.load('visualization', '1', {packages: ['annotatedtimeline']});
    
    function drawVisualizationPOSNEG() {
    
    var data = new google.visualization.DataTable();
    data.addColumn('datetime', 'Date');
    data.addColumn('number', 'Positive Opinions');
    data.addColumn('number', 'Negative Opinions');
    data.addRows([

<?php
    $first = true;
    foreach ($results as $res) {
        if ($first) $first = false;
        else echo ", \n";        
       
        echo "[new Date('".date('r',strtotime($res['time']))."')";
        echo ",". $res['post1'].", ".$res['negt1']."]";
    }
?>  
    ]);
    
    var annotatedtimeline = new google.visualization.AnnotatedTimeLine(
          document.getElementById('visualizationPOSNEG'));
    annotatedtimeline.draw(data, {'displayAnnotations': true});
    }
    
    
    function drawVisualizationMETRICS() {
    
    var data = new google.visualization.DataTable();
    data.addColumn('datetime', 'Date');
    data.addColumn('number', 'M_pos');
    data.addColumn('number', 'M_neg');
    data.addColumn('number', 'N_pos');
    data.addColumn('number', 'N_neg');
    data.addColumn('number', 'C_pos2neg');
    data.addColumn('number', 'C_neg2pos');
    data.addRows([

<?php
    $first = true;
    foreach ($results as $res) {
        if ($first) $first = false;
        else echo ", \n";        
       
        echo "[new Date('".date('r',strtotime($res['time']))."')";
        echo ",". $res['M_pos'];
        echo ",". $res['M_neg'];
        echo ",". $res['N_pos'];
        echo ",". $res['N_neg'];
        echo ",". $res['C_pos2neg'];
        echo ", ".$res['C_neg2pos']."]";
    }
?>  
    ]);
    
    var annotatedtimeline = new google.visualization.AnnotatedTimeLine(
          document.getElementById('visualizationMETRICS'));
    annotatedtimeline.draw(data, {'displayAnnotations': true});
    }
    
    
    google.setOnLoadCallback(drawVisualizationPOSNEG);
    google.setOnLoadCallback(drawVisualizationMETRICS);
       
</script>       
       
 
 
<h2>Positive-Negative opinions</h2>       
<div id="visualizationPOSNEG" style="width: 820px; height: 250px;"></div>     

<h2>Metrics changes</h2> 
<div id="visualizationMETRICS" style="width: 820px; height: 250px;"></div>   
<h3>Explicación de métricas</h3>
<ul>
<li><strong>M_pos</strong>: usuarios que mantienen una opinión positiva entre cada periodo</li>
<li><strong>M_neg</strong>: usuarios que mantienen una opinión negative entre cada periodo</li>
<li><strong>N_pos</strong>: usuarios nuevos con opinión positiva</li>
<li><strong>N_neg</strong>: usuarios nuevos con opinión negativa</li>
<li><strong>C_pos2neg</strong>: usuarios que cambiaron de opinion positiva a negativa </li>
<li><strong>C_neg2pos</strong>: usuarios que combiaron de opinion negativa a positiva</li>
</ul>


<?php

if (isset($this->data['Metrics']['showTable']) && $this->data['Metrics']['showTable'] == true ) 
{
    echo '<h2>Table with metrics</h2>';

#    Displaying PARAMS
    echo '<h3>Params used to compute metrics</h3>';
    
    echo '<table>';
    echo '<thead><tr>';
    echo '<td>param</td><td>value</td>';
    echo '</tr></thead>';
    echo '<tbody>';
    
    foreach ( $this->data['Metrics'] as $key => $value ) 
    {
        echo '<tr>';
        echo '<td>'.$key.'</td><td>'.$value.'</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    
    
#    Displaying DATA
    echo '<h3>Metrics data</h3>';
    echo '<table>';
    echo '<thead><tr>';
    echo '<td>date</td>'; // extra for better time representation
    foreach ( array_keys($results[0]) as $key )
        echo '<td>'. $key . '</td>';
    echo '</tr></thead>';
        
    echo '<tbody>';
    
    foreach ( $results as $res ) 
    {
        echo '<tr>';
        echo '<td>'. date('n/j/Y G:i', strtotime($res['time'])) .'</td>'; // extra for better time representation
        foreach ( $res as $value )
            echo '<td>'. $value . '</td>';
        echo '</tr>';
    }
    

    echo '</tbody>';
    
    echo '</table>';


}







endif;

?>

</div>

<!--
 <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
    google.load('visualization', '1', {packages: ['annotatedtimeline']});
    function drawVisualization2() {

var data = new google.visualization.DataTable();
    data.addColumn('datetime', 'Date');
    data.addColumn('number', 'Positive');
    data.addColumn('number', 'Negative');
      data.addRows([
  [new Date("Sat, 17 Oct 2009 00:00:00 -0300"), 11,44],
  [new Date("Sat, 18 Oct 2009 00:00:00 -0300"), 2,55],
  [new Date("Sat, 19 Oct 2009 00:00:00 -0300"), 2,66],
  [new Date("Sat, 20 Oct 2009 00:00:00 -0300"), 2,77],
  [new Date("Sat, 21 Oct 2009 00:00:00 -0300"), {v:7, f:'7.000'},11]
]);
      var annotatedtimeline = new google.visualization.AnnotatedTimeLine(
          document.getElementById('visualization2'));
      annotatedtimeline.draw(data, {'displayAnnotations': true});
    }
    
    google.setOnLoadCallback(drawVisualization2);
  </script>
<div id="visualization2" style="width: 800px; height: 400px;"></div>

-->
