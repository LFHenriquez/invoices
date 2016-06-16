<?php
    echo $this->Form->create('Invoice', [
        'url' => '/invoices/validation',
        'horizontal' => true,
        'columns' => [
            'label' => 4,
            'input' => 4,
            'error' => 0
        ],
        'type' => 'file'
    ]);
    echo $this->Form->file('file',[
    	'_button' => [
    		'label' => 'facture signée'
    	]
    ]);
    echo "<br>";
    echo $this->Form->submit('Upload') ;
    echo $this->Form->end() ;
?>
<script>
$(function(){
    var filepath = $(this).attr('data-filepath');
    top.location.href = "/invoices/download/";
});
</script>