<?php // TIMEAGO
function timeago() {
    global $post;
    $date = get_post_time('G', false, $post);
    if (empty($date)) {
        return __('Pending Post');
    }
    $chunks = array(
        array(60 * 60 * 24 * 365, __('year'), __('year')),
        array(60 * 60 * 24 * 30, __('month'), __('month')),
        array(60 * 60 * 24 * 7, __('weeks'), __('weeks')),
        array(60 * 60 * 24, __('day'), __('day')),
        array(60 * 60, __('hours'), __('hours')),
        array(60, __('min'), __('min')),
        array(1, __('second'), __('second'))
    );

    if (!is_numeric($date)) {
        $time_chunks = explode(':', str_replace(' ', ':', $date));
        $date_chunks = explode('-', str_replace(' ', '-', $date));
        $date = gmmktime((int) $time_chunks[1], (int) $time_chunks[2], (int) $time_chunks[3], (int) $date_chunks[1], (int) $date_chunks[2], (int) $date_chunks[0]);
    }

    $current_time = current_time('mysql', $gmt = 0);
    $newer_date = strtotime($current_time);

    $since = $newer_date - $date;

    if (0 > $since)
        return __('sometime');
    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];

        // Finding the biggest chunk (if the chunk fits, break)
        if (( $count = floor($since / $seconds) ) != 0)
            break;
    }
    // Set output var
    $output = ( 1 == $count ) ? '1 ' . $chunks[$i][1] : $count . ' ' . $chunks[$i][2];

    if (!(int) trim($output)) {
        $output = '0 ' . __('second');
    }
    $output .= __(' ago');
    return $output;
}
