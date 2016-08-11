<?php
date_default_timezone_set($config['timezone']);
$audioFormats = array('.mp3', '.wav', '.ogg', '.flac', '.m4a');
$filepath =$interview->media_url;
$rights = (string)$interview->rights;
$usage = (string)$interview->usage;
$contactemail = '';
$contactlink = '';
$copyrightholder = '';
$protocol = 'https';
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') {
    $protocol = 'http';
}
$host = $_SERVER['HTTP_HOST'];
$uri = $_SERVER['REQUEST_URI'];
$baseurl = "$protocol://$host$uri";
$extraCss = null;
if (isset($config[$interview->repository])) {
    $repoConfig = $config[$interview->repository];
    $contactemail = $repoConfig['contactemail'];
    $contactlink = $repoConfig['contactlink'];
    $copyrightholder = $repoConfig['copyrightholder'];
    if (isset($repoConfig['open_graph_image']) && $repoConfig['open_graph_image'] <> '') {
        $openGraphImage = $repoConfig['open_graph_image'];
    }
    if (isset($repoConfig['open_graph_description']) && $repoConfig['open_graph_description'] <> '') {
        $openGraphDescription = $repoConfig['open_graph_description'];
    }

    if (isset($repoConfig['css']) && strlen($repoConfig['css']) > 0) {
        $extraCss = $repoConfig['css'];
    }
}
$seriesLink = (string)$interview->series_link;
$collectionLink = (string)$interview->collection_link;
$lang = (string)$interview->translate;

$gaScript = null;
if (isset($repoConfig['ga_tracking_id'])) {
    $gaScript = <<<GASCRIPT
<script type="text/javascript">
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', '{$repoConfig['ga_tracking_id']}', '{$repoConfig['ga_host']}');
ga('send', 'pageview');
</script>
GASCRIPT;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title><?php echo $interview->title; ?></title>
    <link rel="stylesheet" href="css/viewer.css" type="text/css" />
    <?php if (isset($extraCss)) { ?>
    <link rel="stylesheet" href="css/<?php echo $extraCss ?>" type="text/css" />
    <?php
} ?>
    <link rel="stylesheet" href="css/jquery-ui.toggleSwitch.css" type="text/css" />
    <link rel="stylesheet" href="css/jquery-ui-1.8.16.custom.css" type="text/css" />
    <link rel="stylesheet" href="css/font-awesome.css">
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui.toggleSwitch.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/viewer.js"></script>
    <meta property="og:title" content="<?php echo $interview->title; ?>" />
    <meta property="og:url" content="<?php echo $baseurl ?>">
    <?php if (isset($openGraphImage)) { ?>
    <meta property="og:image" content="<?php echo "$baseurl/$openGraphImage" ?>">
    <?php
} ?>
    <?php if (isset($openGraphDescription)) { ?>
    <meta property="og:description" content="<?php echo "$openGraphDescription" ?>">
    <?php
} ?>
  </head>
  <body>
<script type="text/javascript">
var jumpToTime = null;
if (location.href.search('#segment') > -1) {
  var jumpToTime = parseInt(location.href.replace(/(.*)#segment/i, ""));
  if (isNaN(jumpToTime)) {
    jumpToTime = 0;
  }
}
</script>
<?php if (in_array(substr(strtolower($filepath), -4, 4), $audioFormats)) { ?>
    <div id="header">
    <?php
} else { ?>
    <div id="headervid">
    <?php
} ?>
        <?php if(isset($config[$interview->repository])): ?>
        <img id="headerimg"
             src="<?php echo $config[$interview->repository]['footerimg'];?>"
             alt="<?php echo $config[$interview->repository]['footerimgalt'];?>" />
        <?php
endif; ?>
    <div class="center">
      <h1><?php echo $interview->title; ?></h1>
      <h2 id="secondaryMetaData">
        <div>
          <strong><?php echo $interview->repository; ?></strong><br />
          <?php echo $interview->interviewer; ?>, Interviewer |
          <?php echo $interview->accession; ?><br />
          <?php if (isset($interview->collection_link) && (string)$interview->collection_link != '') { ?>
          <a href="<?php echo $interview->collection_link?>"><?php echo $interview->collection?></a> |
          <?php
} else { ?>
          <?php echo $interview->collection; ?> |
          <?php
} ?>
          <?php if (isset($interview->series_link) && (string)$interview->series_link != '') { ?>
          <a href="<?php echo $interview->series_link?>"><?php echo $interview->series?></a>
          <?php
} else { ?>
          <?php echo $interview->series; ?>
          <?php
} ?>
        </div>
      </h2>
      <div id="audio-panel">
        <?php include_once 'tmpl/player_'.$interview->playername.'.tmpl.php'; ?>
      </div>
    </div>
    </div>
    <div id="main">
      <div id="main-panels">
    <div id="content-panel">
      <div id="holder-panel"></div>
      <div id="transcript-panel" class="transcript-panel">
        <?php echo $interview->transcript; ?>
      </div>
      <div id="index-panel" class="index-panel">
        <?php echo $interview->index; ?>
      </div>
    </div>
    <div id="searchbox-panel"><?php include_once 'tmpl/search.tmpl.php'; ?></div>
    </div>
    </div>
    <div id="footer">
      <div id="footer-metadata">
        <?php if (!empty($rights)) { ?>
        <p><span><h3><a href="#" id="lnkRights">View Rights Statement</a></h3>
        <div id="rightsStatement"><?php echo $rights; ?></div></span></p>
        <?php
} else { ?>
        <p><span><h3>View Rights Statement</h3></span></p>
        <?php
} ?>
        <?php if (!empty($usage)) { ?>
        <p><span><h3><a href="#" id="lnkUsage">View Usage Statement</a></h3>
        <div id="usageStatement"><?php echo $usage; ?></div></span></p>
        <?php
} else { ?>
        <p><span><h3>View Usage Statement</h3></span></p>
        <?php
} ?>
        <?php if (!empty($collectionLink)) { ?>
        <p><span><h3>Collection Link: <a
          href="<?php echo $interview->collection_link?>"><?php echo $interview->collection?></a></h3></span></p>
        <?php
} ?>
        <?php if (!empty($seriesLink)) { ?>
        <p><span><h3>Series Link: <a
          href="<?php echo $interview->series_link?>"><?php echo $interview->series?></a></h3></span></p>
        <?php
} ?>
        <p><span><h3>Contact Us: <a href="mailto:<?php echo $contactemail ?>"><?php echo $contactemail ?></a> |
        <a href="<?php echo $contactlink ?>"><?php echo $contactlink ?></a></h3></span></p>
        </div>
        <div id="footer-copyright">
          <small id="copyright"><span>&copy; <?php echo Date("Y") ?></span><?php echo $copyrightholder ?></small>
        </div>
        <div id="footer-logo">
          <img alt="Powered by OHMS logo" src="imgs/ohms_logo.png" border="0"/>
        </div>
        <br clear="both" />
      </div>
<script type="text/javascript" src="js/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="js/jquery.scrollTo-min.js"></script>
<script type="text/javascript" src="js/viewer_<?php echo  $interview->viewerjs;?>.js"></script>
<link rel="stylesheet" href="js/fancybox_2_1_5/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<link rel="stylesheet" href="skin/jplayer.blue.monday.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/fancybox_2_1_5/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet"
      href="js/fancybox_2_1_5/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
<script type="text/javascript" src="js/fancybox_2_1_5/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="js/fancybox_2_1_5/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<link rel="stylesheet"
      href="js/fancybox_2_1_5/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<script type="text/javascript" src="js/fancybox_2_1_5/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
<script type="text/javascript">
$(document).ready(function() {
  jQuery('a.indexSegmentLink').on('click', function (e) {
    var linkContainer = '#segmentLink' + jQuery(e.target).data('timestamp');
    e.preventDefault();
    if (jQuery(linkContainer).css("display") == "none") {
      jQuery(linkContainer).fadeIn(1000);
    } else {
      jQuery(linkContainer).fadeOut();
    }

    return false;
  });

  jQuery('.segmentLinkTextBox').on('click', function () {
    jQuery(this).select();
  });

  if(jumpToTime !== null) {
    jQuery('div.point').each(function (index) {
      if (parseInt(jQuery(this).find('a.indexJumpLink').data('timestamp')) == jumpToTime) {
        jumpLink = jQuery(this).find('a.indexJumpLink');
        jQuery('#accordionHolder').accordion({active: index});
        jQuery('#accordionHolder-alt').accordion({active: index});
        var interval = setInterval(function() {
          <?php
switch ($interview->playername) {
    case 'youtube': ?>
          if (player !== undefined &&
            player.getCurrentTime !== undefined && player.getCurrentTime() == jumpToTime) {
          <?php
        break;
    case 'brightcove': ?>
          if (modVP !== undefined &&
            modVP.getVideoPosition !== undefined &&
            Math.floor(modVP.getVideoPosition(false)) == jumpToTime) {
          <?php
        break;
    case 'kaltura': ?>
          if (kdp !== undefined && kdp.evaluate('{video.player.currentTime}') == jumpToTime) {
          <?php
        break;
    default: ?>
          if(Math.floor(jQuery('#subjectPlayer').data('jPlayer').status.currentTime) == jumpToTime) {
          <?php
        break;
} ?>
            clearInterval(interval);
          } else {
            jumpLink.click();
          }
        }, 500);
        jQuery(this).find('a.indexJumpLink').click();
      }
    });
  }

  $(".fancybox").fancybox();
  $(".various").fancybox({
    maxWidth    : 800,
    maxHeight   : 600,
    fitToView   : false,
    width       : '70%',
    height      : '70%',
    autoSize    : false,
    closeClick  : false,
    openEffect  : 'none',
    closeEffect : 'none'
  });
  $('.fancybox-media').fancybox({
    openEffect  : 'none',
    closeEffect : 'none',
    width       : '80%',
    height      : '80%',
    fitToView   : true,
    helpers     : {
      media : {}
    }
  });
  $(".fancybox-button").fancybox({
    prevEffect : 'none',
    nextEffect : 'none',
    closeBtn   : false,
    helpers    : {
      title   : { type : 'inside' },
      buttons : {}
    }
  });

  jQuery('#lnkRights').click(function() {
    jQuery('#rightsStatement').fadeToggle(400);
    return false;
  });

  jQuery('#lnkUsage').click(function() {
    jQuery('#usageStatement').fadeToggle(400);
    return false;
  });
});
</script>
<script type="text/javascript">
var cachefile = '<?php echo $interview->cachefile; ?>';
</script>
<?php include 'parts/ga.tmpl.php'; ?>
  </body>
</html>
