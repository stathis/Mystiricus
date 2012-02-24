{include file="adm_header.tpl"}

<form id="form1" method="post" action="admin.php?act=categories&do=save&id={$c_id}">
  <div align="center">
  <br /><strong>Πληροφορίες κατηγορίας:</strong><br />
  <table>
    <tr><td>Όνομα κατηγορίας</td><td><input id="name" name="name" type="text" size="100" value="{$name}"/></td></tr>
    <tr><td>Περιγραφή</td><td><input id="desc" name="desc" type="text" size="100" value="{$desc}"/></td></tr>
  </table>
  
  <br />
  <input id="saveForm" class="button_text" type="submit" name="submit" value="Αποθήκευση" />
  <br /><br />
  </div>
</form>


{include file="adm_footer.tpl"}