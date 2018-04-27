#!/usr/bin/php
<?

function create_password() {
   $chars = "abcdefghijklmnopqrstuvwxyz023456789ABCDEFGHIJKLMNOPQRSTUWXYZ";
   $length = strlen($chars);
   srand((double)microtime()*1000000);
   $i = 0;
   $pass = '' ;
   while ($i <= 8) {
      $num = rand() % $length;
      $tmp = substr($chars, $num, 1);
      $pass = $pass . $tmp;
      $i++;
   }
   return $pass;
}

function hash_password($password, $salt) {
   $salt1 = mb_substr($salt, 0, ceil(mb_strlen($salt, "UTF-8")/2), "UTF-8");
   $salt2 = mb_substr($salt, ceil(mb_strlen($salt, "UTF-8")/2), mb_strlen($salt, "UTF-8"), "UTF-8");
   return sha1($salt2.'abc'.$password.'xyz'.$salt1);
}


$kontakty = fopen('kontakty.txt', 'w');
$update   = fopen('update.sql', 'w');
// select yii_user.id, yii_user.username, yii_organisation.worker_email from yii_library left join yii_organisation on (yii_organisation.id=yii_library.organisation_id) left join yii_user on (yii_user.id=yii_library.user_id)
$input    = fopen('seznam_knihoven.csv', 'r');
while (($line = fgets($input, 4096)) !== false) {
   list($id, $user, $addr) = explode(',', $line);
   $user = chop($user);
   $addr = chop($addr);
   $pass = create_password();
   $salt = uniqid(rand());
   $hash = hash_password($pass, $salt);
   fwrite($kontakty, "$addr,$user,$pass\n");
   fwrite($update, "UPDATE yii_user SET password='$hash', salt='$salt' WHERE id = $id;\n");
}

/*
$pass = create_password();
$salt = uniqid(rand());
$hash = hash_password($pass, $salt);
*/

?>