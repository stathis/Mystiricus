{include file="adm_header.tpl"}

    <a href="admin.php?act=categories&amp;do=new"><img style="border: none;" src="images/add.png" alt="" /> 
      <span style="font-size: 18px;">Προσθήκη κατηγορίας</span></a><br /><br />

    <table border="0" width="100%">
      <tr><th width="1">#ID</th><th width="1"></th><th width="140">Όνομα</th><th>Περιγραφή</th><th width="1"></th></tr>
{section name=nr loop=$cats}
      <tr bgcolor="{cycle values="#eeeeee,#dddddd"}">
        <td>{$smarty.section.nr.index_next}</td>
        <td><a href="admin.php?act=categories&amp;do=edit&amp;id={$cats[nr].id}"><img style="border: none;" src="images/pencil.png" alt="Επεξεργασία" /></a></td>
        <td>{$cats[nr].name}</td>
        <td>{$cats[nr].desc}</td>
        <td><a href="admin.php?act=categories&amp;do=delete&amp;id={$cats[nr].id}"><img style="border: none;" src="images/delete.png" alt="Διαγραφή" /></a></td>
      </tr>
{/section}
    </table>

{include file="adm_footer.tpl"}