{include file="header.tpl"}

    <div class="pagetitle">Εγγραφή</div>
    {if $register_enabled==1}    
    {if $signedup==0}
    Παρακαλώ συμπληρώστε όλα τα παρακάτω πεδία με προσοχή.<br /><br />
    
    {if $error!=''}
    {$error}
    {/if}
    
    <form id="form1" name="form1" method="post" action="index.php?act=register">
    <div align="center" style="background-color: #FFF;">
      <table border="0">
        <tr><th align="right" width="120">Όνομα χρήστη:</th><td><input name="username" type="text" id="user" maxlength="255" value="{$username}" /></td></tr>
        <tr><th align="right">Κωδικός:</th><td><input name="password1" type="password" id="pass1" maxlength="255" /></td></tr>
        <tr><th align="right">Επανάληψη:</th><td><input name="password2" type="password" id="pass2" maxlength="255" /></td></tr>
        <tr><th align="right">E-mail:</th><td><input name="email" type="text" id="email" maxlength="255" value="{$email}" /></td></tr>
        <tr><td colspan="2" align="center"><input type="submit" name="submit" id="submit" value="Εγγραφή" /></td></tr>
      </table>
    </div>
    </form>
    {else}
    <br />
    Η εγγραφή σας ολοκληρώθηκε!<br />
    Κάντε κλικ <a href="index.php?act=login">εδώ</a> για να εισέλθετε.
    {/if}
    {else}
    Οι εγγραφές έχουν απενεργοποιηθεί!
    {/if}

{include file="footer.tpl"}

