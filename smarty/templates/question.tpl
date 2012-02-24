{include file="header.tpl"}
    
    {if $question_img!=''}
    <div align="center" style="border-color: #000000; border-style: solid; border-width: 1px;"><img src="{$question_img}" alt="" /></div>
    {/if}
    <form id="form1" name="form1" method="post" action="index.php?act=answerq">
    {*<input type="hidden" name="q_id" value="{$question_id}" />*}    
    <div style="background-color: #FFF;">Ερώτηση: <strong>{$question}</strong></div>
    <small>Βαθμός δυσκολίας: <strong>{$level}</strong></small><br />
    <small>Κατηγορία: <strong>{$question_cat}</strong><br />
    <br />
    <div align="center">
    <table border="0">
    {if $ans_1!=''}
    <tr><th>Α.</th><td><input type="radio" name="answer" value="1" />{$ans_1}</td></tr>{/if}    
    {if $ans_2!=''}
    <tr><th>Β.</th><td><input type="radio" name="answer" value="2" />{$ans_2}</td></tr>{/if}    
    {if $ans_3!=''}
    <tr><th>Γ.</th><td><input type="radio" name="answer" value="3" />{$ans_3}</td></tr>{/if}    
    {if $ans_4!=''}
    <tr><th>Δ.</th><td><input type="radio" name="answer" value="4" />{$ans_4}</td></tr>{/if}
    
    </table>
    </div>
    <br />
    <div align="center"><input type="submit" name="submit" id="submit" value="Υποβολή" /></span>
    </form>

{include file="footer.tpl"}