<!DOCTYPE html>
<meta charset="UTF-8">

<?php
session_save_path("/var/www/html/cc2018"); //set the location where save file

$login=FALSE;
$name="NEW USER";
if(isset($_GET['name'])){
$name=$_GET['name'];
}
$pass=$_GET['key'];
$text=$_GET['text'];

#echo session_save_path().'/sess_'.$_COOKIE['PHPSESSID'], "<br>";

if($name==='admin' && $pass==='hogehogeahogehoge'){
   session_start();
   $_SESS['user']='admin';
   $_SESS['login']=TRUE;
   $login=$_SESS['login'];

   $fp = fopen("ADMN.csv",'a+b');
   fputcsv($fp, [$name, $text]);
   rewind($fp);
   while ($row = fgetcsv($fp)) {
   $rows[] = $row;
   }
  # echo "stat 1<br>";   
}elseif (file_exists(session_save_path().'/sess_'.$_COOKIE['PHPSESSID'])){
   session_start();
  # echo "stat 2<br>";
   echo "login as ",$_SESS['user'],".<br>";
   $fp = fopen($name.".csv", 'a+b');
   fputcsv($fp, [$name, $text]);
   rewind($fp);

   $login=TRUE;
}else{
  # echo "stat 3<br>";
   $fp = fopen($name.".csv", 'a+b');
   fputcsv($fp, [$name, $text]);
   rewind($fp);

   if(!empty($name)){
   $fp_admn = fopen("ADMN.csv", 'a+b');
   fputcsv($fp_admn, [$name, $text]);
   rewind($fp_admn);
   }

   while ($row = fgetcsv($fp)) {
   $rows[] = $row;
   }
}
?>


<title>ものづくり 2018</title>
<h1>ものづくり 2018</h1>

<section>
<?php if(!$login): ?>
    <h2>LOGIN!</h2>
    <form action="login.php" method="get">
        name:<input name ="name" value=""><br>
        key:<input name ="key" value=""><br><br>
	
	ADMNに頼めば入れてくれるかも.......?<br>
	ADMNがloginした時に返信するから, 宛先がわかるようにnameはいれてね <br>
	<textarea name="text" wrap="soft" rows="4" cols="60"></textarea>
        <button>login</button>
    </form>
<?php else: ?>
      <h2>WELCOM ADMIN!</h2>
      Welcome Mr. <?=$_SESS['user']?> !! <br>
      <button type="button" onclick="alert('FLAG{This_c00k1e_1s_not_s0_bad.}')">
	flag
      </button>
      
<?php endif;?>


    <h2><?=$name?>への受信一覧</h2>
<?php if (!empty($rows)): ?>
    <ul>
<?php foreach ($rows as $row): ?>
	<li><?=$row[1]?> (<?=$row[0]?>)</li>
<?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>まだ返信着てないよ</p>
<?php endif; ?>
</section>
