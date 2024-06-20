<!DOCTYPE html>
<html>
<head>
    <title>Yemek Önerisi</title>
    <link rel="stylesheet" href="card.css">
</head>
<body>
<h1>Yemek Önerilerinize Göz Atın</h1>
<button class="button">
    <div class="button-box">
    <span class="button-elem">
      <svg viewBox="0 0 46 40" xmlns="http://www.w3.org/2000/svg">
        <path
            d="M46 20.038c0-.7-.3-1.5-.8-2.1l-16-17c-1.1-1-3.2-1.4-4.4-.3-1.2 1.1-1.2 3.3 0 4.4l11.3 11.9H3c-1.7 0-3 1.3-3 3s1.3 3 3 3h33.1l-11.3 11.9c-1 1-1.2 3.3 0 4.4 1.2 1.1 3.3.8 4.4-.3l16-17c.5-.5.8-1.1.8-1.9z"
        ></path>
      </svg>
    </span>
        <span class="button-elem">
      <svg viewBox="0 0 46 40">
        <path
            d="M46 20.038c0-.7-.3-1.5-.8-2.1l-16-17c-1.1-1-3.2-1.4-4.4-.3-1.2 1.1-1.2 3.3 0 4.4l11.3 11.9H3c-1.7 0-3 1.3-3 3s1.3 3 3 3h33.1l-11.3 11.9c-1 1-1.2 3.3 0 4.4 1.2 1.1 3.3.8 4.4-.3l16-17c.5-.5.8-1.1.8-1.9z"
        ></path>
      </svg>
    </span>
    </div>
</button>
<div class="container">
    <?php
    // Formdan gelen verileri alıyoruz
    $doyuruculuk = $_POST['doyuruculuk'];
    $sure = $_POST['sure'];
    $ogun = $_POST['ogun'];
    $yag_orani = $_POST['yagOrani'];
    $normal = $_POST['normal'];
    $kategori = $_POST['kategori'];
    $protein_orani = $_POST['proteinOrani'];
    $lezzet_profili = $_POST['lezzetProfili'];
    $diyet_programi = $_POST['diyet'];
    $alerji = $_POST['alerjisoru'];



    // JSON dosyasını okuyoruz
    $json = file_get_contents('veri.json');
    $yemekler = json_decode($json, true);

    // Öneri yapacak yemekleri tutacağımız değişkeni tanımlıyoruz
    $onerilen_yemekler = [];

    // Yemekler arasında dolaşıp uygun olanları seçiyoruz
    foreach ($yemekler as $yemek) {

        if($alerji == "evet")
        {
            $alerjenler = isset($_POST['alerjen']) ? (array)$_POST['alerjen'] : [];
            $yemek_alerjenler = isset($yemek['alerjenler']) ? explode(',', $yemek['alerjenler']) : [];
            $alerjen_var = false;
            foreach ($alerjenler as $alerjen) {
                if (in_array($alerjen, $yemek_alerjenler)) {
                    $alerjen_var = true;
                    break;
                }
            }
            if ($alerjen_var) {
            continue;
            }
        }


        if (
            $yemek['doyuruculuk'] == $doyuruculuk &&
            $yemek['sure'] == $sure &&
            $yemek['ogun'] == $ogun &&
            $yemek['yag_orani'] == $yag_orani) 
        {
            if($normal == "evet")
            {
                if(
                    ($yemek['normal'] == "evet" || $yemek['normal'] == "hayır") &&
                    ($yemek['vegan'] == "evet" || $yemek['vegan'] == "hayır") &&
                    ($yemek['vejetaryen'] == "evet" || $yemek['vejetaryen'] == "hayır")&&
                    $yemek['kategori'] == $kategori &&
                    $yemek['protein_orani'] == $protein_orani &&
                    $yemek['lezzet_profili'] == $lezzet_profili &&
                    $yemek['diyet_programi'] == $diyet_programi)
                {
                    $onerilen_yemekler[] = $yemek;
                }
            }
            else if($normal == "hayır")
            {
                $vegan = $_POST['vegan'];
                if($vegan == "evet")
                {
                    if(
                        ($yemek['normal'] == "evet" || $yemek['normal'] == "hayır") &&
                        $yemek['vegan'] == "evet" &&
                        ($yemek['vejetaryen'] == "evet" || $yemek['vejetaryen'] == "hayır")&&
                        $yemek['kategori'] == $kategori &&
                        $yemek['protein_orani'] == $protein_orani &&
                        $yemek['lezzet_profili'] == $lezzet_profili &&
                        $yemek['diyet_programi'] == $diyet_programi)
                    {
                        $onerilen_yemekler[] = $yemek;
                    }
                }
                else
                {
                    $vejetaryen = $_POST['vejetaryen'];
                    if($vejetaryen == "evet")
                    {
                        if(
                            ($yemek['normal'] == "evet" || $yemek['normal'] == "hayır") &&
                            ($yemek['vegan'] == "evet" || $yemek['vegan'] == "hayır") &&
                            $yemek['vejetaryen'] == "evet" &&
                            $yemek['kategori'] == $kategori &&
                            $yemek['protein_orani'] == $protein_orani &&
                            $yemek['lezzet_profili'] == $lezzet_profili &&
                            $yemek['diyet_programi'] == $diyet_programi)
                        {
                            $onerilen_yemekler[] = $yemek;
                        }
                    }
                }
            }
        }
    }

    // Eğer uygun yemek bulunamadıysa uygun mesajı gösteriyoruz
    if (empty($onerilen_yemekler)) {
        echo "Üzgünüz, uygun yemek bulunamadı.";
    } else {
        // Uygun yemekler varsa, tümünü ekrana yazdırıyoruz
        foreach ($onerilen_yemekler as $yemek) {
            echo '<div class="card">';
            echo '<img src="' . $yemek['fotograf'] . '" alt="' . $yemek['yemek_adi'] . '">';
            echo '<div class="card__content">';
            echo '<p class="card__title">' . $yemek['yemek_adi'] . '</p>';
            echo '<p class="card__description">' . $yemek['aciklama'] . '</p>';
            echo '<a href="' . $yemek['link'] . '">Tarif için tıklayın</a>';
            echo '</div>';
            echo '</div>';
        }
    }
    ?>
</div>
</body>
<script>
  document.querySelector('.button').addEventListener('click', function() {
    window.location.href = 'index.html';
  });
</script>

</html>