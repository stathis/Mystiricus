{include file="adm_header.tpl"}

    <a href="admin.php?act=questions&amp;do=new"><img style="border: none;" src="images/add.png" alt="" /> 
      <span style="font-size: 18px;">Προσθήκη ερώτησης</span></a><br /><br />

    <table border="0" width="100%">
      <tr><th>#ID</th><th></th><th>Ερώτηση</th><th>Lvl</th><th>Κατηγορία</th><th></th></tr>
{section name=nr loop=$questions}
      <tr bgcolor="{cycle values="#eeeeee,#dddddd"}">
        <td>{$smarty.section.nr.index_next}</td>
        <td><a href="admin.php?act=questions&amp;do=edit&amp;id={$questions[nr].id}"><img style="border: none;" src="images/pencil.png" alt="Επεξεργασία" /></a></td>
        <td>{$questions[nr].question}</td>
        <td>{$questions[nr].level}</td>
        <td width="120">{$questions[nr].cat_name}</td>
        <td><a href="admin.php?act=questions&amp;do=delete&amp;id={$questions[nr].id}"><img style="border: none;" src="images/delete.png" alt="Διαγραφή" /></a></td>
      </tr>
{/section}
    </table>

{include file="adm_footer.tpl"}