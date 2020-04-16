<?php
// require_once 'tempMailBOT.php';

// $tempMailBOT = new TempMailBOT();
// $email = $tempMailBOT->getNewMail();
// echo $email;

require_once 'createAccount.php';
$createAccount = new CreateAccount();
echo $createAccount->getFlowToken();