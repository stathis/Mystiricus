<?php
  $cfg = array (
    'dbhost'       => 'localhost',
    'dbname'       => '',
    'dbuser'       => '',
    'dbpass'       => '',
    
    'biscuit_name' => 'mystiricus_XXXXX',
    'biscuit_time' => 28800,
    
    'rndword'      => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXX',
    
    'register_enabled' => 1,
    
    'data_dir' => './data',
    
    // Smarty stuff follow
    'smarty_lib_path'     => './Smarty-2.6.22/libs/Smarty.class.php',
    
    'smarty_template_dir' => './smarty/templates',
    'smarty_compile_dir'  => './smarty/templates_c',
    'smarty_cache_dir'    => './smarty/cache',
    'smarty_config_dir'   => './smarty/configs'
  );
  
  function biscuit_md5($str) {
     return md5($str.$cfg['rndword']);
  }
  
/*
function qsprintf()
{
    $numargs  = func_num_args();
    $arg_list = func_get_args();
    $format  = $arg_list[0];
    $next_arg_list = array();
    for ($i = 1; $i < $numargs; $i++)
        $next_arg_list[] = mysqli_real_escape_string($arg_list[$i]);
    return vsprintf($format, $next_arg_list);
}

function qsprintf2()
{
    $numargs  = func_num_args();
    $arg_list = func_get_args();
    $format  = $arg_list[0];
    $next_arg_list = array();
    for ($i = 1; $i < $numargs; $i++)
        $next_arg_list[] = $db->real_escape_string($arg_list[$i]);
    return vsprintf($format, $next_arg_list);
}
*/

function rmslashes($str)
{
    return get_magic_quotes_gpc() ? stripslashes($str) : $str;
}


?>
