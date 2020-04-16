<?php
require_once 'tempMailBOT.php';
require_once 'createAccount.php';

$tempMailBOT = new TempMailBOT();
$mail = $tempMailBOT->getNewMail();

$createAccount = new CreateAccount('John Travolta', $mail);
$createAccount->beginVerification();

while(!$tempMailBOT->getActivationCode('twitter')){
    sleep(10);
}
$activationCode = $tempMailBOT->getActivationCode('twitter');

$createAccount->sendActivationCode($activationCode);
$createAccount->setAccountPassword("1597536548520");
// $createAccount->skipPhoto();
// $createAccount->skipBio();
// $createAccount->setLanguages();
// $createAccount->setInterests();
// $createAccount->setRecommendations();
// $createAccount->setNotificationsPermission();