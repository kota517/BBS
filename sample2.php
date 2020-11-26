<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>ミッション5</title>
    <link rel="stylesheet" href="stylesheet.css">
  </head>
  <body>
  
  <form action=""method="post">
  <p>入力フォーム</p>
  <input type="structure"name="name"placeholder="名前">
  <input type="structure"name="str"placeholder="コメント">
  <input type="password"name="password"placeholder="32文字以下のパスワード">
  <input type="submit"name="submit"value="送信"></p>

  <p>削除フォーム</p>
  <input type="number" name="deleat_number" placeholder="削除">
  <input type="password"name="deleat_password"placeholder="設定したパスワード">
  <input type="submit" name="deleat" value="削除"><br>
  
  <p>編集フォーム</p>
  <input type="number"name="edit_number"placeholder="編集対象番号">
  <input type="strucuture" name="edit_name" placeholder="新たな名前">
  <input type="strucuture" name="edit_str" placeholder="新たなコメント"> 
  <input type="password"name="edit_password"placeholder="設定したパスワード">
  <input type="submit"name="edit"value="編集">
  </form>

<?php

 //データベースに接続
$dsn="データベース名";
$user="ユーザー名";
$pass="パスワード";
$pdo=new PDO($dsn,$user,$pass,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

//テーブル作成
$sql = "CREATE TABLE IF NOT EXISTS tbstt"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "comment TEXT,"
. "password char(32),"
. "date TIMESTAMP DEFAULT CURRENT_TIMESTAMP" //現在の日時を返す　https://johobase.com/sqlserver-datetime-function/
.");";
$stmt = $pdo->query($sql);

/*
//テービルの構成要素
$sql ='SHOW CREATE TABLE tbs';
$result = $pdo -> query($sql);
foreach ($result as $row){
	echo $row[1];
}
echo "<hr>";
	    
/*テーブルの確認
  $sql ='SHOW TABLES';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[0];
		echo '<br>';
	}
	echo "<hr>";
*/


//投稿機能
if(isset($_POST["submit"])){
  $sql = $pdo -> prepare("INSERT INTO tbstt (name, comment, password,date) VALUES (:name, :comment, :password,:date)");
  $sql -> bindParam(':name', $name, PDO::PARAM_STR);
  $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
  $sql -> bindParam(':password', $password, PDO::PARAM_STR);
  $sql -> bindParam(':date',$date,PDO::PARAM_STR);
  $name=$_POST["name"];
  $comment=$_POST["str"];
  $password=$_POST["password"];
  date_default_timezone_set("Asia/Tokyo");
  $date=date("Y/m/d H:i:s");
  $sql -> execute();
}


//削除機能
if(isset($_POST["deleat_password"])){
    $deleat_password=$_POST["deleat_password"];
    $id =$_POST["deleat_number"];
  
    $sql = 'SELECT password FROM tbstt WHERE id=:id ';
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $stmt->execute();                             // ←SQLを実行する。
    $results = $stmt->fetchAll(); 
    
    foreach ($results as $row){
		if($deleat_password==$row['password']){
            $sql = 'delete from tbstt where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }    
	}
  
    /*うまく動かなかった。なんで？
  //削除するidのパスワードを抽出し変数にいれる
  $sql = 'SELECT * FROM tbs WHERE id=:id ';
  $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
  $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
  $stmt->execute();                             // ←SQLを実行する。
  $results = $stmt->fetchAll(); 
  
  if($deleat_password==$results){
        $sql = 'delete from tbs where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
  }
  */
}

//編集機能
if(isset($_POST["edit"])){
    $edit_password=$_POST["edit_password"];
    $id = $_POST["edit_number"]; //変更する投稿番号
    
    $sql = 'SELECT password FROM tbstt WHERE id=:id ';
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $stmt->execute();                             // ←SQLを実行する。
    $results = $stmt->fetchAll(); 
    
    foreach ($results as $row){
		  if($edit_password==$row['password']){
            $name = $_POST["edit_name"];
            $comment = $_POST["edit_str"]; //変更したい名前、変更したいコメントは自分で決めること
            $sql = 'UPDATE tbstt SET name=:name,comment=:comment WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();        
     }    
	  }
    /*
    //変数するidのパスワードを抽出し変数にいれる
    $sql = 'SELECT password FROM tbs WHERE id=:id ';
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $results = $stmt->execute();                             // ←SQLを実行する。
    
    if($edit_password==$results){
        $name = $_POST["edit_name"];
        $comment = $_POST["edit_str"]; //変更したい名前、変更したいコメントは自分で決めること
        $sql = 'UPDATE tbs SET name=:name,comment=:comment WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

    }
    */
}

//レコード表示
$sql = 'SELECT * FROM tbstt';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
  //$rowの中にはテーブルのカラム名が入る
  echo $row['id'].',';
  echo $row['name'].',';
  echo $row['comment'].',';
  echo $row['date'];
  echo "<hr>";
}


?>


</body>
</html>