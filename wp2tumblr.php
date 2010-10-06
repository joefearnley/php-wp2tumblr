<?php

/**
 * wp2tumblr.php
 *
 * @author Joe Fearnley
 *
 * Read a wordpress export xml file and post content to tumblr.
 */

$file_name = 'wordpress.xml';
$tumblr_email = '';
$tumblr_password = '';

$namespaces = array (
    'excerpt' => 'http://wordpress.org/export/1.0/excerpt/',
    'content' => 'http://purl.org/rss/1.0/modules/content/',
    'wfw' => 'http://wellformedweb.org/CommentAPI/',
    'dc' => 'http://purl.org/dc/elements/1.1/',
    'wp' => 'http://wordpress.org/export/1.0/'
);

$xml = simplexml_load_file($file_name);
$wp_posts = array();

foreach($xml->channel->item as $post) { 
    $content = $post->children($namespaces['content']);
    array_push($wp_posts, array('title' => $post->title, 'date' => $post->pubDate, 'body' => $content));
}

foreach($wp_posts as $wp_post) {
    $date = date('Y-m-d', strtotime((string)$wp_post['date']));
    $request_data = http_build_query(
        array(
            'email' => $tumblr_email,
            'password' => $tumblr_password,
            'type' => 'regular',
            'title' => (string) $wp_post['title'],
            'date' => $date,
            'body' => (string) $wp_post['body']
        )
    );

    post($request_data);
}

function post($request_data) {
    $c = curl_init('http://www.tumblr.com/api/write');
    curl_setopt($c, CURLOPT_POST, true);
    curl_setopt($c, CURLOPT_POSTFIELDS, $request_data);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($c);
    $status = curl_getinfo($c, CURLINFO_HTTP_CODE);
    curl_close($c);

    if ($status == 201) {
        echo "Success! The new post ID is $result.\n";
    } else if ($status == 403) {
        echo 'Bad email or password';
    } else {
        echo "Error: $result\n";
    }
}

?>
