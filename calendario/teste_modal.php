<?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');
if(isset($_GET['date'])){
    $date = $_GET['date'];
    $stmt = $mysqli->prepare("select * from bookings where date = ?");
    $stmt->bind_param('s', $date);
    $bookings = array();
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows>0){
            while($row = $result->fetch_assoc()){
                $bookings[] = $row['timeslot'];
            }
            $stmt->close();
        }
    }
}

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $timeslot = $_POST['timeslot'];
    $stmt = $mysqli->prepare("select * from bookings where date = ? AND timeslot = ?");
    $stmt->bind_param('ss', $date, $timeslot);
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows>0){
            $msg = "<div class='alert alert-danger'>Este horário já foi agendado por outra pessoa.</div>";
        }else{
            $stmt = $mysqli->prepare("INSERT INTO bookings (name, timeslot, email, date) VALUES (?,?,?,?)");
            $stmt->bind_param('ssss', $name, $timeslot, $email, $date);
            $stmt->execute();
            $msg = "<div class='alert alert-success'>Horário reservado com sucesso</div>";
            $bookings[]=$timeslot;
            $stmt->close();
            $mysqli->close();
        }
    }
}

$duration = 120;
$cleanup = 0;
$start = "13:00";
$end = "19:00";


function timeslots($duration, $cleanup, $start, $end){
    $start = new DateTime($start);
    $end = new DateTime($end);
    $interval = new DateInterval ("PT".$duration."M");
    $cleanupInterval = new DateInterval("PT".$cleanup."M");
    $slots = array();

    for($intStart = $start; $intStart<$end; $intStart->add($interval)->add($cleanupInterval)){
        $endPeriod = clone $intStart;
        $endPeriod->add($interval);
        if($endPeriod>$end){
            break;
        }

        $slots[] = $intStart->format("H:i")." - ".$endPeriod->format("H:i");

    }

    return $slots;

}


?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <style>
  .modal-header, h4, .close {
    background-color: #5cb85c;
    color:white !important;
    text-align: center;
    font-size: 30px;
  }
  .modal-footer {
    background-color: #f9f9f9;
  }
  </style>
</head>
<body>

<div class="container">
<h1 class="text-center">Data da reserva: <?php echo date('d/m/Y', strtotime($date)); ?></h1><hr>
            <div class="row">
                <form method="post">
                <div class="col-md-12">
                    <?php echo isset($msg)?$msg:""; ?>
                </div>
                <?php $timeslots = timeslots($duration, $cleanup, $start, $end);
                    foreach($timeslots as $ts){
                ?>
                <div class="col-md-2">
                    <div class="form-group">
                        <?php if(in_array($ts, $bookings)){ ?>
                            <button class="btn btn-danger">Horário Reservado</button>
                        <?php }else{ ?>
                            <button type="button" class="btn btn-success book" data-toggle="modal" data-target="#teste" data-timeslot="<?php echo $ts; ?>"><?php echo $ts; ?></button>
                        <?php } ?>
                    </div>
                </div>
                
                <?php } ?>
                </form>
                </div>
  <div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Reserva: <span id="slot"></span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="" method="post">
                                    <div class="form-group">
                                        <label for="">Você escolheu o horário: </label>
                                        <input required type="text" readonly name="timeslot" id="timeslot" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label style="display:none;" style>Name</label>
                                        <input style="display:none;" type="text" name="name" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label style="display:none;">Email</label>
                                        <input style="display:none;" type="email" name="email" class="form-control">
                                        <p>Tem Certeza?</p>
                                    </div>
                                    <div class="formgroup pull-left">
                                        <button class="btn btn-primary" type="submit" name="submit">Sim</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Não</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


  </div> 
</div>
 
<script>

$(document).ready(function(){
  $(".book").click(function(){
    var timeslot = $(this).attr("data-timeslot");
    $("#slot").html(timeslot);
    $("#timeslot").val(timeslot);
    $("#myModal").modal();
  });
});

</script>
</body>
</html>
