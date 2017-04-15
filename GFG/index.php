<?php
require("lib/requires.php")
?>


<div id="leftbox" >
    <textarea  id="json" name="json" placeholder="Input your json" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Input your json'"></textarea>
    <div style="width: 100%; ">
        <button onclick="btnAction('validator')" class="button validate">Validate</button>
        <button onclick="btnAction('filter')" class="button filter">Filter</button>
    </div>
</div>   
<div id ="rightbox">
    <div  id="logbox"></div>
</div>   


<script>
$( document ).ready(function() {
   $("#json").on('change keyup paste', function() {
    	$('#logbox').html('');
	});

});
</script>





