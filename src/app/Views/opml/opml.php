<?php
    if (isset($XMLHeader)) echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
?>

<opml version="1.0">
    <head>
        <title><?= $OpmlTitle ?></title>
        <?php if (isset($UserEmail)) echo "<ownerEmail>$UserEmail</ownerEmail>"; ?>
    </head>
    <body>
        <?php foreach ($OpmlData as $eachRSS):?>
        <outline type="rss" title="<?= $eachRSS["feed_name"] ?>" text="<?= $eachRSS["feed_name"] ?>" description="<?= $eachRSS["feed_comment"] ?>" htmlUrl="<?= $eachRSS["website_url"] ?>" xmlUrl="<?= $eachRSS["feed_url"] ?>" />
        <?php endforeach;?>
    </body>
</opml>