{include file="header.tpl"}

    <div class="pagetitle">Στατιστικά</div>

    <table border="0" width="100%">
      <tr><th>#</th><th>Χρήστης</th><th>Πόντοι</th><th>Συνολικές απαντήσεις</th></tr>
{section name=nr loop=$stats}
      <tr bgcolor="{cycle values="#eeeeee,#dddddd"}"><td>{$smarty.section.nr.index_next}</td><td>{$stats[nr].username}</td><td>{$stats[nr].points}</td><td>{$stats[nr].q_total}</td></tr>
{/section}
    </table>

{include file="footer.tpl"}