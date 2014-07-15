<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sample</title>
</head>
<body>
<p>
<?php
/*      $f = popen('pwd','r'); */
/* $home_dir = fread($f, 2096); */
/* pclose($f); */
$home_dir = '/Users/takaai/Sites/texweb';
$platex_dir = '/usr/texbin';
$dvipdfmx_dir = '/usr/texbin';
if (is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
  if (move_uploaded_file($_FILES["upfile"]["tmp_name"], "files/" . $_FILES["upfile"]["name"])) {
    $file_name_tex = $_FILES["upfile"]["name"];
    $reg="/(.*)(?:\.([^.]+$))/";
    preg_match($reg,$file_name_tex,$retArr);
    $file_name = $retArr[1];
    chmod("files/" . $file_name_tex, 0644);
    echo $file_name_tex . "をアップロードしました。";
    echo "<p><code>platex -kanji=utf8 " . $file_name_tex . "</code></p>";
    echo "<pre>\n";
    $dvi = 0;  $log = 0;
    $f = popen("HOME=$home_dir; cd files; ulimit -t 10 -f 2048; $platex_dir/platex -kanji=utf8 -interaction=nonstopmode " . $file_name_tex, "r");
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
    echo "<p><a href=\"files/$file_name.log\">$file_name.log</a></p>\n";
    echo "<p><a href=\"files/$file_name.dvi\">$file_name.dvi</a></p>\n";
    $f = popen("HOME=$home_dir; cd files; ulimit -t 10 -f 2048; $dvipdfmx_dir/dvipdfmx $file_name.dvi 2>&1", "r");
    if ($f) {
        while ($buf = fgets($f, 4096)) {
          if (preg_match("/\d+\.pdf/", $buf)) $good = 1;
          echo htmlspecialchars($buf);
        }
    }
    pclose($f);
    echo "</pre>\n";
    echo "<p><a href=\"files/$file_name.pdf\">$file_name.pdf</a></p>\n";
    } else {
    echo "ファイルをアップロードできません。";
  }
} else {
  echo "ファイルが選択されていません。";
}

?>
</p>
</body>
</html>
