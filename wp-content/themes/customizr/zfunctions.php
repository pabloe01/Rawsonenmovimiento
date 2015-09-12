<?php
add_action('__navbar', 'f_login_with_ajax' , 25);

function f_login_with_ajax() {
    ob_start();
    ?>  <div class="login" > <?php 
    login_with_ajax();
    ?>  </div> <?php
    $html = ob_get_contents();
    if ($html)
        ob_end_clean();
    echo apply_filters('f_login_with_ajax', $html);
}