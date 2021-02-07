<?php
function build_calendar($month, $year){
    $mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');
    $daysOfWeek = array('Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb');
    $firstDayOfMonth = mktime(0,0,0,$month,1,$year);
    $numberDays = date('t', $firstDayOfMonth);
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];
    $dateToday = date('Y-m-d');

    setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');
    $mes = strftime('%B', strtotime($monthName));

    $calendar = "<table class='table table-bordered'>";
    $calendar.= "<center class='text-capitalize'><h2>$mes - $year</h2>";
    $calendar.= " <a class='btn btn-xs btn-primary' href='?month=".date('m', mktime(0, 0, 0, $month-1, 1, $year))."&year=".date('Y', mktime(0, 0, 0, $month-1, 1, $year))."'>Mês Anterior</a>";
    
    $calendar.= " <a class='btn btn-xs btn-primary' href='?month=".date('m')."&year=".date('Y')."'>Mês Atual</a>";
    $calendar.= " <a class='btn btn-xs btn-primary' href='?month=".date('m', mktime(0, 0, 0, $month+1, 1, $year))."&year=".date('Y', mktime(0, 0, 0, $month+1, 1, $year))."'>Próximo Mês</a></center><br>";
    $calendar.= "<tr>";
    
    foreach($daysOfWeek as $day){
        $calendar.="<th class='header'>$day</th>";
    }
    
    $calendar.= "</tr><tr>";
    
    if($dayOfWeek > 0){
        for($k=0;$k<$dayOfWeek;$k++){
            $calendar.="<td class='desmarcada'></td>";
        }
    }
    
    $currentDay = 1;
    $month = str_pad($month, 2, "0", STR_PAD_LEFT);
    
    while($currentDay <= $numberDays){
        
        if($dayOfWeek == 7){
            $dayOfWeek = 0;
            $calendar.= "</tr><tr>";
        }
        
        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";
        $dayname = strtolower(date('I', strtotime($date)));
        $eventNum = 0;
        $today = $date==date('Y-m-d')? "today": "";

        if($date<date('Y-m-d')){
            $calendar.= "<td><h4>$currentDay</h4>";
        }else{
            if($dayOfWeek == 0 || $dayOfWeek == 6){
                $calendar.= "<td class='desmarcada'><h4>$currentDay</h4>";
            }else{
                $calendar.= "<td class='$today'><h4>$currentDay</h4> <a href='teste_modal.php?date=".$date."' class='btn btn-success btn-xs'>+</a>";
            }
        }
        $calendar.= "</td>";
        $currentDay++;
        $dayOfWeek++;
    }
    
    if($dayOfWeek != 7){
        $remainingDays = 7-$dayOfWeek;
        for($i=0;$i<$remainingDays;$i++){
            $calendar.= "<td class='desmarcada'></td>";
        }
    }
    $calendar.="</tr>";
    $calendar.="</table>";
    echo $calendar;
}
?>
<html lang="pt-br">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale-1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <style>
            table{
                table-layout: fixed;
            }
            td{
                width: 33%;
            }
            .today{
                background: yellow;
            }
            .desmarcada{
                background-color: #eee;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php
                        $dateComponents = getdate();
                        if(isset($_GET['month']) && isset($_GET['year'])){
                            $month = $_GET['month'];
                            $year = $_GET['year'];
                        }else{
                            $month = $dateComponents['mon'];
                            $year = $dateComponents['year'];
                        }
                        echo build_calendar($month,$year);
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>