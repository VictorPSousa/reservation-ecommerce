<?php
   include("pdf-php/src/Cezpdf.php");
   $pdf = new Cezpdf(); 
   $pdf -> selectFont('pdf-php/src/fonts/Helvetica.afm'); 
   $pdf -> ezText('Bom dia');
     
   $pdf -> ezStream();
?>