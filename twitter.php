<?php
require_once 'tempMailBOT.php';
require_once 'createAccount.php';

$tempMailBOT = new TempMailBOT();
$mail = $tempMailBOT->getNewMail();

$createAccount = new CreateAccount();
$createAccount->beginVerification('John Travolta', $mail);

while(!$tempMailBOT->getActivationCode('twitter')){
    sleep(5);
}

$activationCode = $tempMailBOT->getActivationCode('twitter');