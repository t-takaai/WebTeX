<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>処理結果</title>
</head>
<body>
<h1>処理結果</h1>
<?php
/*       $f = popen('pwd','r'); */
/* $home_dir = fread($f, 2096); */
/* pclose($f); */
$home_dir = '/Users/takaai/Sites/texweb';
$platex_dir = '/usr/texbin';
$dvipdfmx_dir = '/usr/texbin';
  $count = (int)$_POST['count'];
  if ($count > 999 || $count < 1) {
    echo "<p><em>エラー</em>: $count</p>";
  } elseif ($_POST['submit'] == "PDF生成" && $_POST['text'] != "") {
    $f = fopen("work/$count.tex", "w");
    fwrite($f, $_POST['text']);
    fwrite($f, "\n");
    fclose($f);
    echo "<p><code>platex -kanji=utf8 $count</code></p>";
    echo "<pre>\n";
    $dvi = 0;  $log = 0;
    $f = popen("HOME=$home_dir; cd work; ulimit -t 10 -f 2048; $platex_dir/platex -kanji=utf8 -interaction=nonstopmode $count.tex", "r");
    if ($f) {
      while ($buf = fgets($f, 4096)) {
        if (preg_match("/^Output written on \d+\.dvi/", $buf)) $dvi = 1;
        if (preg_match("/^Transcript written on \d+\.log/", $buf)) $log = 1;
        echo htmlspecialchars($buf);
      }
      pclose($f);
    } else {
      echo "<p>Cannot run platex</p>\n";
    }
    echo "</pre>\n";
    if ($log) echo "<p><a href=\"work/$count.log\">$count.log</a></p>\n";
    if ($dvi) {
      echo "<p><a href=\"work/$count.dvi\">$count.dvi</a></p>\n";
      $good = 0;
      echo "<hr><p><code>dvipdfmx $count</code></p>";
      echo "<pre>\n";
      $f = popen("HOME=$home_dir; cd work; ulimit -t 10 -f 2048; $dvipdfmx_dir/dvipdfmx $count.dvi 2>&1", "r");
      if ($f) {
        while ($buf = fgets($f, 4096)) {
          if (preg_match("/\d+\.pdf/", $buf)) $good = 1;
          echo htmlspecialchars($buf);
        }
        pclose($f);
        echo "</pre>\n";
        if ($good) {
          echo "<p><a href=\"work/$count.pdf\">$count.pdf</a></p>\n";
        } else {
          echo "<p>dvipdfmx: エラー</p>\n";
        }
      } else {
        echo "<p>dvipdfmx: エラー\n";
      }
    } else {
      echo "<p>platex: エラー\n";
    }
  } elseif ($_POST['submit'] == "関連ファイル削除") {
    echo "<p><code>rm -f $count.*</code></p><hr>";
    echo "<pre>\n";
    system("cd work; /bin/rm -f $count.*");
    echo "</pre>\n";
  } else {
    echo "<p><em>エラー</em></p>\n";
  }
?>

</body>
</html>
