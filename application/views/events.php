<div class="main">
  <div class="content">
    <h1>Events</h1>
     <div style="padding-right: 30px; margin-bottom: 40px">
Our team = experienced practitioners, senior strategists, cutting edge geeks, long time organizers, bloggers, coders, designers, Tweeters and more.  Our senior team members are in high demand for speaking engagements and private sessions in part because our excitement about what we can do together is contagious. Learn more about the Fission crew on our <a href="/team">Team page</a>.
    </div>

    <?php 
     if ($calendar) 
         echo $calendar; 

     if ($events) {
         foreach ($events as $i => $event) {
             if (strpos($event->date, '00:00:00') > 0)
                 $date = date("F j, Y", strtotime($event->date));
             else
                 $date = date("F j, Y, g:i a", strtotime($event->date));
             if ($event->repeat) 
                 $date .= ' - ' . date("F j, Y", strtotime($event->date) + (($event->repeat - 1) * (24 * 60 * 60)));

             echo '<h2>' . $event->title . '</h2>';
             echo '<div style="font-size:12px;font-weight:bold">' . $date . "</div>";
             $des = $event->description;
             $des = str_ireplace('roz', '<a href="/team/member/roz lemieux">roz</a>', $des);
             $des = str_ireplace('beka', '<a href="/team/member/Beka Economopoulos">beka</a>', $des);
             $des = str_ireplace('cheryl ', '<a href="/team/member/Cheryl Contee">cheryl</a> ', $des);
             echo '<div style="font-size:13px">' . $des . "</div>";
         }
     }

     ?>

  </div>
