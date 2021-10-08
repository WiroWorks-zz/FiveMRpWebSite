<?php
ob_start();
session_start();

$serverdosya = "/wiro";

if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool
    {
        return '' === $needle || false !== strpos($haystack, $needle);
    }
}


function hekirKontrol(string $str)
{
    if (!str_contains($str, "'") || !str_contains($str, '"')) {
        return true;
    } else {
        echo "<h1>veri alanlarında ' " . '"' . "gibi karakterler kullanamazsınız</h1>";
        return true;
    }
}

try {
    $db = new PDO("mysql:host=localhost; dbname=wiro; charset=utf8;", "root", "");
} catch (Exception $e) {
    echo $e->getMessage();
}

if (isset($_SESSION['username']) && isset($_SESSION['eposta'])) {
    $email = $_SESSION['eposta'];
    $hesapKontrolg = $db->query("SELECT * FROM accounts WHERE email = '$email'");
    $userrr = $hesapKontrolg->fetch();
    $yetki = $userrr['permission'];
    $basvurusonuc = $userrr['basvurusonuc'];
} else {
    echo "<h1>İLK ÖNCE GİRİŞ YAPMALISINIZ</h1>";
    echo "<h2>ANA SAYFAYA YÖNLENDİRİLİYORSUNUZ</h2>";
    header("Refresh: 5; url=" . "http://" . $_SERVER['HTTP_HOST'] . "/wiro/");
}

$go = true;

if (isset($_POST['basvurutarz'])) {
    $nick = $_SESSION['username'];
    $email = $_SESSION['eposta'];
    switch ($_POST['basvurutarz']) {
        case 'WL':
            $hesapKontrolg = $db->query("SELECT basvuruyapabilir FROM accounts WHERE email = '$email' AND nickname = '$nick'");
            $user = $hesapKontrolg->fetch();
            if (intval($user['basvuruyapabilir']) == 1) {
                $array = array(
                    "kis" => $_POST['kis'],
                    "kdt" => $_POST['kdt'],
                    "kaa" => $_POST['kaa'],
                    "kba" => $_POST['kba'],
                    "kh" => $_POST['kh'],
                    "ksehir" => $_POST['ksehir'],
                    "kpolis" => $_POST['kpolis'],
                    "kbulun" => $_POST['kbulun'],
                    "ksehir2" => $_POST['ksehir2']
                );
                $go = true;
                foreach ($array as $d) {
                    if (!hekirKontrol($d)) {
                        $go = false;
                        break;
                    }
                }
                if ($go) {
                    $array = json_encode($array);
                    $kayitSorgu = $db->prepare('INSERT INTO wlbasvurular SET eposta = ?, basvurujson = ?, tarih = ?, durum = ?');
                    $ekle = $kayitSorgu->execute([
                        $_SESSION['eposta'], $array, date("d/m/Y G:i:s"), "beklemede"
                    ]);
                    if ($ekle) {
                        $sonuc = $db->exec("UPDATE accounts SET basvuruyapabilir = 0, basvurusonuc = 0 WHERE email = '$email' AND nickname = '$nick'");
                        if ($sonuc) {
                            echo "Başarıyla Whitelist başvurunuzu gerçekleştirdiniz <br> Anasayfaya yönlendiriliyorsunuz";
                            header("Refresh: 5; url=" . "http://" . $_SERVER['HTTP_HOST'] . "/wiro/");
                        }
                    }
                } else {
                }
            } else {
                echo "basvuru yapmak için hakkınız yok";
                header("Refresh: 5; url=" . "http://" . $_SERVER['HTTP_HOST'] . "/wiro/");
            }
            break;
        default:
            # code...
            break;
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style/style.css" media="screen, projection">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
</head>

<body>
    <div id="middle">
    <ul id="top">
            <li><a href="<?php echo "http://" . $_SERVER['HTTP_HOST'] . "/wiro/index" ?>"><img class="menu-logo" src="style/logo.png"></a></li>
            <li class="bilgi menu-li"><a style="text-decoration: none;" href="<?php echo "http://" . $_SERVER['HTTP_HOST'] . "/wiro/index"  ?>">Anasayfa</a></li>
            <li class="menu-li">Tanıtım</li>
            <li class="menu-li"><a style="text-decoration: none;" name="WL" href="<?php echo "http://" . $_SERVER['HTTP_HOST'] . "/wiro/basvuru?basvuru=WL"  ?>">WL Başvuru</a></li>
            <?php
            if (isset($yetki)) {
                if ($yetki != "user") {
                    echo '<li class="menu-li"><a style="text-decoration: none;" href="http://' . $_SERVER['HTTP_HOST'] . '/wiro/panel"' . '>Panel</a></li>';
                }
            }
            ?>
            <?php
            if (!isset($_SESSION['username'])) {
                echo '<li class="giris menu-li">Giriş yap</li>';
                echo '<li class="kayit menu-li">Kayıt ol</li>';
            }
            else {
                echo '<li class="menu-li"><a style="text-decoration: none;" href="http://' . $_SERVER['HTTP_HOST'] . '/wiro/index?c=c"' . '>Çıkış yap</a></li>';
            }
            ?>
        </ul>
    </div>
    <div class="basvuru-main">
        <form class="basvuru-form" method="POST">
            <?php
            if (isset($_SESSION['username'])) {
                switch ($_GET['basvuru']) {
                    case 'WL':
                        echo
                        '<div class="basvuru-div-50">
                        <p class="basvuru-p">karakter isim & Soyisim</p>
                        <input autocomplete="off" required type="text" name="kis" class="basvuru-txt">
                    </div>
                    <div class="basvuru-div-50">
                        <p class="basvuru-p">karakterin doğum tarihi</p>
                        <input autocomplete="off" required type="text" name="kdt" class="basvuru-txt">
                    </div>
                    <div class="basvuru-div-50">
                        <p class="basvuru-p">Karakterinizin Anne Adı</p>
                        <input autocomplete="off" required type="text" name="kaa" class="basvuru-txt">
                    </div>
                    <div class="basvuru-div-50">
                        <p class="basvuru-p">Karakterinizin Baba Adı</p>
                        <input autocomplete="off" required type="text" name="kba" class="basvuru-txt">
                    </div>
                    <div class="basvuru-div-100">
                        <p class="basvuru-p">Karakter Hikayesi</p>
                        <textarea autocomplete="off" required type="text" name="kh" class="basvuru-txt basvuru-txt-100"></textarea>
                    </div>
                    <div class="basvuru-div-100">
                        <p class="basvuru-p">Şehre iniş yaparsanız öncelikle nasıl bir tutum sergilemeyi düşünüyorsunuz?</p>
                        <textarea autocomplete="off" required type="text" name="ksehir" class="basvuru-txt basvuru-txt-100"></textarea>
                    </div>
                    <div class="basvuru-div-100">
                        <p class="basvuru-p">Polisler tarafından yakalanıyorsunuz. Fakat hapise girmek istemiyorsunuz. Ne gibi bir yol izlersiniz?</p>
                        <textarea autocomplete="off" required type="text" name="kpolis" class="basvuru-txt basvuru-txt-100"></textarea>
                    </div>
                    <div class="basvuru-div-100">
                        <p class="basvuru-p">Bulunduğunuz birliğin lideri size düşman birlikten bir üye kaçırmanızı söyledi. Idlewood bölgesinde karşıt birlikten olduğunu düşündüğünüz kişilere denk geldiniz ve silahlısınız. Nasıl bir yol izlersiniz?</p>
                        <textarea autocomplete="off" required type="text" name="kbulun" class="basvuru-txt basvuru-txt-100"></textarea>
                    </div>
                    <div class="basvuru-div-100">
                        <p class="basvuru-p">Şehirde uzun süredir yaşayan sivil bir vatandaşsınız. Yaptığınız iş gereği çevreniz oldukça geniş. Sizi koruyup kollayabilecek, arka çıkacak arkadaşlarınız da var. Pink Cage Motel iç kısımda bulunan pubda bir bira içmeye uğradınız. Bu sırada 2-3 kişi yanına gelip içlerinden biri sana, "Bilader sen bize geçen artistlik yapmışsın, hayırdır sen kimsin?" diyor. Ne olduğunu anlamaya çalışarak şahıslara bakıyorsun. Şahıs devam ediyor, "Sen bizi tanıyor musun? Maraz ailesindeniz biz! Senin burada işini bitiririm." diyor. *
                            Bu senaryoda senin olaylara karşı tutumun ne olur? Ne yaparsın? Ve rol hatası var mıdır? Varsa yazınız.</p>
                        <textarea autocomplete="off" required type="text" name="ksehir2" class="basvuru-txt basvuru-txt-100"></textarea>
                    </div>
                    <button type="submit" class="onaylabtn" name="basvurutarz" value="WL" style="position: absolute; left: 50%; bottom: -5%; transform: translate(-50%, 50%);">Gonder</button>';
                        break;
                        case 'EMS':
                            echo
                            '<div class="basvuru-div-50">
                            <p class="basvuru-p">karakter isim & Soyisim</p>
                            <input autocomplete="off" required type="text" name="kis" class="basvuru-txt">
                        </div>
                        <div class="basvuru-div-50">
                            <p class="basvuru-p">karakterin doğum tarihi</p>
                            <input autocomplete="off" required type="text" name="kdt" class="basvuru-txt">
                        </div>
                        <div class="basvuru-div-50">
                            <p class="basvuru-p">Karakterinizin Anne Adı</p>
                            <input autocomplete="off" required type="text" name="kaa" class="basvuru-txt">
                        </div>
                        <div class="basvuru-div-50">
                            <p class="basvuru-p">Karakterinizin Baba Adı</p>
                            <input autocomplete="off" required type="text" name="kba" class="basvuru-txt">
                        </div>
                        <div class="basvuru-div-100">
                            <p class="basvuru-p">Karakter Hikayesi</p>
                            <textarea autocomplete="off" required type="text" name="kh" class="basvuru-txt basvuru-txt-100"></textarea>
                        </div>
                        <div class="basvuru-div-100">
                            <p class="basvuru-p">Soru 1</p>
                            <textarea autocomplete="off" required type="text" name="soru1" class="basvuru-txt basvuru-txt-100"></textarea>
                        </div>
                        <div class="basvuru-div-100">
                            <p class="basvuru-p">Soru 2</p>
                            <textarea autocomplete="off" required type="text" name="soru2" class="basvuru-txt basvuru-txt-100"></textarea>
                        </div>
                        <div class="basvuru-div-100">
                            <p class="basvuru-p">Soru 3</p>
                            <textarea autocomplete="off" required type="text" name="soru3" class="basvuru-txt basvuru-txt-100"></textarea>
                        </div>
                        <div class="basvuru-div-100">
                            <p class="basvuru-p">Soru 4</p>
                            <textarea autocomplete="off" required type="text" name="soru4" class="basvuru-txt basvuru-txt-100"></textarea>
                        </div>
                        <button type="submit" class="onaylabtn" name="basvurutarz" value="EMS" style="position: absolute; left: 50%; bottom: -5%; transform: translate(-50%, 50%);">Gonder</button>';
                            break;
                    default:
                        echo 'bu kısım şuanlık yapım aşamasında';
                        break;
                }
            }
            ?>
        </form>
    </div>
    <div id="footer">
    </div>
    <script type="text/javascript">
        function HideAll() {
            $("#bilgi").hide();
            $("#kayit").hide();
            $("#giris").hide();
            $(".discordd").hide();
        }

        function myFunction() {
            document.getElementById("myDropdown").classList.toggle("show");
        }

        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                var i;
                for (i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>

</html>