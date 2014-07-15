<?php
$user = 'takaai';
$password = 'pass';

if (!isset($_SERVER['PHP_AUTH_USER'])){
    header('WWW-Authenticate: Basic realm="Private Page"');
    header('HTTP/1.0 401 Unauthorized');

    die('このページを見るにはログインが必要です');
}else{
    if ($_SERVER['PHP_AUTH_USER'] != $user
        || $_SERVER['PHP_AUTH_PW'] != $password){

        header('WWW-Authenticate: Basic realm="Private Page"');
        header('HTTP/1.0 401 Unauthorized');
        die('このページを見るにはログインが必要です');
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>TeX on Web</title>
</head>
<body>
<h2>TeX on Web</h2>
<p>処理結果は別の窓に現れます。結果の窓が現れないことがありますが，裏に隠れて見えないだけですので，何度も「処理」を押さないでください。</p>

<hr>
<p>直接書き込む場合</p>

<p>ファイル名は1.tex～999.texに限ります（適当に割り振られますが変更できます）。</p>

<?php
  $fp = fopen("count.txt", "r+");
  $count = fgets($fp, 10);
  $count = $count + 1;
  if ($count > 999 || $count < 1) $count = 1;
  fseek($fp, 0);
  fputs($fp, "$count   ");
  fclose($fp);
?>

<form action="do.php" method="post" target="result">
ファイル名: <input name="count" size="3" maxlength="3" value="<?php echo $count ?>">.tex<br>
<textarea name="text" rows="20" cols="80">
\documentclass{jsarticle}
\begin{document}

ここに何か書いてください。

\end{document}
</textarea>
<br>
<input type="submit" name="submit" value="PDF生成">
<input type="submit" name="submit" value="関連ファイル削除">
</form>

<hr>
<p>ファイルをアップロードする場合</p>

<form action="upload.php" method="post" enctype="multipart/form-data" target="result">
  ファイル：
  <input type="file" name="upfile" size="30" /><br />
  <br />
  <input type="submit" value="アップロード" />
</form>

<hr>
<p><a href="/~takaai/" rel="author">鷹合孝之</a></p>

<p>
<!-- hhmts start -->
Last modified: <time>2014-07-04 21:10:57+09:00</time>
<!-- hhmts end -->
</p>
</body>
</html>
