<?php
     header("Access-Control-Allow-Origin: *");
     if(!isset($_REQUEST['password']) || $_REQUEST['password'] != '???'){
        die('invalid password');
     }
?>
<html>
  <head>
    <title>Hls.js demo - basic usage</title>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <style>
    button{
        font-size: 20px;
        margin: 5px;
        padding: 20px;
        min-width: 100px;
    }
    .channel{
        padding: 10px;
        margin: 3px;
        font-size: 20px;
    }
    </style>
  </head>

  <body>
      <script src="//cdn.jsdelivr.net/npm/hls.js@latest"></script>

      <center>
          <h1>Remote control page</h1>
          <video style="width: calc(100% - 20px); max-width: 480px;" id="video" controls></video>
      </center>

      <!--script>
        if(Hls.isSupported()) {
          var video = document.getElementById('video');
          
          var config = {
              autoStartLoad: true,
              maxBufferLength: 3
          };
          
          var hls = new Hls(config);
          hls.loadSource('http://www.jbcse.com/video-proxy/1.m3u8');
          hls.attachMedia(video);
          hls.on(Hls.Events.MANIFEST_PARSED,function() {
            //video.play();
        });
       }
       // hls.js is not supported on platforms that do not have Media Source Extensions (MSE) enabled.
       // When the browser has built-in HLS support (check using `canPlayType`), we can provide an HLS manifest (i.e. .m3u8 URL) directly to the video element throught the `src` property.
       // This is using the built-in support of the plain video element, without using hls.js.
        else if (video.canPlayType('application/vnd.apple.mpegurl')) {
          video.src = 'http://10.0.0.168/0.ts';
          video.addEventListener('canplay',function() {
            //video.play();
          });
        }
      </script-->

  <!-- injected in netlify post processing step -->
<div style="position: absolute; top: 5px; right: 5px;">
  <a rel="noopener" href="https://www.netlify.com" target="_blank"><img src="https://www.netlify.com/img/global/badges/netlify-color-accent.svg" /></a>
</div>

<?php
    
    $buttons = array();
    
    $buttons['channel +'] = '39,8962,4486,478,4494,478,4492,478,2254,476,4496,476,2254,476,2254,478,2252,478,2254,476,2254,476,2252,478,2252,478,2254,476,4494,478,2252,478,4494,478,2254,476,30806,8960,2248,474;';
    $buttons['channel -'] = '39,8960,4486,476,2280,450,2256,474,4494,478,4494,476,2230,498,2254,478,2254,476,2278,452,2254,476,2250,478,2254,476,2280,450,2252,478,2278,452,4494,476,2254,476,35088,8960,2272,450;';
    $buttons['OK'] = '39,8958,4486,476,4494,476,2254,476,2252,476,2252,478,4494,476,2278,452,2252,478,2252,478,2278,452,2254,476,2280,450,2254,476,2252,478,4490,480,4494,478,4494,476,30794,8960,2246,474;';
    $buttons['Size (#)'] = '39,8960,4488,474,2278,452,2252,476,2252,478,2252,478,2252,480,2254,476,4492,478,2252,478,2254,476,2254,476,2280,450,2252,478,2252,476,2254,478,4492,476,4492,478,35080,8958,2244,476;';
    $buttons['CC (*)'] = '39,8936,4510,452,2278,452,2278,454,4518,452,2276,454,2276,456,2278,452,4518,454,2276,454,2276,454,2276,454,2278,452,2278,452,2278,452,2276,452,2278,454,4520,474,35090,8986,2216,480;';
    $buttons['Info'] = '39,8960,4484,478,4492,478,4492,478,2250,478,2252,478,4492,480,4492,478,2252,478,2252,478,2256,474,2252,478,2254,476,2252,478,2254,476,4494,478,2252,478,4492,478,28554,8960,2244,478;';
    $buttons['Guide'] = '39,8934,4486,478,2250,478,2252,478,2256,474,2254,476,4492,478,4494,476,2252,478,2256,474,2252,478,2254,476,2254,478,2252,478,4494,476,2254,476,4494,478,4492,476,30820,8932,2248,474;';
    $buttons['Exit'] = '39,8958,4486,476,2252,478,4492,478,2252,478,2252,478,4492,478,2252,478,2254,476,2254,476,2252,478,2254,476,2252,478,2252,478,4494,476,2252,476,4494,476,4494,476,30792,8960,2246,476;';
    $buttons['Menu'] = '39,8960,4484,478,4494,476,2256,474,2254,476,4494,476,4496,474,2254,476,2252,478,2252,478,2256,474,2254,476,2252,478,2280,450,2254,476,4494,476,4496,474,2254,476,30800,8960,2246,478;';
    $buttons['1'] = '39,8962,4484,480,4496,476,2252,478,2252,476,2254,478,2254,476,2254,476,2280,450,2256,476,2256,476,2254,476,2256,474,2252,478,4492,478,4494,478,4494,478,4494,478,30800,8958,2270,452;';
    $buttons['2'] = '39,8962,4488,476,2254,478,4494,478,2252,478,2256,476,2254,478,2254,476,2254,476,2256,474,2254,476,2282,450,2256,476,2252,478,2254,478,4496,478,4498,474,4492,478,33050,8960,2248,476;';
    $buttons['3'] = '39,8960,4512,450,4496,476,4494,476,2280,450,2254,476,2256,474,2254,478,2254,476,2254,476,2256,476,2252,478,2252,478,2254,476,4496,478,2256,474,4494,476,4494,478,30800,8962,2246,478;';
    $buttons['4'] = '39,8964,4486,478,2252,478,2256,476,4496,476,2254,476,2256,474,2252,478,2254,478,2256,474,2280,452,2256,474,2256,476,2280,452,2254,476,2254,476,4522,450,4498,474,35098,8960,2272,450;';
    $buttons['5'] = '39,8962,4486,476,4498,474,2252,478,4494,478,2280,450,2254,476,2280,450,2280,450,2256,476,2254,476,2256,476,2254,476,2254,476,4492,478,4494,478,2254,476,4494,478,30800,8964,2244,478;';
    $buttons['6'] = '39,8964,4486,478,2254,476,4496,478,4494,478,2254,478,2254,476,2252,478,2256,476,2254,476,2256,474,2252,478,2254,476,2256,474,2280,450,4494,478,2254,476,4522,450,33044,8962,2246,476;';
    $buttons['7'] = '39,8960,4486,478,4496,476,4494,476,4496,476,2252,478,2256,476,2256,474,2254,476,2254,476,2280,450,2254,478,2254,476,2254,476,4496,478,2278,452,2254,476,4494,478,30800,8964,2244,478;';
    $buttons['8'] = '39,8958,4488,476,2254,476,2254,476,2256,476,4494,478,2280,452,2256,476,2278,452,2282,450,2254,476,2282,450,2254,478,2280,450,2252,478,2254,476,2254,476,4494,478,37398,8960,2248,476;';
    $buttons['9'] = '39,8962,4486,476,4494,478,2254,478,2280,450,4492,480,2280,450,2254,478,2252,478,2254,476,2250,478,2254,478,2254,476,2254,476,4494,478,4496,476,4494,478,2280,450,30802,8960,2262,458;';
    $buttons['0'] = '39,8958,4486,478,2254,476,2252,478,2254,476,2278,452,2252,476,2254,476,2280,452,2254,476,2254,476,2254,476,2252,478,2254,476,2278,452,2278,450,2256,476,2256,474,42002,8958,2270,452;';
    $buttons['up'] = '39,8960,4486,478,2254,476,2254,476,4496,476,2252,478,4492,478,4494,478,2258,474,2252,478,2280,450,2280,450,2254,478,2254,476,4496,478,2254,476,2280,452,4494,476,30802,8960,2244,478;';
    $buttons['left'] = '39,8956,4486,476,2256,474,4498,474,4494,476,2254,476,4494,476,4496,476,2256,474,2256,474,2254,476,2254,476,2254,476,2280,450,4492,480,4494,478,4494,476,2252,478,26318,8960,2244,478;';
    $buttons['down'] = '39,8958,4488,474,4494,478,2278,450,4496,476,2254,476,4494,478,4494,476,2252,478,2254,478,2250,478,2254,476,2252,478,2254,476,2252,504,2228,476,2256,474,4494,478,30796,8986,2244,450;';
    $buttons['right'] = '39,8958,4486,476,4494,478,4490,478,4492,478,2254,476,4498,472,4494,476,2252,478,2252,478,2280,452,2278,452,2256,474,2254,504,2226,476,4492,478,4456,514,2254,476,26314,8960,2244,478;';
    
    $channels = array();
    $channels['NHK'] = array('4','8','2');
    $channels['PBS'] = array('1','3');
    //$channels['Weather'] = array('1','1','9');
    $channels['NatGeo'] = array('1','2','1');
    $channels['Sci'] = array('1','2','2');
    $channels['Mil History'] = array('1','2','6');
    $channels['History'] = array('1','2','8');
    $channels['TLC'] = array('1','3','9');
    
    //$channels['Disc Life'] = array('1','6','1');
    $channels['SyFy'] = array('1','8','0');
    $channels['BBC'] = array('1','8','9');
    $channels['Comedy Central'] = array('1','9','0');
    $channels['FXX'] = array('1','9','1');
    $channels['Freeform'] = array('1','9','9');
    $channels['MTV'] = array('2','1','0');
    $channels['MTV2'] = array('2','1','1');
    $channels['VH1'] = array('2','1','7');
    $channels['MTV Classic'] = array('2','1','8');
    $channels['AMC'] = array('2','3','1');
    $channels['FX Movie'] = array('2','3','2');
    $channels['Sundance'] = array('2','3','5');
    $channels['Cartoon Network'] = array('2','5','7');
    $channels['PBS World'] = array('4','7','3');
    $channels['Yahoo! Finance'] = array('6','0','4');
    $channels['BBC World'] = array('6','0','9');
    $channels['Discovery'] = array('6','2','0');
    $channels['fyi,'] = array('6','2','9');
    $channels['Motor Trend'] = array('6','3','1');
    $channels['dyi'] = array('6','6','7');
    $channels['vice'] = array('6','9','7');    
    $channels['ifc'] = array('7','3','4');
    $channels['showtime'] = array('8','6','5');
    $channels['showtime w'] = array('8','6','6');
    $channels['showcase'] = array('8','6','7');
    $channels['hbo hd'] = array('8','9','9');
    $channels['hbo hd2'] = array('9','0','1');
    $channels['hbo zone'] = array('9','1','1');
    $channels['fx'] = array('1','0','5','4');
    $channels['fxm'] = array('7','3','2');
    $channels['g4'] = array('8','0','4');
    $channels['MAVtv'] = array('8','1','0');
    $channels['TNT'] = array('0','5','1');
    $channels['TBS'] = array('0','5','2');
    $channels['USA'] = array('0','5','0');
    $channels['BBC America'] = array('6','8','9');
    //$channels['signal strength'] = array('Menu','up','up','right','down','down','down','down','down','Info');
        
    echo '<center><br>';
    foreach($buttons as $key => $val){
        echo <<<html
        <form method="POST" action="/remote-proxy/ir-remote-signal/" enctype="text/plain" target="my-iframe" style="display: inline;">
            <input type="hidden" name="p" value="$val">
            <input type="submit" value="$key">
        </form>
html;
    }
    echo '<br><br>Favorite Channels: ';
    foreach($channels as $key => $channel){
        $temp = '';
        $buttonPresses = '';
        while(count($channel) < 4){
            array_unshift($channel,'0');
        }
        foreach($channel as $button){
            $temp .= $buttons[$button];
            $buttonPresses .= $button;
        }
        
        
        echo <<<html
        <form method="POST" action="/remote-proxy/ir-remote-signal/" enctype="text/plain" onclick="saveChannel('$buttonPresses');" target="my-iframe" style="display: inline;">
            <input type="hidden" name="p" value="$temp">
            <input type="submit" class='channel' value="$key">
        </form>
html;
    }
    
    echo '</center>';
    
?>

<script>

function saveChannel(channelArg){
    jQuery.post('/saveFiosChannel.php', {channel: channelArg});
    return true;
}

</script>
<br><br>
<a href="/remote-proxy/reset/" target="my-iframe"><button>Reset Verizon TV Box</button></a>

<iframe name="my-iframe" src="/remote-proxy/" style="display: none;"></iframe>

</body>
</html>

