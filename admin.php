<?php
  $time_start = microtime(true);
  
  require_once 'include/config.php';
  require_once 'include/template.php'; // $tpl
  
  $db = new mysqli($cfg['dbhost'], $cfg['dbuser'], $cfg['dbpass'], $cfg['dbname']);
  $db->set_charset("utf8");
  
  $user_ip = $_SERVER['REMOTE_ADDR'];
  
  $uid = $cfg['biscuit_name'].'_u';
  $key = $cfg['biscuit_name'].'_k';
  $acl_level = 0;
  
  if (isset($_COOKIE[$uid]))
  {
    $user_id = $db->real_escape_string($_COOKIE[$uid]);
    $result = $db->query("SELECT * FROM `users` WHERE `id`='$user_id' LIMIT 1");
    $q++;
    $user = $row = $result->fetch_object();
    $result->free_result();
    
    if ( ($_COOKIE[$key] == biscuit_md5($row->password)) && ($row->active == 1) )
    {
      if ($row->last_ip != $user_ip) {
        $db->query("UPDATE `users` SET `last_ip`='$user_ip' WHERE `id`='$row->id' LIMIT 1");
        $q++;
      }
    
      $loggedin = true;
      $acl_level = $row->access_level;
      
      if ($acl_level >= 10) {
        $is_admin = true;
        
        if ($acl_level >= 50)
          $is_root = true;
      }
      else {
        $is_admin = false;
        $redir = 'index.php';
      }
    }
    else
    {
      setcookie($uid, "", time()-60*60*24*30, '/');
      setcookie($key, "", time()-60*60*24*30, '/');
      
      $loggedin = false;
      $redir = 'index.php';
    }
    
  }
  else {
    $loggedin = false;
    $redir = 'index.php';
  }
  
  $act = $_GET['act'];
  $do = $_GET['do'];
  
  $data_dir = $cfg['data_dir'];
  $tpl->assign('data_dir', $data_dir);
  $tpl->assign('loggedin', $loggedin);
  $tpl->assign('acl_level', $acl_level);
  
  // ---------------------------------------------------------------------------
  
  if (($loggedin==true) && ($is_admin==true))  
  {
    $validact = array('index', 'questions', 'categories', 'users', 'info');
    if (!in_array($act, $validact))
      $act = 'index';
          
    $tpl->assign('user_id', $user_id);
    $tpl->assign('username', $user->username);
    $tpl->assign('password', $user->password);
    $tpl->assign('email', $user->email);
    $tpl->assign('registered', date("d/m/Y H:i:s", $user->registered));
    $tpl->assign('last_login', date("d/m/Y H:i:s", $user->last_login));
    $tpl->assign('points', $user->points);
    $tpl->assign('q_answered', $user->q_answered);
    $tpl->assign('q_total', $user->q_total);
    
    switch ($act)
    {
      default:
      case 'index':
        break;
        
      case 'questions':
        $validdo = array('list', 'new', 'edit', 'save', 'delete', 'deleteimage');
        if (!in_array($do, $validdo))
          $do = 'list';

        switch ($do)
        {
          default:
          case 'list':
            $act = 'questions_list';
            
            //$result = $db->query("SELECT `q`.`id`, `q`.`question`, `q`.`level`, `c`.`name` FROM `questions` `q` LEFT JOIN `categories` `c` ON `q`.`cat`=`c`.`id` ORDER BY `q`.`id` ASC");
            $result = $db->query("SELECT `q`.*, `c`.`name` AS `cat_name` FROM `questions` `q` LEFT JOIN `categories` `c` ON `q`.`cat`=`c`.`id` ORDER BY `q`.`id` ASC");
            $q++;
            
            $questions_num = $result->num_rows;
            $questions = array();
          
            while ($row = $result->fetch_array(MYSQLI_ASSOC))
              $questions[] = $row;
            $result->free_result();
          
            $tpl->assign('questions_num', $questions_num);
            $tpl->assign('questions', $questions);
            break;
          
          case 'edit':
            $q_id = $db->real_escape_string($_GET['id']);
             
            $result = $db->query("SELECT * FROM `questions` WHERE `id`='$q_id'");
            $q++;
            $question = $result->fetch_object();
            $result->free_result();
            
            $tpl->assign('q_id', $question->id);
            $tpl->assign('question', $question->question);
            $tpl->assign('cat', $question->cat);
            $tpl->assign('level', $question->level);
            $tpl->assign('image', $question->image);
            $tpl->assign('imagepath', $data_dir.'/images/'.$question->image);
            $tpl->assign('ans_1', $question->ans_1);
            $tpl->assign('ans_2', $question->ans_2);
            $tpl->assign('ans_3', $question->ans_3);
            $tpl->assign('ans_4', $question->ans_4);
            $tpl->assign('correct', $question->correct);
            
          case 'new':
            $result = $db->query("SELECT * FROM `categories`");
            $q++;            
            $cats_num = $result->num_rows;
            $cats = array();          
            while ($row = $result->fetch_array(MYSQLI_ASSOC))
              $cats[] = $row;
            $result->free_result();
            
            $tpl->assign('cats', $cats);
            $tpl->assign('cats_num', $cats_num);            
          
            $act = 'questions_form';
            break;
            
          case 'save':
            $q_id = $db->real_escape_string($_GET['id']);
            
            $question = $db->real_escape_string(htmlspecialchars(rmslashes($_POST['question'])));
            $cat = $db->real_escape_string(htmlspecialchars(rmslashes($_POST['cat'])));
            $level = $db->real_escape_string(htmlspecialchars(rmslashes($_POST['level'])));
            $ans_1 = $db->real_escape_string(htmlspecialchars(rmslashes($_POST['ans_1'])));
            $ans_2 = $db->real_escape_string(htmlspecialchars(rmslashes($_POST['ans_2'])));
            $ans_3 = $db->real_escape_string(htmlspecialchars(rmslashes($_POST['ans_3'])));
            $ans_4 = $db->real_escape_string(htmlspecialchars(rmslashes($_POST['ans_4'])));
            $correct = $db->real_escape_string(htmlspecialchars(rmslashes($_POST['correct'])));
            
            $result = $db->query("SELECT * FROM `questions` WHERE `id`='$q_id' LIMIT 1");
            $q++;
            if ($result->num_rows > 0) {
              $row = $result->fetch_object();
              $filename = $row->image; 
            }
            else
              $filename = '';
            $result->free_result();
            
            if (isset($_FILES['file']))
              if ( (($_FILES['file']['type'] == 'image/jpeg') || ($_FILES['file']['type'] == 'image/pjpeg')) && ($_FILES['file']['size'] < 20000000) )
              {
                $filename = ($filename=='') ? md5($question.rand(10, 10000)).'.jpg' : $filename;
                $filename_full = $cfg['data_dir'].'/images/'.$filename;
                
                if ($q_id > 0)
                  if (file_exists('data/images/'.$filename_full))
                    unlink('data/images/'.$filename_full);
    

                $uploadedfile = $_FILES['file']['tmp_name'];

                $src = imagecreatefromjpeg($uploadedfile);
                list($width, $height) = getimagesize($uploadedfile);
                if ($width > 600 || $height > 300)
                {
                  $newwidth = 600;
                  $newheight = ($height/$width)*$newwidth;
                  //$newheight = ($newheight > 350) ? 350 : $newheight;
                  if ($newheight > 350) {
                    $newheight = 350;
                    $newwidth = ($newheight*$width)/$height;
                  }
                  $tmp = imagecreatetruecolor($newwidth, $newheight);

                  imagecopyresampled($tmp, $src ,0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                  imagejpeg($tmp, $filename_full, 100);

                  imagedestroy($src);
                  imagedestroy($tmp);
                }
                else
                {
                  move_uploaded_file($uploadedfile, $filename_full);
                }
              }
            
            if (is_numeric($q_id))
            {
              $db->query("UPDATE `questions` SET `question`='$question', `cat`='$cat', `level`='$level', `image`='$filename', `ans_1`='$ans_1', `ans_2`='$ans_2', `ans_3`='$ans_3', `ans_4`='$ans_4', `correct`='$correct' WHERE `id`='$q_id' LIMIT 1");
              $q++;
            }
            else
            {
              $db->query("INSERT INTO `questions` (`question`, `cat`, `level`, `image`, `ans_1`, `ans_2`, `ans_3`, `ans_4`, `correct`) VALUES ('$question', '$cat', '$level', '$filename', '$ans_1', '$ans_2', '$ans_3', '$ans_4', '$correct')");
              $q++;
            }
            
            $redir = 'admin.php?act=questions';
            break;
            
          case 'delete':
            $q_id = $db->real_escape_string($_GET['id']);
            $db->query("DELETE FROM `questions` WHERE `id`='$q_id' LIMIT 1");
            $q++;
            
            $redir = 'admin.php?act=questions';
            break;
            
          case 'deleteimage':
            $q_id = $db->real_escape_string($_GET['id']);
            
            $result = $db->query("SELECT `image` FROM `questions` WHERE `id`='$q_id' LIMIT 1");
            $q++;
            $row = $result->fetch_object();
            $result->free_result();
            $filename = $row->image;
            
            if (file_exists('data/images/'.$filename))
               unlink('data/images/'.$filename);
            
            $db->query("UPDATE `questions` SET `image`='' WHERE `id`='$q_id'");
            $q++;
            
            $redir = 'admin.php?act=questions&do=edit&id='.$q_id;
            break;
        }
        break;
        
      case 'categories':
        $validdo = array('list', 'new', 'edit', 'save', 'delete');
        if (!in_array($do, $validdo))
          $do = 'list';

        switch ($do)
        {
          default:
          case 'list':
            $act = 'categories_list';
            
            $result = $db->query("SELECT * FROM `categories` ORDER BY `id` ASC");
            $q++;
            
            $cats_num = $result->num_rows;
            $cats = array();
          
            while ($row = $result->fetch_array(MYSQLI_ASSOC))
              $cats[] = $row;
            $result->free_result();
          
            $tpl->assign('cats_num', $cats_num);
            $tpl->assign('cats', $cats);
            break;
          
          case 'edit':
            $c_id = $db->real_escape_string($_GET['id']);
             
            $result = $db->query("SELECT * FROM `categories` WHERE `id`='$c_id'");
            $q++;
            $question = $result->fetch_object();
            $result->free_result();
            
            $tpl->assign('c_id', $question->id);
            $tpl->assign('name', $question->name);
            $tpl->assign('desc', $question->desc);
          case 'new':
            $act = 'categories_form';
            break;
          
          case 'save':
            $c_id = $db->real_escape_string($_GET['id']);
            
            $name = $db->real_escape_string(htmlspecialchars(rmslashes($_POST['name'])));
            $desc = $db->real_escape_string(htmlspecialchars(rmslashes($_POST['desc'])));
              
            if (is_numeric($c_id))
            {
              $db->query("UPDATE `categories` SET `name`='$name', `desc`='$desc' WHERE `id`='$c_id' LIMIT 1");
              $q++;
            }
            else
            {
              $db->query("INSERT INTO `categories` (`name`, `desc`) VALUES ('$name', '$desc')");
              $q++;
            }
            
            $redir = 'admin.php?act=categories';
            break;
          
          case 'delete':
            $c_id = $db->real_escape_string($_GET['id']);
            $db->query("DELETE FROM `categories` WHERE `id`='$c_id' LIMIT 1");
            $q++;
            
            $redir = 'admin.php?act=categories';
            break;
        }
        break;
        
      case 'users':
        $validdo = array('list', 'new', 'save', 'delete');
        if (!in_array($do, $validdo))
          $do = 'list';

        switch ($do)
        {
          default:
          case 'list':
            $act = 'users_list';
            
            $result = $db->query("SELECT * FROM `users` ORDER BY `id` ASC");
            $q++;
            
            $users_num = $result->num_rows;
            $users = array();
          
            while ($row = $result->fetch_array(MYSQLI_ASSOC))
              $users[] = $row;
            $result->free_result();
          
            $tpl->assign('users_num', $users_num);
            $tpl->assign('users', $users);
            break;
          
          case 'edit':
            break;
          
          case 'save':
            break;
          
          case 'delete':
            break;
        }
        break;
        
      case 'info':
        $result = $db->query("SELECT COUNT(*) AS `qcount` FROM `questions`");
        $q++;        
        $row1 = $result->fetch_object();
        $result->free_result();
        
        $result = $db->query("SELECT COUNT(*) AS `ucount` FROM `users`");
        $q++;        
        $row2 = $result->fetch_object();
        $result->free_result();
        
        $tpl->assign('qcount', $row1->qcount);
        $tpl->assign('ucount', $row2->ucount);
        break; 
    }
    
    $displaypage = $act;
  }   
  else
  {
    $tpl->assign('error', 'Απαγορεύεται η πρόσβαση.');
    $displaypage = 'error';
  }
  
  // ---------------------------------------------------------------------------
  
  if ($redir!='')
  {
    header("Location: $redir");
    exit;
  }
  else
  {
    $tpl->assign('q', $q);
    $tpl->assign('gentime', round(microtime(true)-$time_start, 4));
    $tpl->display('adm_'.$displaypage.'.tpl');
  }
  
?>
