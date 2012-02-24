{include file="adm_header.tpl"}

<form id="form1" method="post" action="admin.php?act=questions&do=save&id={$q_id}" enctype="multipart/form-data">
  <div align="center">
  <br /><strong>Πληροφορίες ερώτησης:</strong><br />
  <table>
    <tr><td>Ερώτηση</td><td><input id="question" name="question" type="text" size="100" value="{$question}"/></td></tr>
    <tr><td>Κατηγορία</td><td>
    <select name="cat">
      <option value="-1"{if $cat==-1} selected="selected"{/if}>---</option>
    {section name=nr loop=$cats}
      <option value="{$cats[nr].id}"{if $cat==$cats[nr].id} selected="selected"{/if}>{$cats[nr].name}</option>
    {/section}
    </select></td></tr>
    <tr><td>Δυσκολία</td><td>
    <select name="level">
      <option value="1"{if $level==1} selected="selected"{/if}>1</option>
      <option value="2"{if $level==2} selected="selected"{/if}>2</option>
      <option value="3"{if $level==3} selected="selected"{/if}>3</option>
      <option value="4"{if $level==4} selected="selected"{/if}>4</option>
      <option value="5"{if $level==5} selected="selected"{/if}>5</option>    
    </select>
    </td></tr>
    <tr><td>Εικόνα</td><td>{if $image!=''}<a title="Το όνομα αρχείου της ήδη ανεβασμένης εικόνας" href="{$imagepath}" target="_blank">{$image}</a> <a href="admin.php?act=questions&amp;do=deleteimage&amp;id={$q_id}"><img style="border: none;" src="images/delete.png" alt="Διαγραφή" title="Διαγραφή" /></a><br />{/if}<input type="file" name="file" id="file" /></td></tr>
  </table>
  <br /><strong>Απαντήσεις:</strong><br />
  <table>
    <tr><td>Απάντηση Α <input type="radio" name="correct" value="1" {if $correct==1}checked="checked"{/if} /></td><td><input id="ans_1" name="ans_1" type="text" size="100" value="{$ans_1}"/></td></tr>
    <tr><td>Απάντηση Β <input type="radio" name="correct" value="2" {if $correct==2}checked="checked"{/if} /></td><td><input id="ans_2" name="ans_2" type="text" size="100" value="{$ans_2}"/></td></tr>
    <tr><td>Απάντηση Γ <input type="radio" name="correct" value="3" {if $correct==3}checked="checked"{/if} /></td><td><input id="ans_3" name="ans_3" type="text" size="100" value="{$ans_3}"/></td></tr>
    <tr><td>Απάντηση Δ <input type="radio" name="correct" value="4" {if $correct==4}checked="checked"{/if} /></td><td><input id="ans_4" name="ans_4" type="text" size="100" value="{$ans_4}"/></td></tr>
  </table>
  
  <br />
  <input id="saveForm" class="button_text" type="submit" name="submit" value="Αποθήκευση" />
  <br /><br />
  </div>
</form>


{include file="adm_footer.tpl"}
