{include file="adm_header.tpl"}

    <table border="0" width="100%">
      <tr><th width="1">#ID</th><th>User name</th><th>Πόντοι</th><th>Ημ/νία εγγραφής</th><th>Τελευταία είσοδος</th></tr>
{section name=nr loop=$users}
      <tr bgcolor="{cycle values="#eeeeee,#dddddd"}">
        <td>{$smarty.section.nr.index_next}</td>
        <td>{$users[nr].username}</td>
        <td>{$users[nr].points}</td>
        <td>{$users[nr].registered|date_format}</td>
        <td>{if $users[nr].last_login>0}{$users[nr].last_login|date_format}{else}&ndash;{/if}</td>
      </tr>
{/section}
    </table>

{include file="adm_footer.tpl"}