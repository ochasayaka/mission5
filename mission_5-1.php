<!DOCTYPE html>
<html lang="ja">

    
<?php
    
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    $sql = "CREATE TABLE IF NOT EXISTS mission5"
    . "("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date datetime,"
    . "pass char(32)"
    . ");";
    $stmt = $pdo->query($sql);
    
    //削除機能
    //削除対象番号が送信されたら、
    if (!empty($_POST['delate'])) {
        // かつ、削除パスワードが送信されたら
        if(!empty($_POST['delepass'])) {

            $id = $_POST['delate'];
            $pass=$_POST['delepass'];
            
            // tbtestのテーブルから削除したい番号に対応するidの列を探す
            $sql = 'delete from mission5 where id=:id AND pass=:pass';
            
            //クエリの実行準備、idは変動値だから、prepareを使う
            $stmt = $pdo->prepare($sql);
    
            // 変数$idをidに結び付ける、executeした時点で実行される。（＝削除される）
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->execute();
        
        }else{
            echo "パスワードを入力してください<br>";
            
        }
        
        }elseif(!empty($_POST['delepass'])){
            echo "削除対象番号を入力してください<br>";
        
        }
        
        //編集機能
        //編集対象番号が送信されたら、
        if (!empty($_POST['edit'])) {
            // かつ編集パスワードが送信されたら、
            if(!empty($_POST['editpass'])){
                
                //  取得するデータid、パスワードを指定
                $pass = $_POST['editpass'];
                $id = $_POST['edit'];                
                
                // SQLを作成する
                $sql = 'SELECT * from mission5 where id=:id AND pass=:pass';
                $stmt = $pdo->prepare($sql);
                
                // 登録するデータをセット
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                
                // SQLを実行する
                $results = $stmt->execute();
                
                // 該当データを取得する
                $results = $stmt->fetch();
                
                //value属性と一致させる 
                $editname = $results[1];
                $editcomment = $results[2];
                $editnumber = $results[0];
                

            }else{
                echo "パスワードを入力してください<br>";
            
            }
                
            
        }elseif(!empty($_POST['editpass'])){
            echo "編集対象番号を入力してください<br>";
            
        }
        

        //投稿機能
        //名前が送信されたら、
        if (!empty($_POST['name'])) {
            
            // かつ、コメントが送信されたら、
            if(!empty($_POST['comment'])){
                
                // かつ、パスワードが送信されたら、
                if(!empty($_POST['pass'])){
                    
                    if(!empty($_POST['editHid'])){
                        //変更する投稿番号
                        $id = $_POST['editHid']; 
                        $name = $_POST['name'];
                        $comment = $_POST['comment'];
                        
                        $pass = $_POST['pass'];
                        $sql = 'UPDATE mission5 SET name=:name,comment=:comment,date=NOW(),pass=:pass WHERE id=:id';
                        $stml = $pdo->prepare($sql);
                        $stml->bindParam(':name', $name, PDO::PARAM_STR);
                        $stml->bindParam(':comment', $comment, PDO::PARAM_STR);
                        $stml->bindParam(':pass', $pass, PDO::PARAM_STR);
                        $stml->bindParam(':id', $id, PDO::PARAM_INT);
                        $stml->execute();
                        
                    }else{
                        
                        //データを登録 
                        $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, date, pass) VALUES (:name, :comment, NOW(), :pass)");
                        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);                    
                    
                        // テーブル作成
                        $name = $_POST['name'];
                        $comment = $_POST['comment'];
                        $pass = $_POST['pass'];
                        $sql -> execute();

                    }
                    
                }else{
                    if(!empty($_POST['comment'])){
                         echo "パスワードを入力してください<br>";
                        
                    }
                    
                }
                
            }else{
                if (!empty($_POST['name'])){
                echo "コメントを入力してください<br>";
                    
                }
                
            }
            
        }else{
            if(!empty($_POST['comment'])){
            echo "名前を入力してください<br>";     
                
            }elseif(!empty($_POST['pass'])){
                    echo "名前を入力してください<br>";
                
            }
            
        }

?>

  <head>
    <meta charset="UTF-8">
    <title>mission5-1</title>
  </head>
  <body>
    <form action="" method="post">
      <input type="text" name="name" placeholder="名前" value="<?php if(isset($editname)) {echo $editname;} ?>"><br>
      <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($editcomment)) {echo $editcomment;} ?>"><br>
      <input type="text" name="pass" placeholder="パスワード">
      <input type="hidden" name="editHid" value="<?php if(isset($editnumber)) {echo $editnumber;} ?>">
      <input type="submit" name="submit" value="送信">
    </form><br>

    <form action="" method="post">
      <input type="number" name="delate" placeholder="削除対象番号"><br>
      <input type="text" name="delepass" placeholder="パスワード">
      <input type="submit" name="delete" value="削除">
    </form><br>

    <form action="" method="post">
      <input type="number" name="edit" placeholder="編集対象番号"><br>
      <input type="text" name="editpass" placeholder="パスワード">
      <input type="submit" value="編集">
    </form><br>
    </div>
    
    <?php
        //削除機能
        //削除対象番号が送信されたら、
        if (!empty($_POST['delate'])) {
            // かつ、削除パスワードが送信されたら
            if(!empty($_POST['delepass'])) {
            
            
            // tbtest内のテーブルを表示させる（抽出）。
            // テーブルは変動値ではないから、queryで問題ない。
            $sql = 'SELECT * FROM mission5';
            $stmt = $pdo->query($sql);
            
            // 結果セットから、残っている行を取得する
            $results = $stmt->fetchAll();
            // 配列の数だけループさせる
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム（行）名が入る
                // hrタグは水平の横線を引くもの
                echo $row['id'].' ';
                echo $row['name'].' ';  
                echo $row['comment'].' ';
                echo $row['date']."<br>";
            
            }
            
        }else{
            // tbtest内のテーブルを表示させる（抽出）。
            $sql = 'SELECT * FROM mission5';
            $stmt = $pdo->query($sql);
            
            // 結果セットから、残っている行を取得する
            $results = $stmt->fetchAll();
            // 配列の数だけループさせる
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム（行）名が入る
                echo $row['id'].' ';
                echo $row['name'].' ';  
                echo $row['comment'].' ';
                echo $row['date']."<br>";
            
            }
    
        }
            
        }elseif(!empty($_POST['delepass'])){
            // tbtest内のテーブルを表示させる（抽出）。
            $sql = 'SELECT * FROM mission5';
            $stmt = $pdo->query($sql);
            
            // 結果セットから、残っている行を取得する
            $results = $stmt->fetchAll();
            // 配列の数だけループさせる
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム（行）名が入る
                echo $row['id'].' ';
                echo $row['name'].' ';  
                echo $row['comment'].' ';
                echo $row['date']."<br>";
                
            }
        
        }
        
            // 編集機能
            if (!empty($_POST['edit'])) {
                // かつ編集パスワードが送信されたら、
                if(!empty($_POST['editpass'])){

                }else{
                    // tbtest内のテーブルを表示させる（抽出）。
                    $sql = 'SELECT * FROM mission5';
                    $stmt = $pdo->query($sql);
                
                    // 結果セットから、残っている行を取得する
                    $results = $stmt->fetchAll();
                    // 配列の数だけループさせる
                    foreach ($results as $row){
                        //$rowの中にはテーブルのカラム（行）名が入る
                        echo $row['id'].' ';
                        echo $row['name'].' ';  
                        echo $row['comment'].' ';
                        echo $row['date']."<br>";
                
                    }
                }
            
            }elseif(!empty($_POST['editpass'])){
                // tbtest内のテーブルを表示させる（抽出）。
                // テーブルは変動値ではないから、queryで問題ない。
                $sql = 'SELECT * FROM mission5';
                $stmt = $pdo->query($sql);
            
                // 結果セットから、残っている行を取得する
                $results = $stmt->fetchAll();
                // 配列の数だけループさせる
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム（行）名が入る
                    // hrタグは水平の横線を引くもの
                    echo $row['id'].' ';
                    echo $row['name'].' ';  
                    echo $row['comment'].' ';
                    echo $row['date']."<br>";

                }
            }
        
        // 投稿機能
        // 名前が送信されたら、
        if (!empty($_POST['name'])) {
            
            // かつ、コメントが送信されたら、
            if(!empty($_POST['comment'])){
                
                // かつ、パスワードが送信されたら、
                if(!empty($_POST['pass'])){
                    
                    if(!empty($_POST['ediHid'])){
                    
                        // 作成したテーブルを表示させる
                        $sql = 'SELECT * FROM mission5';
                        $id = $_POST['ediHid']; 
                        $stmt = $pdo->query($sql);
                        $results = $stmt->fetchAll();
                        foreach ($results as $row){
                            //$rowの中にはテーブルのカラム名が入る
                            echo $row['id'].' ';
                            echo $row['name'].' ';
                            echo $row['comment'].' ';
                            echo $row['date'];
                        }
                    }else{

                        // 作成したテーブルを表示させる
                        $sql = 'SELECT * FROM mission5';
                        $stmt = $pdo->query($sql);
                        $results = $stmt->fetchAll();
                        foreach ($results as $row){
                            //$rowの中にはテーブルのカラム名が入る
                            echo $row['id'].' ';
                            echo $row['name'].' ';
                            echo $row['comment'].' ';
                            echo $row['date']."<br>";

                        }                        
                    }
                }else{
                    // 作成したテーブルを表示させる
                    $sql = 'SELECT * FROM mission5';
                    $stmt = $pdo->query($sql);
                    $results = $stmt->fetchAll();
                    foreach ($results as $row){
                        //$rowの中にはテーブルのカラム名が入る
                        echo $row['id'].' ';
                        echo $row['name'].' ';
                        echo $row['comment'].' ';
                        echo $row['date']."<br>";
                    }
                    
                }
            }else{
                // 作成したテーブルを表示させる
                $sql = 'SELECT * FROM mission5';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].' ';
                    echo $row['name'].' ';
                    echo $row['comment'].' ';
                    echo $row['date']."<br>";

                }
            }
            
                
        }else{
            if(!empty($_POST['comment'])){
                // 作成したテーブルを表示させる
                $sql = 'SELECT * FROM mission5';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].' ';
                    echo $row['name'].' ';
                    echo $row['comment'].' ';
                    echo $row['date']."<br>";

                }
            
            }elseif(!empty($_POST['pass'])){
                // 作成したテーブルを表示させる
                $sql = 'SELECT * FROM mission5';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].' ';
                    echo $row['name'].' ';
                    echo $row['comment'].' ';
                    echo $row['date']."<br>";

                }
            }
        }
        
    ?>


  </body>

</html>