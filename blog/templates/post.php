<?php
include_once("../data/posts.php");

$postId = $_GET['id'];
$currentPost = [];
$postFound = false;

foreach ($posts as $post) {
    if ($post['id'] == $postId) {
        $currentPost = $post;
        $postFound = true;
        break; // Exit the loop since we found the post
    }
}

if (!$postFound) {
    $currentPost = ['title' => 'Não encontrado'];
}

echo $currentPost['title'];
?>

<p>Dolor laboris velit minim veniam. Ullamco cillum exercitation nisi ad ea elit et do est fugiat laborum ipsum nisi elit. Id aute enim eiusmod laborum mollit sunt quis et enim est voluptate voluptate officia commodo. Proident culpa magna sint qui velit labore.

Proident in nisi aliquip aliqua. Veniam dolor est est non. Duis labore cupidatat nulla ex reprehenderit nostrud do nulla aliquip Lorem tempor.

Dolor eu consectetur et culpa reprehenderit dolor mollit veniam eu ipsum. Veniam ea reprehenderit deserunt do nisi nisi adipisicing deserunt aliqua amet qui laboris. Elit id laboris officia adipisicing. Eu officia proident do ad elit. Dolor enim et culpa eu aliquip consectetur. Cillum fugiat ex cupidatat veniam est laboris do nulla laboris ullamco magna ex. Pariatur proident labore esse tempor nisi laborum est nulla consectetur.

Reprehenderit exercitation pariatur duis ad sint elit excepteur. Esse non proident laboris ad ad id. Sit ipsum dolore id culpa veniam cillum est deserunt ipsum. Exercitation nisi nisi exercitation culpa cillum occaecat ipsum et fugiat excepteur anim elit.

Est id mollit occaecat pariatur eiusmod sunt. Exercitation consequat culpa ea duis nisi est commodo voluptate et laborum adipisicing anim excepteur ipsum. Consequat minim excepteur aliqua nisi velit duis. Et esse laboris quis deserunt ipsum qui sit et cupidatat enim. Ad aliqua esse sint ipsum in exercitation laboris labore. Incididunt nisi ullamco sunt voluptate tempor laboris veniam minim eiusmod est. Laborum dolor anim reprehenderit elit pariatur.

Elit non eiusmod sit ea irure aliqua consequat id nostrud. Ea nulla fugiat occaecat commodo nostrud enim duis magna nulla nostrud. Ex incididunt quis eiusmod cillum Lorem consectetur ut do nisi quis exercitation deserunt. Esse ad dolor qui officia sint sunt consequat do ullamco minim.</p>

<p><a href="../"><- voltar</a></p>