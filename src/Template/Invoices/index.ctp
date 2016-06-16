<?php
    echo $this->Form->create('Invoice', [
        'url' => '/invoices/edit',
        'horizontal' => true,
        'columns' => [
            'label' => 4,
            'input' => 4,
            'error' => 0
        ]
    ]);
    echo $this->Form->input('email', [
        'label' => 'Adresse email',
        'type' => 'text'
    ]);
    echo $this->Form->input('password', [
        'label' => 'Mot de passe',
        'type' => 'password'
    ]);
    echo $this->Form->submit('Log In') ;
    echo $this->Form->end() ;
?>