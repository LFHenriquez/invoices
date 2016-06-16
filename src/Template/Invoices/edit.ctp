<?php
    echo $this->Form->create('Invoice', [
        'url' => '/invoices/view/',
        'horizontal' => true,
        'columns' => [
            'label' => 4,
            'input' => 6,
            'error' => 0
        ]
    ]);
    foreach ($header as $item) {
        if($item['display_on_invoice'])
            echo $this->Form->input($item['field_name'], [
                'type' => 'text',
                'label' => $item['display_name'],
                'default' => $values[$item['field_name']]
            ]);
    }
    echo $this->Form->submit('Télécharger ma facture personnalisée') ;
    echo $this->Form->end() ;
?>
