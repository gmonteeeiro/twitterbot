<?php
require_once 'tempMailBOT.php';
require_once 'createAccount.php';

$tempMailBOT = new TempMailBOT();
$mail = $tempMailBOT->getNewMail();

$createAccount = new CreateAccount('John Travolta', $mail);
$createAccount->beginVerification();

while(!$tempMailBOT->getActivationCode('twitter')){
    sleep(5);
}
$activationCode = $tempMailBOT->getActivationCode('twitter');

echo $activationCode;

$createAccount->sendActivationCode($activationCode);