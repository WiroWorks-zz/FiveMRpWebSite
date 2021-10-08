<?php
ob_start();
session_start();

if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool
    {
        return '' === $needle || false !== strpos($haystack, $needle);
    }
}


function hekirKontrol(string $str)
{
    if (!str_contains($str, "'") && !str_contains($str, '"')) {
        return true;
    } else {
        header("location:http://www.beybut.com/hackerbey.jpg");
    }
}

try {
    $db = new PDO("mysql:host=localhost; dbname=wiro; charset=utf8;", "root", "");
} catch (Exception $e) {
    echo $e->getMessage();
}

$siteeris = $db->query("SELECT * FROM sitee WHERE id = 1");
$site = $siteeris->fetch();

if (isset($_GET['c'])) {
    $_SESSION['username'] = null;
    $_SESSION['eposta'] = null;
}

if (isset($_POST['islem'])) {
    switch ($_POST['islem']) {
        case 'kayit':
            if (hekirKontrol($_POST['isim']) && hekirKontrol($_POST['dogum']) && hekirKontrol($_POST['nick']) && hekirKontrol($_POST['discord']) && hekirKontrol($_POST['pass']) && hekirKontrol($_POST['passtekrar']) && hekirKontrol($_POST['eposta'])) {
                if ($_POST['pass'] == $_POST['passtekrar']) {
                    $name = $_POST['isim'];
                    $dogum = $_POST['dogum'];
                    $nick = $_POST['nick'];
                    $discord = $_POST['discord'];
                    $pass = $_POST['pass'];
                    $eposta = $_POST['eposta'];
                    $date = date("d/m/Y G:i:s");
                    $hesapKontrol = $db->prepare('SELECT * FROM accounts WHERE email = ?');
                    $hesapKontrol->execute([
                        $eposta
                    ]);
                    if ($hesapKontrol->rowCount() > 0) {
                        echo "başarısız";
                    } else {
                        $kayitSorgu = $db->prepare('INSERT INTO accounts SET name = ?, dgtarih = ?, nickname = ?, discord = ?, pass = ?, email = ?, tarih = ?');
                        $ekle = $kayitSorgu->execute([
                            $name, $dogum, $nick, $discord, $pass, $eposta, $date
                        ]);
                        if ($ekle) {
                            $_SESSION['username'] = $nick;
                            $_SESSION['eposta'] = $eposta;
                        }
                    }
                }
            }
            break;
        case 'giris':
            if (hekirKontrol($_POST['geposta']) && hekirKontrol($_POST['gsifre'])) {
                $geposta = $_POST['geposta'];
                $gsifre = $_POST['gsifre'];
                $hesapKontrolg = $db->query("SELECT * FROM accounts WHERE email = '$geposta' AND pass = '$gsifre'");
                $user = $hesapKontrolg->fetch();
                $username = $hesapKontrolg->fetchColumn();
                if ($hesapKontrolg->rowCount() > 0) {
                    $_SESSION['username'] = $user['name'];
                    $_SESSION['eposta'] = $_POST['geposta'];
                }
            }
            break;
        default:
            # code...
            break;
    }
}

if (isset($_SESSION['eposta'])) {
    $email = $_SESSION['eposta'];
    $hesapKontrolg = $db->query("SELECT * FROM accounts WHERE email = '$email'");
    $userrr = $hesapKontrolg->fetch();
    $yetki = $userrr['permission'];
    $basvurusonuc = $userrr['basvurusonuc'];
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" media="all" type="text/css" href="style/style.css">
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
            }            else {
                echo '<li class="menu-li"><a style="text-decoration: none;" href="http://' . $_SERVER['HTTP_HOST'] . '/wiro/index?c=c"' . '>Çıkış yap</a></li>';
            }
            ?>
        </ul>
        <div class="discordd">
            <a class="wiro-btn" style="text-decoration: none;" target="_blank" href="<?php echo $site['discordDavet'] ?>">Discord <img width="40px" style="margin-left: 30px;" src="style/discordblack.png"></a>
        </div>
    </div>
    <?php
    if (isset($basvurusonuc)) {
        switch ($basvurusonuc) {
            case -1:
                break;
            case 0:
                echo "Başvurunuz elimize ulaştı Başvurunuz sonuçlandığında buradan görebileceksiniz";
                break;
            case 1:
                echo "basvurnuz malesef reddedilmiştir fakat üzülmeyin yeni bir WL başvuru hakkı kazanmış olabilirsiniz";
                break;
            case 2:
                echo "Başvurunuz kabul edildi discorda bekleniyorsunuz";
                break;
            default:
                # code...
                break;
        }
    }
    ?>
    <div id="giris" class="giriskayit" style="display: none;">
        <form id="kayitform" method="POST">
            <h3>Giriş yap</h3>
            <p class="ustit">E-posta</p>
            <input type="text" class="wiro-txt" name="geposta">
            <p class="ustit">Şifre</p>
            <input type="password" class="wiro-txt" name="gsifre">
            <button type="submit" class="onaylabtn" name="islem" value="giris" style="left: 50%; top: 100%; transform: translate(-50%, -50%);"><b style="color: black;">Giriş Yap</b></button>
        </form>
    </div>
    <div id="kayit" class="giriskayit" style="display: none;">
        <form method="POST" id="kayitform">
            <h3>Kayıt ol</h3>
            <p class="ustit">Adınız</p>
            <input type="text" class="wiro-txt" name="isim" id="isimk" required>
            <p class="ustit">Doğum Tarihiniz</p>
            <input type="text" class="wiro-txt" name="dogum" id="dogumk" required>
            <p class="ustit">Kullanıcı Adınız</p>
            <input type="text" class="wiro-txt" name="nick" id="nickk" required>
            <p class="ustit">Discord Kullanıcı Adınız</p>
            <input type="text" class="wiro-txt" name="discord" id="discordk" required>
            <p class="ustit">Şifre</p>
            <input type="password" class="wiro-txt" name="pass" id="passk" required>
            <p class="ustit">Şifre Tekrardan Girin</p>
            <input type="password" class="wiro-txt" name="passtekrar" id="passtekrark" required>
            <p class="ustit">E-posta</p>
            <input type="text" class="wiro-txt" name="eposta" id="epostak" required>
            <button type="submit" id="kayitbtnn" class="onaylabtn" name="islem" value="kayit" style="left: 50%; top: 100%; transform: translate(-50%, -50%);"><b style="color: black;">Kayıt Ol</b></button>
        </form>
    </div>
    <div id="bilgi">
        <img src="style/logo300.png" style="width: 300px;">
        <div id="bilgisag">
            <?php echo $site['tanıtımText'] ?>
        </div>
    </div>
    <div id="footer">
    </div>
    <script type="text/javascript">
        $(".menu-li").click(function(e) {
            HideAll();
            if ($(this).attr("class").replace(/ .*/, '') == "bilgi") {
                $(".discordd").show();
            } else if ($(this).attr("class").replace(/ .*/, '') == "menu-li") {
                $("#bilgi").show();
                $(".discordd").show();
                return
            }
            $("#" + $(this).attr("class").replace(/ .*/, '')).show();
        });

        function HideAll() {
            $("#bilgi").hide();
            $("#kayit").hide();
            $("#giris").hide();
            $(".discordd").hide();
        }

        /* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
        function myFunction() {
            document.getElementById("myDropdown").classList.toggle("show");
        }

        // Close the dropdown menu if the user clicks outside of it
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