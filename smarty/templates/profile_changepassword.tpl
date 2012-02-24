{include file="header.tpl"}

    <div class="pagetitle">Αλλαγή κωδικού</div>
    <br /><br />
    
    {if $inform!=''}{$inform}<br />{/if}
    <form id="form1" name="form1" method="post" action="index.php?act=profile&do=changepassword">
    <div align="center" style="background-color: #FFF;">
    <table>
      <tr><th align="right">Ισχύον κωδικός:</th><td><input name="currpass" type="password" id="currpass" maxlength="255" /></td></tr>
      <tr><th align="right">Νέος κωδικός:</th><td><input name="newpass1" type="password" id="newpass1" maxlength="255" /></td></tr>
      <tr><th align="right">Ξανά:</th><td><input name="newpass2" type="password" id="newpass2" maxlength="255" /></td></tr>
      <tr><td colspan="2" align="center"><input type="submit" name="submit" id="submit" value="ΟΚ" /></td></tr>
    </table>
    </div>
    </form>

{include file="footer.tpl"}