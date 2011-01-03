    <h1>Events</h1>

    <?php 
     if ($calendar)
         echo $calendar; 
     
     if ($events) {
         foreach ($events as $i => $event) {
             if (strpos($event->date, '00:00:00') > 0)
                 $date = date("F j, Y", strtotime($event->date));
             else
                 $date = date("F j, Y, g:i a", strtotime($event->date));
             echo '<div style="font-size:12px;font-weight:bold">' . $date . "</div>";
             echo '<div style="font-size:13px">' . $event->description . "</div>";
         }
     }

     ?>


