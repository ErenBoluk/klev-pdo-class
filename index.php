<?php require 'main.php' ?>

<?= '<pre>' ?>
<?php

$params = array(
    'id' => 2
);
$data = $klev->select('SELECT * FROM person Where pId = :id', $params);
if ($data['rc'] > 0 && $data['status']) {
    echo "Veri Bulundu. <br> İsim : {$data['data'][0]['pName']}";
} else {
    echo 'Veri Bulunamadı.';
}

echo '<hr>';
$Dparams = array(
    'id' => 4
);
$delete = $klev->delete('DELETE FROM person Where pId = :id', $Dparams);
if ($delete) {
    echo 'Veri Silindi.';
} else {
    echo 'Veri Silinemedi.';
}

echo '<hr>';
$Uparams = array(
    'pName' => 'Klev',
    'id' => 3
);
$update = $klev->update('UPDATE  person SET pName = :pName Where pId = :id', $Uparams);
if ($update) {
    echo 'Veri Güncellendi.';
} else {
    echo 'Veri Güncellenemedi.';
}
?>   