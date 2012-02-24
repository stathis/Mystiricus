<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="mystiricus.css" rel="stylesheet" type="text/css" />
<title>MYSTIRICUS</title>
</head>

<body>
<div align="center">
<table width="800" border="0">
  <tr>
    <td height="100" colspan="3" align="center" valign="middle">
      <img src="images/header.png" alt="" /><br />
{if $loggedin==false}
      Καλωσορίσατε στο mystiricus.
{else}
      <small>
        Είστε συνδεδεμένος/η ως <strong><a href="index.php?act=profile" title="Προφίλ">{$username}</a></strong> &bull; 
        Έχετε <strong>{$points}</strong> πόντους &bull; <a href="index.php?act=logout">Αποσύνδεση</a>
        {if $acl_level>=10}&bull; <a href="admin.php">Πίνακας ελέγχου</a>{/if}
      </small>
{/if}
    </td>
  </tr>
  <tr>
    <td width="100" align="left" valign="top">
      <a href="index.php?act=index">Κεντρική</a><br />
{if $loggedin==false}
      <a href="index.php?act=login"><strong>Είσοδος</strong></a>
      <a href="index.php?act=register">Εγγραφή</a><br />
{else}
      <a href="index.php?act=play"><strong>Παίξε!!!</strong></a><br />
      <a href="index.php?act=profile">Προφίλ</a><br />
      <a href="index.php?act=statistics">Στατιστικά</a><br />
      <a href="index.php?act=logout">Αποσύνδεση</a><br />
{/if}
      <br />
      <a href="index.php?act=links">Σύνδεσμοι</a><br />
      <a href="index.php?act=about">Περί</a><br />
    </td>
    <td width="700" colspan="2" align="left" valign="top">
