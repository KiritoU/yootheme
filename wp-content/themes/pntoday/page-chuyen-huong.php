<?php
// get the redirection url from GET variable
$redirect_to = !empty($_GET['url'])
 ? trim(strip_tags(stripslashes($_GET['url'])))
 : '';

$wait_time    = 10000; // thời gian tự động chuyển hướng (tính bằng millisecond - ở đây là 30000 ml = 30s).
$wait_seconds = $wait_time / 1000;

add_action('wp_head', 'redirect_to_no_index', 99);
function redirect_to_no_index()
{
?>
   <!-- tắt index trang này -->
  <meta name="robots" content="noindex, follow">

<?php
}

add_action('wp_head', 'redirect_to_external_link');

function redirect_to_external_link()
{
    global $redirect_to, $wait_seconds, $wait_time;

    if (empty($redirect_to) || empty($wait_time)) {
    return;
    }
    ?>

    <script>var redirect = window.setTimeout(function(){window.location.href='<?php esc_html_e($redirect_to); ?>'},<?php echo $wait_time; ?>);</script>
    <noscript><meta http-equiv="refresh" content="<?php echo $wait_seconds; ?>;url=<?php esc_attr_e($redirect_to); ?>">></noscript>

 <?php
}

get_header(); //hiện thị header
?>

 <div id="redirect-page-content"  style="width: 100%; background:#fff; height: 50%; margin: 0px 0px 0px 0px; text-align: center; font-size: 18px;">
        <div class="redirect-message">
        <?php if (!empty($redirect_to)) { ?>
          <div style="    border: 1px solid #525151;
    width: 100%;
    height: 90px;
    line-height: 90px;
    text-align: center;
    background: #f0f0f0;">ads</div>
         <p style="font-size: 90%;margin: 8px 0px 15px 0px !important;color: #f50c0c;"><strong>TỰ ĐỘNG CHUYỂN ĐẾN TRANG TẢI FILE SAU <span style="color: #2fad16" id="timer"></span> GIÂY </strong></p>
         <div style="    border: 1px solid #525151;
    width: 100%;
    height: 90px;
    line-height: 90px;
    text-align: center;
    background: #f0f0f0;">ads</div>
         <?php
        } else {
        _e('Link chuyển hướng bị lỗi');
        }
        ?>
        </div>
 </div>
<script>
document.getElementById('timer').innerHTML = <?php echo $wait_seconds;?>;
var timer = <?php echo $wait_seconds;?>;
var interval = setInterval(function() {
   var seconds = timer;
   if (seconds > 0) {
    --seconds;
    document.getElementById('timer').innerHTML = seconds + "";
   timer = seconds;
   }
   else {

   }

 }, 1000);
</script>

<?php
 get_footer(); //hiện thị footer
?>
