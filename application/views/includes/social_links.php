<?php
$social_url = "";
$buzz_title = "";
$buzz_snippet = "";
$facebook_title = "";
?>
<span class="social">
  <script>var fbShare = {
    url: '<?php echo $social_url ?>',
    title: '<?php echo $facebook_title ?>'
    }</script>
  <script src="http://widgets.fbshare.me/files/fbshare.js"></script>
</span>						

<span class="social" style="margin-left: 10px;">
  <script type="text/javascript">
    tweetmeme_url = '<?php echo $social_url ?>';
    tweetmeme_source = 'greenmynevada';
    tweetmeme_service = 'bit.ly';
  </script>
  <script type="text/javascript" src="http://tweetmeme.com/i/scripts/button.js"></script>
</span>

<span class="social" style="margin-left: 10px;">
<a href="http://www.google.com/reader/link?url=<?php echo $social_url ?>&title=<?php echo $buzz_title ?>&snippet=<?php echo $buzz_snippet; ?>&srcURL=http://USABudgetDiscussion.org">
  <img src="/img/img-3.gif" alt="">
</a>
</span>

<span class="social" style="margin-left: 10px;">
<a target="_blank" href="mailto:?subject=Green my Nevada"><img src="/img/img-4.gif" alt=""></a>
</a>
</span>

