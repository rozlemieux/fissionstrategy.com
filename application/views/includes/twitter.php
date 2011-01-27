<?php

function timeAgo($tm,$rcs = 0)	{
    $cur_tm = time(); 
    $dif = $cur_tm-$tm;
    $pds = array('second','minute','hour','day','week','month','year','decade');
    $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
    for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);
    $no = floor($no); if($no <> 1) $pds[$v] .='s'; $x=sprintf("%d %s ",$no,$pds[$v]);
    if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
					
    return $x;
  }
				
function twitterify($ret) {
    $ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
    $ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
    $ret = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $ret);
    $ret = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $ret);
    return $ret;
}

function file_age( $file ) {

    if( !file_exists($file) )
        return 99999999;
				        
    $s = stat( $file );
    return time() - $s['mtime'];
}

$cache_file = $this->config->item('base_dir') . "application/views/includes/fs.twitter.json.cache";
$s = file_age( $cache_file );

if( $s > 900 || strlen(file_get_contents($cache_file)) == 0 ) { //cache the feed locally for 15 minutes

    require_once($this->config->item('base_dir') . 'application/views/includes/class-snoopy.php');

    $snoopy = new Snoopy;
    $snoopy->read_timeout = 4;
    $snoopy->fetch("http://twitter.com/statuses/user_timeline/fissionstrategy.json?count=2");
    $twitter_feed = $snoopy->results;
    file_put_contents($cache_file, $twitter_feed);
} else {
    $twitter_feed = file_get_contents($cache_file);
}

$twitterdata = json_decode($twitter_feed,true);

if (!isset($twitterdata->error) 
    && isset($twitterdata) && isset($twitterdata[0]) && isset($twitterdata[0]['text']) && strlen($twitterdata[0]['text']) > 0) {

        echo '"'.twitterify($twitterdata[0]['text']).'"';
        echo '
						<p>
							<small class="date">'.timeAgo(strtotime($twitterdata[0]['created_at'])).' ago</small>
						</p>
					';
    }

?>

<a href="http://twitter.com/fissionstrategy" class="more">follow us</a>
