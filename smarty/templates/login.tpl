{include file="header.tpl"}

    <div class="pagetitle">Είσοδος</div>
    Εισάγετε τα στοιχεία σας.<br /><br />

<form id="form1" name="form1" method="post" action="index.php?act=login">
<div align="center" style="background-color: #FFF;">
<table>
  <tr><th>Χρήστης:</th><td><input name="user" type="text" id="user" maxlength="255" /></td></tr>
  <tr><th>Κωδικός:</th><td><input name="pass" type="password" id="pass" maxlength="255" /></td></tr>
  <tr><td colspan="2" align="center"><input type="submit" name="submit" id="submit" value="Είσοδος" /></td></tr>
</table>
</div>
</form>

{include file="footer.tpl"}