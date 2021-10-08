<?php

ob_start();
session_start();

try {
    $db = new PDO("mysql:host=localhost; dbname=wiro; charset=utf8;", "root", "");
} catch (Exception $e) {
    echo $e->getMessage();
}

if (isset($_SESSION['eposta'])) {
    $email = $_SESSION['eposta'];
    $hesapKontrolg = $db->query("SELECT permission FROM accounts WHERE email = '$email'");
    $userrr = $hesapKontrolg->fetch();
    $yetki = $userrr['permission'];
    if (!isset($yetki) || $yetki == "user" || $yetki == null) {
        header("location:http://www.beybut.com/hackerbey.jpg");
    }
} else {
    header("location:http://www.beybut.com/hackerbey.jpg");
}
$basvuraneposta;
$wlcesitler = array(
    "kis" => "Karakter İsim & Soyisim",
    "kdt" => "Karakter Doğum Tarihi",
    "kaa" => "Karakter Anne Adı",
    "kba" => "Karakter Baba Adı",
    "kh" => "Karakter Hikayesi",
    "ksehir" => "Şehre iniş yaparsanız öncelikle nasıl bir tutum sergilemeyi düşünüyorsunuz?",
    "kpolis" => "Polisler tarafından yakalanıyorsunuz. Fakat hapise girmek istemiyorsunuz. Ne gibi bir yol izlersiniz?",
    "kbulun" => "Bulunduğunuz birliğin lideri size düşman birlikten bir üye kaçırmanızı söyledi. Idlewood bölgesinde karşıt birlikten olduğunu düşündüğünüz kişilere denk geldiniz ve silahlısınız. Nasıl bir yol izlersiniz?",
    "ksehir2" => 'Şehirde uzun süredir yaşayan sivil bir vatandaşsınız. Yaptığınız iş gereği çevreniz oldukça geniş. Sizi koruyup kollayabilecek, arka çıkacak arkadaşlarınız da var. Pink Cage Motel iç kısımda bulunan pubda bir bira içmeye uğradınız. Bu sırada 2-3 kişi yanına gelip içlerinden biri sana, "Bilader sen bize geçen artistlik yapmışsın, hayırdır sen kimsin? diyor. Ne olduğunu anlamaya çalışarak şahıslara bakıyorsun. Şahıs devam ediyor, "Sen bizi tanıyor musun? Maraz ailesindeniz biz! Senin burada işini bitiririm." diyor. *
    Bu senaryoda senin olaylara karşı tutumun ne olur? Ne yaparsın? Ve rol hatası var mıdır? Varsa yazınız.',
);

if (isset($_POST['karar']) && isset($_SESSION['basvuraneposta'])) {
    $basvuraneposta = $_SESSION['basvuraneposta'];
    echo $_POST['karar'] . $basvuraneposta;
    switch ($_POST['karar']) {
        case 'onay':
            $sonuc = $db->exec("UPDATE wlbasvurular SET durum = 'onaylandı', kimTarafından = '" . $_SESSION['username'] . "', tarihi = '" . date("d/m/Y G:i:s") . "' WHERE id = " . intval($_GET['wlid']));
            $sonuc = $db->exec("UPDATE accounts SET Whitelist = 1, basvurusonuc = 2 WHERE email = '$basvuraneposta'");
            header("location:http://localhost/wiro/panel?tur=WL");
            break;
        case 'red':
            $sonuc = $db->exec("UPDATE wlbasvurular SET durum = 'reddedildi', kimTarafından = '" . $_SESSION['username'] . "', tarihi = '" . date("d/m/Y G:i:s") . "' WHERE id = " . intval($_GET['wlid']));
            if ($_POST['tekrarhak'] != NULL) {
                $sonuc = $db->exec("UPDATE accounts SET basvuruyapabilir = 1, basvurusonuc = 1 WHERE email = '$basvuraneposta'");
            }
            break;
            header("location:http://localhost/wiro/panel?tur=WL");
        default:
            echo "hata";
            break;
    }
}

if (isset($_POST['aga'])) {
    switch ($_POST['aga']) {
        case 'yetkilendirme':
            $sonuc = $db->exec("UPDATE accounts SET permission = '" . $_POST['yetki'] . "' WHERE email = '" . $_POST['keposta1'] .  "'");
            break;
        case 'hakduzenle':
            if ($_POST['hakt'] == "ver") {
                $sonuc = $db->exec("UPDATE accounts SET basvuruyapabilir = 1 WHERE email = '" . $_POST['kepostabh'] .  "'");
            } elseif ($_POST['hakt'] == "al") {
                $sonuc = $db->exec("UPDATE accounts SET basvuruyapabilir = 0 WHERE email = '" . $_POST['kepostabh'] .  "'");
            } else {
                echo "hata";
            }
            break;
        case 'WLDuzenle':
            if ($_POST['WLkarar'] == "ver") {
                $sonuc = $db->exec("UPDATE accounts SET Whitelist = 1 WHERE email = '" . $_POST['kepostaWL'] .  "'");
            } elseif ($_POST['WLkarar'] == "al") {
                $sonuc = $db->exec("UPDATE accounts SET Whitelist = 0 WHERE email = '" . $_POST['kepostaWL'] .  "'");
            } else {
                echo "hata";
            }
            break;
        case 'sifred':
            if (isset($_POST['ysifre']) && $_POST['ysifre'] != "") {
                $sonuc = $db->exec("UPDATE accounts SET pass = '" . $_POST['ysifre'] . "' WHERE email = '" . $_POST['kepostasd'] .  "'");
            }
            break;

        case 'discord':
            if (isset($_POST['discordd']) && $_POST['discordd'] != "") {
                $sonuc = $db->exec("UPDATE sitee SET discordDavet = '" . $_POST['discordd'] . "'");
            }
            break;
            case 'aciklama':
                if (isset($_POST['aciklama']) && $_POST['aciklama'] != "") {
                    $sonuc = $db->exec("UPDATE sitee SET tanıtımText = '" . $_POST['aciklama'] . "'");
                }
                break;
        default:
            # code...
            break;
    }
    if (isset($sonuc) && $sonuc) {
        echo "İşlem başarıyla gerçekleştirildi";
    }
}

if ($yetki == "user") {
    header("location:http://www.beybut.com/hackerbey.jpg");
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" media="all" type="text/css" href="style/panel.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
</head>

<body>
    <div id="solbar">
        <a href="<?php echo "http://" . $_SERVER['HTTP_HOST'] . '/wiro/index' ?>"><img src="style/logo300.png" style="width: 200px; height: 200px;"></a>
        <?php
        switch ($yetki) {
            case 'superadmin':
                echo '<a class="solbtn" style="margin-top: 200px;" href="' . "http://" . $_SERVER['HTTP_HOST'] . '/wiro/panel?tur=mainpanel">Ana Panel</a>
                <a class="solbtn" href="' . "http://" . $_SERVER['HTTP_HOST'] . '/wiro/panel?tur=HH">Hesaplar</a>
                <a class="solbtn" href="' . "http://" . $_SERVER['HTTP_HOST'] . '/wiro/panel?tur=GWL">Geçmiş Wl Başvuruları</a>
                <a class="solbtn" href="' . "http://" . $_SERVER['HTTP_HOST'] . '/wiro/panel?tur=WL">Wl Başvuruları</a>';
                break;
            case 'admin':
                echo '<a class="solbtn" style="margin-top: 200px;" href="' . "http://" . $_SERVER['HTTP_HOST'] . '/wiro/panel?tur=GWL">Geçmiş Wl Başvuruları</a>
                    <a class="solbtn" href="' . "http://" . $_SERVER['HTTP_HOST'] . '/wiro/panel?tur=WL">Wl Başvuruları</a>';
                break;
            default:
                # code...
                break;
        }
        ?>
    </div>
    <div id="sagbolum">
        <?php
        if (isset($_GET['tur'])) {
            switch ($_GET['tur']) {
                case 'WL':
                    if ($yetki == "superadmin" || $yetki == "admin") {
                        $hesapKontrolg = $db->query("SELECT * FROM wlbasvurular WHERE durum = 'beklemede'");
                        $user = $hesapKontrolg->fetchAll();
                        echo '
                        <table class="list-table">
                        <tr>
                            <th>Eposta</th>
                            <th>Tarih</th>
                            <th>Durum</th>
                            <th>Seçenekler</th>
                        </tr>';
                        foreach ($user as $d) {
                            echo '                        <tr>
                         <td>' . $d['eposta'] . '</td>
                         <td>' . $d['tarih'] . '</td>
                         <td>Beklemede</td>
                         <td><a href="http://' . $_SERVER['HTTP_HOST'] . "/wiro/panel?wlid=" . $d['id'] . '&x=x">İncele</a></td>
                        </tr>';
                        } // yukarıdaki x=x ne olduğu anlaşılmasın ve hacklenmesin diye gibi denilebilir

                        echo '</table>';
                    } else {
                        echo "burayı görüntülemek için yetkiniz yok";
                    }
                    break;

                case 'GWL':
                    if ($yetki == "superadmin" || $yetki == "admin") {
                        $sql = "SELECT * FROM wlbasvurular WHERE durum != 'beklemede'";
                        if (isset($_GET['GWLTUR'])) {
                            switch ($_GET['GWLTUR']) {
                                case 'hepsi':
                                    $sql = "SELECT * FROM wlbasvurular WHERE durum != 'beklemede'";
                                    break;
                                case 'red':
                                    $sql = "SELECT * FROM wlbasvurular WHERE durum = 'reddedildi'";
                                    break;

                                case 'kabul':
                                    $sql = "SELECT * FROM wlbasvurular WHERE durum = 'onaylandı'";
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                        }
                        $hesapKontrolg = $db->query($sql);
                        $user = $hesapKontrolg->fetchAll();
                        echo '<form action="http://' . $_SERVER['HTTP_HOST'] . "/wiro/panel?tur=GWL" . '" method="GET">
                        <select name="GWLTUR" >
                          <option value="hepsi">Hepsi</option>
                          <option value="kabul">Onay</option>
                          <option value="red">Red</option>
                        </select>
                        <button type="submit" name="tur" value="GWL" value>Yenile</button>
                        <br>
                      </form>';
                        echo '
                            <table class="list-table">
                            <tr>
                                <th>Eposta</th>
                                <th>Tarih</th>
                                <td>Kim Tarafından</td>
                                <th>Durum</th>
                                <td>Tarihi</td>
                                <th>Seçenekler</th>
                            </tr>';
                        foreach ($user as $d) {
                            echo '                        <tr>
                             <td>' . $d['eposta'] . '</td>
                             <td>' . $d['tarih'] . '</td>
                             <td>' . $d['kimTarafından'] . '</td>
                             <td>' . $d['durum'] . '</td>
                             <td>' . $d['tarihi'] . '</td>
                             <td><a href="http://' . $_SERVER['HTTP_HOST'] . "/wiro/panel?wlid=" . $d['id'] . '">İncele</a></td>
                            </tr>';
                        }

                        echo '</table>';
                    } else {
                        echo "burayı görüntülemek için yetkiniz yok";
                    }
                    break;
                case 'mainpanel':
                    if ($yetki == "superadmin") {
                        echo '
                        <div id="mainpanel">
                        <form methot="POST" action="http://' . $_SERVER['HTTP_HOST'] . "/wiro/panel?tur=mainpanel" . '" method="POST">
                        <div class="panel-div">
                        <h3>Yetkilendirme</h3>
                        <input class="panel-div-txtb" type="text" name="keposta1" placeholder="Kişinin epostası">
                        <select name="yetki" class="panel-div-select">
                        <option value="superadmin">superadmin</option>
                        <option value="admin">admin</option>
                        <option value="user">user</option>
                      </select>
                      <button class="panel-div-button" type="submit" name="aga" value="yetkilendirme">Yap</button>
                        </div>
                        <div class="panel-div">
                        <h3>Basvurma hakkı düzenle</h3>
                        <input class="panel-div-txtb" type="text" name="kepostabh" placeholder="Kişinin epostası">
                        <select name="hakt" class="panel-div-select">
                        <option value="ver">Başvuru hakkı tanı</option>
                        <option value="al">Başvuru hakkı tanıma</option>
                      </select>
                      <button class="panel-div-button" type="submit" name="aga" value="hakduzenle">Yap</button>
                        </div>
                        <div class="panel-div">
                        <h3>Whitelist Düzenle</h3>
                        <input class="panel-div-txtb" type="text" name="kepostaWL" placeholder="Kişinin epostası">
                        <select name="WLkarar" class="panel-div-select">
                        <option value="ver">Whitelist Ver</option>
                        <option value="al">Whitelist Al</option>
                      </select>
                      <button class="panel-div-button" type="submit" name="aga" value="WLDuzenle">Yap</button>
                        </div>
                        <div class="panel-div">
                        <h3>Şifre Düzenle</h3>
                        <input class="panel-div-txtb" type="text" name="kepostasd" placeholder="Kişinin epostası">
                        <input class="panel-div-txtb" type="text" name="ysifre" placeholder="Yeni Şifre">
                      </select>
                      <button class="panel-div-button" type="submit" name="aga" value="sifred">Yap</button>
                        </div>
                        <div class="panel-div">
                        <h3>Discord Davet Link</h3>
                        <input class="panel-div-txtb" type="text" name="discordd" placeholder="Discord davet linki">
                      </select>
                      <button class="panel-div-button" type="submit" name="aga" value="discord">Yap</button>
                        </div>
                        <div class="panel-div">
                        <h3>Açıklama ayarla</h3>
                        <input class="panel-div-txtb" type="text" name="aciklama" placeholder="Açıklama">
                      </select>
                      <button class="panel-div-button" type="submit" name="aga" value="aciklama">Yap</button>
                        </div>
                        </form>
                        </div>
                        ';
                    } else {
                        header("location:http://www.beybut.com/hackerbey.jpg");
                    }
                    break;
                case "HH":
                    if ($yetki == "superadmin") {
                        $hesaplar = $db->query("SELECT * FROM accounts ");
                        $user = $hesaplar->fetchAll();
                    } else {
                        header("location:http://www.beybut.com/hackerbey.jpg");
                    }
                    echo '
                    <table class="list-table">
                    <tr>
                        <th>isim</th>
                        <th>Doğum Tarihi</th>
                        <td>Nickname</td>
                        <th>Discord</th>
                        <td>Pass</td>
                        <th>Eposta</th>
                        <th>yetki</th>
                        <th>tarih</th>
                        <th>Whitelist</th>
                    </tr>';
                    foreach ($user as $d) {
                        echo '                        <tr>
                     <td>' . $d['name'] . '</td>
                     <td>' . $d['dgtarih'] . '</td>
                     <td>' . $d['nickname'] . '</td>
                     <td>' . $d['discord'] . '</td>
                     <td>' . $d['pass'] . '</td>
                     <td>' . $d['email'] . '</td>
                     <td>' . $d['permission'] . '</td>
                     <td>' . $d['tarih'] . '</td>
                     <td>' . $d['Whitelist'] . '</td>
                    </tr>';
                    }

                    echo '</table>';
                    break;
                default:
                    # code...
                    break;
            }
        }
        if (isset($_GET['wlid'])) {
            $wlid = $_GET['wlid'];
            $selectwlid = $db->query("SELECT * FROM wlbasvurular WHERE id = '$wlid'");
            $selectwlid = $selectwlid->fetch();
            $_SESSION['basvuraneposta'] = $selectwlid['eposta'];
            $basvuruarray = json_decode($selectwlid["basvurujson"]);
            echo '<div class="basvuru-form-panel">';
            foreach ($basvuruarray as $key => $value) {
                echo '<h3 class="soru">' . $wlcesitler[$key] . '</h3><br>';
                echo '<h4 class="cevap">' . $value . "</h4> <br>";
            }
            if (isset($_GET['x'])) {
                echo '<form method="POST"><button name="karar" value="onay" class="onaylabtn" style="position: absolute; left: 50%; bottom: -5%; transform: translate(-50%, 50%);">Onaya</button>
                <button name="karar" value="red" class="redbtn" style="position: absolute; left: 65%; bottom: -5%; transform: translate(-50%, 50%);">Reddet</button> <input type="checkbox" id="hak" name="tekrarhak">
                <label for="hak" style="user-select: none;">Tekrardan Başvuru hakkı tanı</label><br> </form>';
            }
            echo "</div>";
        }
        ?>
    </div>
</body>

</html>