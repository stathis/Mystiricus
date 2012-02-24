<?php
  $time_start = microtime(true);
  
  session_start();
  
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
    }
    else
    {
      setcookie($uid, "", time()-60*60*24*30, '/');
      setcookie($key, "", time()-60*60*24*30, '/');
      
      $redir = 'index.php';
      $loggedin = false;
    }
    
  }
  else {
    $loggedin = false;
    $tpl->assign('showregister', true);
  }
  
  $act = $_GET['act'];
  
  $data_dir = $cfg['data_dir'];
  $tpl->assign('data_dir', $data_dir);
  $tpl->assign('loggedin', $loggedin);
  
  switch ($loggedin)
  {
    case true:
      $validact = array('index', 'profile', 'statistics', 'play', 'answerq', 'links', 'about', 'logout');
      if (!in_array($act, $validact))
        $act = 'index';
            
      $tpl->assign('user_id', $user_id);
      $tpl->assign('username', $user->username);
      $tpl->assign('password', $user->password);
      $tpl->assign('email', $user->email);
      $tpl->assign('acl_level', $acl_level);
      $tpl->assign('registered', date("d/m/Y H:i:s", $user->registered));
      $tpl->assign('last_login', date("d/m/Y H:i:s", $user->last_login));
      $tpl->assign('points', $user->points);
      $tpl->assign('q_answered', $user->q_answered);
      $tpl->assign('q_total', $user->q_total);
      
      switch ($act)
      {
        default:
        case '':
        case 'index':
          break;
        
        case 'profile':
          if (isset($_GET['do']))
          {
            $do = $_GET['do'];
            $validdo = array('startover', 'changepassword', '');
            
            if (in_array($do, $validdo)) {
            
              switch ($do)
              {              
                case 'startover':
                  $db->query("DELETE FROM `answers` WHERE `u_id`='$user_id'");
                  $q++;
                  $db->query("UPDATE `users` SET `story_read`='0', `points`='0', `q_answered`='0', `q_total`='0', `q_last_cat`='0' WHERE `id`='$user_id'");
                  $q++;
                  
                  $redir = 'index.php?act=profile';
                  break;
                
                case 'changepassword':
                  $act = 'profile_changepassword';
                  if (isset($_POST['submit']))
                  {
                    $currpass = rmslashes($_POST['currpass']);
                    $newpass1 = rmslashes($_POST['newpass1']);
                    $newpass2 = rmslashes($_POST['newpass2']);
                    
                    if (md5($currpass)==$user->password)
                      if ($newpass1==$newpass2)
                      {
                        $newpass = md5($newpass1);                        
                        $time_now = time();
                        
                        $db->query("UPDATE `users` SET `password`='$newpass' WHERE `id`='$user_id'");
                        $q++;
                        
                        setcookie($uid, $user_id, $time_now+$cfg['biscuit_time'], '/');            
                        setcookie($key, biscuit_md5($newpass), $time_now+$cfg['biscuit_time'], '/');
                        
                        $inform = 'Ο κωδικός αλλάχθηκε επιτυχώς!';
                      }
                      else
                        $inform = 'Οι κωδικοί δεν ταιριάζουν.';
                    else
                      $inform = 'Ο κωδικός που εισάγατε είναι λάθος!';
                      
                    $tpl->assign('inform', $inform);
                  }
                  break;
                
                default:
                case '':
                  break;
                
              }
            
            }
          }
          break;
          
        case 'statistics':          
          $result = $db->query("SELECT * FROM `users` WHERE `active`='1' ORDER BY `points` DESC LIMIT 20");
          $q++;
          
          $stats_num = $result->num_rows;
          $stats = array();
          
          while ($row = $result->fetch_array(MYSQLI_ASSOC))
            $stats[] = $row;
          $result->free_result();
          
          $tpl->assign('stats_num', $stats_num);
          $tpl->assign('stats', $stats);
          break;
        
        case 'play':                  
          if ($user->story_read == 0)
          { // if its the first time, show the story
            if ($_GET['storyread'] == 1)
            {
              $db->query("UPDATE `users` SET `story_read`='1' WHERE `id`='$user_id'"); // mark the story read
              $q++;
              
              $redir = 'index.php?act=play';
            }
            else
            {
              $act = 'story';
            }
          }
          else
          {
            $act = 'question';
            
            $last_cat = $user->q_last_cat;
            
            $result = $db->query("SELECT `id` AS `catcount` FROM `categories` ORDER BY `id` DESC");
            $q++;
            $row = $result->fetch_object();
            $result->free_result();
            $total_cats = $row->catcount;
            
            //echo "Total: $total_cats<br />";
            
            $next_cat = ($last_cat==$total_cats) ? 1 : $last_cat + 1;
            
            //echo "Last cat: $last_cat<br />Next cat:$next_cat<br />";
            
            $cat_order = "'$next_cat'";
            //if ($next_cat<=$total_cats)
            for ($i=$next_cat+1;$i<=$total_cats;$i++)
              $cat_order .= ", '$i'";
            //if ($next_cat<=$total_cats)
            for ($i=1;$i<$next_cat;$i++)
              $cat_order .= ", '$i'";
            
            //echo $cat_order;
            
            $result = $db->query("SELECT `a`.* FROM `answers` `a` LEFT JOIN `questions` `q` ON `a`.`q_id`=`q`.`id` WHERE `a`.`u_id`='$user_id' AND `q`.`cat`='$next_cat' AND `a`.`answered`='0' LIMIT 1");
            $q++;
            
            if ($result->num_rows>0)
            {
              $row = $result->fetch_object();
              $result->free_result();
              $q_id = $row->q_id;
              
              $result = $db->query("SELECT * FROM `questions` WHERE `id`='$q_id' AND `cat`>0 LIMIT 1");
              $q++;
              $row2 = $result->fetch_object();
              $result->free_result();
            }
            else
            {
              $result = $db->query("SELECT `q`.* FROM `questions` `q` LEFT JOIN `answers` `a` ON `a`.`q_id`=`q`.`id` AND `a`.`u_id`='$user_id' WHERE `a`.`u_id` IS NULL AND `q`.`cat`>0 ORDER BY field(`q`.`cat`, $cat_order) ASC, `q`.`level` ASC LIMIT 1");
              $q++;
              if ($result->num_rows==0)
                $finished = true;
              else
                $row2 = $result->fetch_object();
              $result->free_result();
            }
            
            if ($finished == false) {
            $result = $db->query("SELECT * FROM `categories` WHERE `id`='$row2->cat' LIMIT 1");
            $q++;
            if ($result->num_rows>0) {
              $row3 = $result->fetch_object();
              $q_cat_name = $row3->name;
            }
            $result->free_result();
                        
            //$tpl->assign('question_id', $row2->id);
            $_SESSION['q_id'] = $row2->id;
            
            $tpl->assign('question', $row2->question);
            $tpl->assign('question_cat', $q_cat_name);
            $tpl->assign('level', $row2->level);
            $tpl->assign('worth', ($row2->level)*($row2->level));
            
            if ($row2->image!='')
              $tpl->assign('question_img', $data_dir.'/images/'.$row2->image);
            
            $tpl->assign('ans_1', $row2->ans_1);
            $tpl->assign('ans_2', $row2->ans_2);
            $tpl->assign('ans_3', $row2->ans_3);
            $tpl->assign('ans_4', $row2->ans_4);
            }
            else
            {
              $act = 'finished';
            }
          }
          break;
          
        case 'answerq':
          $act = 'question';
          $redir = 'index.php?act=play';
                    
          if (!isset($_POST['answer'])||!isset($_SESSION['q_id'])||$_POST['answer']<1||$_POST['answer']>4)
            $redir = 'index.php?act=play';
          else
          {
            $ans = $_POST['answer'];
            $q_id = $_SESSION['q_id'];
            
            // get question data
            $result = $db->query("SELECT * FROM `questions` WHERE `id`='$q_id' LIMIT 1");
            $q++;
            $row = $result->fetch_object();
            $result->free_result();
            
            // check row existance in "answers", and if exists get data
            $result = $db->query("SELECT * FROM `answers` WHERE `u_id`='$user_id' AND `q_id`='$q_id' LIMIT 1");
            $q++;
            if ($result->num_rows>0)
            {
              $updaterow = 1;
              $row2 = $result->fetch_object();
            }
            $result->free_result();
            
            if ($ans==$row->correct) // CORRECT ANSWER -------------------------
            {
              $award = (($row->level)*($row->level)) - $row2->times_wrong;
              $total_award = $user->points + $award;
              $total_ans = $user->q_answered + 1;
              $total_q = $user->q_total + 1;
              $last_cat = $row->cat;
              
              // Update the users data; Set new total points, total answers etc
              if ($award>0)
              {
                $db->query("UPDATE `users` SET `points`='$total_award', `q_answered`='$total_ans', `q_total`='$total_q', `q_last_cat`='$last_cat' WHERE `id`='$user_id'");
                $q++;
              }
              else
              {
                $db->query("UPDATE `users` SET `q_answered`='$total_ans', `q_total`='$total_q', `q_last_cat`='$last_cat' WHERE `id`='$user_id'");
                $q++;            
              }  
              
              // Update answer status
              if ($updaterow)
              { // Row exists, lets update it
                $db->query("UPDATE `answers` SET `answered`='1' WHERE `u_id`='$user_id' AND `q_id`='$q_id'");
                $q++;
              }
              else
              { // Row doesnt exist. Let's create it.
                $db->query("INSERT INTO `answers` (`u_id`, `q_id`, `answered`, `times_wrong`) VALUES ('$user_id', '$q_id', '1', '0')");
                $q++;
              }
            }
            else // WRONG ANSWER -----------------------------------------------
            {
              $award = 2*$row->level;
              $total_award = ($user->points >= $award) ? $user->points - $award : $user->points;
              //$total_ans = $user->q_answered + 1;
              $total_q = $user->q_total + 1;
              $last_cat = $row->cat;
              
              // Update the users data; Set new total points, total answers etc
              $db->query("UPDATE `users` SET `points`='$total_award', `q_total`='$total_q', `q_last_cat`='$last_cat' WHERE `id`='$user_id'");
              $q++;
              
              // Update answer status
              if ($updaterow)
              { // Row exists, lets update it
                $times_wrong = $row2->times_wrong + 1;
                $db->query("UPDATE `answers` SET `times_wrong`='$times_wrong' WHERE `u_id`='$user_id' AND `q_id`='$q_id'");
                $q++;
              }
              else
              { // Row doesnt exist. Let's create it.
                $db->query("INSERT INTO `answers` (`u_id`, `q_id`, `answered`, `times_wrong`) VALUES ('$user_id', '$q_id', '0', '1')");
                $q++;
              }
            }
            
            unset($_SESSION['q_id']);
          }
          break;
          
        case 'links':
          // Display link page.
          break;
          
        case 'about':
          // Display about page.
          break;
        
        case 'logout': // Delere cookies and reload
          setcookie($uid, "", time()-60*60*24*30, '/');
          setcookie($key, "", time()-60*60*24*30, '/');
          $redir = 'index.php';
          break;
      }
      $displaypage = $act;
      break;
    
    case false:
      $validact = array('index', 'register', 'links', 'about', 'login');
      if ( !in_array($act, $validact) )
        $act = 'index';
      switch ($act)
      {
        default:
        case '':
        case 'index':
          // Just show main page for the anonymous user. Nothing special
          break;
          
        case 'register':
          if ($cfg['register_enabled']==1) {          
            $tpl->assign('register_enabled', 1);
            $tpl->assign('signedup', 0);
                    
            if (isset($_POST[submit]))
            {
              $username = rmslashes($_POST['username']);
              $password1 = rmslashes($_POST['password1']);
              $password2 = rmslashes($_POST['password2']);
              $email = rmslashes($_POST['email']);
              $error = '';
            
              if ($username=='' || !ctype_alnum($username))
                $error .= 'Το όνομα χρήστη πρέπει να μην είναι κενός και να περιέχει μόνο λατινικούς χαρακτήρες και αριθμούς.<br />';
              else
              {
                $result = $db->query("SELECT * FROM `users` WHERE `username`='$username'");
                $q++;
                if ($result->num_rows>0)
                  $error .= 'Το όνομα χρήστη που επιλέξατε υπάρχει ήδη. Παρακαλώ δοκιμάστε άλλο.<br />';
                $result->free_result();
              }              
              if (trim($password1!=$password2))
                $error .= 'Οι κωδικοί δεν είναι όμοιοι.<br />';
              if ($password1=='' || strlen(trim($password1))< 6 || !ctype_alnum($password1))
                $error .= 'Ο κωδικός πρέπει να είναι τουλάχιστον 6 χαρακτήρων.<br />';//concatenate more to $error 
              if(trim($email)=='')
                $error .= 'Το e-mail είναι απαραίτητο για την εγγραφή σας!<br />';
              else
                if(!eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$', $email))
                  $error .= 'Το e-mail που εισάγατε δεν έχει την σωστή μορφή!';                
              
              if ($error=='')
              {
                $password = md5($password1);
                $datetime = time();
            
                $db->query("INSERT INTO `users` (`username`, `password`, `email`, `registered`, `active`) VALUES ('$username', '$password', '$email', '$datetime', '1')");
                $q++;
            
                $tpl->assign('signedup', 1);
              }
              else
              {
                $tpl->assign('username', $username);
                $tpl->assign('email', $email);
                $tpl->assign('error', $error);          
              }          
            }
          }
          else
          {
            $tpl->assign('register_enabled', 0);
          }
          break;
          
        case 'links':
          // Display link page.
          break;
        
        case 'about':
          // Display about page.
          break;
          
        case 'login':
          if (($_POST['user']!='')&&($_POST['pass']!=''))
          {
            $username = $db->real_escape_string($_POST['user']);
            $password = $_POST['pass'];
            $result = $db->query("SELECT `id`,`password` FROM `users` WHERE `username`='$username' LIMIT 1");
            $q++;
            $row = $result->fetch_object();
            $result->free_result();
            
            if (md5($password) == $row->password)
            {
              $time_now = time();
              $user_ip = $_SERVER['REMOTE_ADDR'];
            
              setcookie($uid, $row->id, $time_now+$cfg['biscuit_time'], '/');            
              setcookie($key, biscuit_md5($row->password), $time_now+$cfg['biscuit_time'], '/');
            
              $db->query("UPDATE `users` SET `last_login`='$time_now', `last_ip`='$user_ip' WHERE `id`='$row->id' LIMIT 1");
              $q++;
            
              $redir = 'index.php?act=home';
            }

          }
          break;
        
      }
      $displaypage = $act;
      break;
  
  }


  if ($redir!='')
  {
    header("Location: $redir");
    exit;
  }
  else
  {
    $tpl->assign('q', $q);
    $tpl->assign('gentime', round(microtime(true)-$time_start, 4));
    $tpl->display($displaypage.'.tpl');
  }

?>
