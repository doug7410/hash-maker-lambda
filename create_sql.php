<?php declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';

lambda(function ($purchases) {
    $sql = '';

    /**
     * @param $purchase
     * @return bool|string
     */
    function createHash($purchase)
    {
        $decryptedUniqueId = $purchase['first_name'] . $purchase['last_name'] . $purchase['date_of_birth'];

        $salt = '$2a$07$usesomesillystringforsalt$';
        $encryptedUniqueId = substr(crypt($decryptedUniqueId, $salt), strlen($salt) - 1);
        return $encryptedUniqueId;
    }

    foreach ($purchases as $purchase) {
        $encryptedUniqueId = createHash($purchase);

        $sql .= "update purchases set unique_id = \"{$encryptedUniqueId}\" where id = '{$purchase['id']}';";
    }

    return base64_encode($sql);
});
