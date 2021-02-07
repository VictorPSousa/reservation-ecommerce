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
            $msg = "<div class='alert alert-danger'>Already Booked</div>";
        }else{
            $stmt = $mysqli->prepare("INSERT INTO bookings (name, timeslot, email, date) VALUES (?,?,?,?)");
            $stmt->bind_param('ssss', $name, $timeslot, $email, $date);
            $stmt->execute();
            $msg = "<div class='alert alert-success'>Booking Successfull</div>";
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

        $slots[] = $intStart->format("H:iA")."-".$endPeriod->format("H:iA");

    }

    return $slots;

}


?>

<!doctype html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <h1 class="text-center">Book for Date: <?php echo date('d F Y', strtotime($date)); ?></h1><hr>
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
                            <button class="btn btn-danger"><?= $ts; ?></button>
                        <?php }else{ ?>
                            <button class="btn btn-success book" id="book" data-timeslot="<?= $ts; ?>"><?= $ts; ?></button>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
                </form>
            </div>
        </div>
        <div id="teste" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Booking: <span id="slot">/</span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="" method="post">
                                    <div class="form-group">
                                        <label for="">Timeslot</label>
                                        <input required type="text" readonly name="timeslot" id="timeslot" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Name</label>
                                        <input required type="text" name="name" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Email</label>
                                        <input required type="email" name="email" class="form-control">
                                    </div>
                                    <div class="formgroup pull-right">
                                        <button class="btn btn-primary" type="submit" name="submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script>   
           $(".book").click(function(){
                var timeslot = $(this).attr("data-timeslot");
                $("#slot").html(timeslot);
                $("#timeslot").val(timeslot);
                $("#teste").modal("show");
           })
        </script>
    </body>
</html>