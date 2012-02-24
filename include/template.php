<?php

  require($cfg['smarty_lib_path']);
  $tpl = new Smarty();
  $tpl->template_dir = $cfg['smarty_template_dir'];
  $tpl->compile_dir = $cfg['smarty_compile_dir'];
  $tpl->cache_dir = $cfg['smarty_cache_dir'];
  $tpl->config_dir = $cfg['smarty_config_dir'];
  
  
?>