<?php

$q = $_REQUEST['q'];
if ($q[0]==='/') $q=substr($q,1);

if ($q === 'options') require_once 'options.php';
else require_once 'homepage.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?php echo $title?$title.' - 洛奇百科':'洛奇百科'; ?></title>
        <link href="/main.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <div style="display:flex;aligen-items:stretch;min-height:100vh">
            <nav>
                <a href="/"><h1>洛奇百科</h1></a>
                <!--form action="/search" onsubmit="return !!this.s.value"><input name="s" style="width: 80%; margin:0 1em" placeholder="搜索 至少输入两个字" value=""></form-->
                <div id="toc">
                    <ul>
                        <li><a href="/options">魔法释放卷轴</a></li>
                    </ul>
                </div>
            </nav>
            <main>
                <?php
                if ($title) echo "<h2>$title</h2>";
                view();
                ?>
            </main>
    </body>
</html>