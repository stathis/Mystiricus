{include file="header.tpl"}

    <div class="pagetitle">{$username}</div>
    <small>{if $acl_level>10}Προσωπικό{else}Παίκτης{/if}</small><br /><br />
    
    <table border="0">
    {*<tr><th>Κωδικός (md5)</th><td>{$password}</td></tr>*}
    <tr><th>E-mail</th><td>{$email}</td></tr>
    <tr><th>Εγγραφή στις</th><td>{$registered}</td></tr>
    <tr><th>Τελευταία είσοδος</th><td>{$last_login}</td></tr>
    <tr><th>Πόντοι</th><td>{$points}</td></tr>
    <tr><th>Συνολικές απαντήσεις</th><td>{$q_total}</td></tr>
    <tr><th>Σωστές απαντήσεις</th><td>{$q_answered}</td></tr>
    </table>
    <br />
    <br />
    <div align="center" style="font-weight: bold;">Διαχείριση λογαριασμού</div>
    &raquo; <a href="index.php?act=profile&amp;do=changepassword">Αλλαγή κωδικού πρόσβασης</a><br />
    &raquo; <a href="index.php?act=profile&amp;do=startover">Διαγραφή των πόντων, βαθμών και απαντήσεών σας και έναρξη νέου παιχνιδιού.</a>


{include file="footer.tpl"}