<?php 
echo $table ;
echo '<hr/>';
echo btform::form_open();
echo btform::form_input('IP : ',array('name'=>'ip')) ;
echo btform::form_submit(array('name'=>'submit'), 'Add to whitelist');
echo btform::form_close();
