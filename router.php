<?php
  #url get değeri .htaccess dosyası ile gizlendi
  if(isset($_GET["url"]) && $_GET["url"] != null && $_GET["url"] != ""){
    $url = explode("/",$_GET["url"]);
    $page = trim($url[0]);
    switch ($page) {
      case 'uyelik':
        view('uyelik');
        break;
      case 'kayit-basarili':
        view("kayit_basarili");
        break;
      case 'hesap-dogrula':
        view("hesap_dogrula");
        break;
      case 'siparislerim':
        view('siparislerim');
        break;
      case 'yorumlarim':
        view('yorumlarim');
        break;
      case 'kategori':
        view('kategori');
        break;
      case 'urun':
        view('urun');
        break;
      case 'sayfa':
          view('sayfa');
          break;
      case 'hesap-dogrulama-durum':
          view('hesap_dogrulama_durum');
        break;
      case 'yorum-durum':
          view('yorum_durum');
        break;

      case 'profil':
          if(count($url) > 1){
            if ($url[1]=="yorumlarim") view('yorumlarim');
            else if ($url[1]=="siparislerim") view('siparislerim');
            else if ($url[1]=="siparis-detay") view('siparis_detay');
            else if ($url[1]=="favorilerim") view('favorilerim');
            else if ($url[1]=="adreslerim") view('adreslerim');
            else if ($url[1]=="adres-duzenle") view('adres_duzenle');
            else view("profil");
          }
          else view("profil");
        break;
      case 'sepet':
        view('sepet');
        break;
      case 'odeme':
        view('odeme');
        break;
      case 'teslimat-adresi-sec':
        view('teslimat_adresi_sec');
        break;
      case 'fatura-adresi-sec':
        view('fatura_adresi_sec');
        break;
      case 'siparis-tamamla':
        view('siparis_tamamla');
        break;
      case 'siparis-basarili':
        view('siparis_basarili');
        break;
      case 'siparis-basarisiz':
        view('siparis_basarisiz');
        break;
      case 'yonetim':
          if(count($url) > 1){
            if ($url[1]=="urun-islemleri") adminView('urun_islemleri');
            elseif ($url[1]=="urun-ekle") adminView('urun_ekle');
            elseif ($url[1]=="urun-sil") adminView('urun_sil');
            elseif ($url[1]=="urun-duzenle") adminView('urun_duzenle');
            elseif ($url[1]=="urun-sec") adminView('urun_sec');
            elseif ($url[1]=="urun-yorumlari") adminView('urun_yorumlari');
            elseif ($url[1]=="kategori-islemleri") adminView('kategori_islemleri');
            elseif ($url[1]=="kategori-sec") adminView('kategori_sec');
            elseif ($url[1]=="giris") adminView('giris');
            elseif ($url[1]=="cikis") adminView('cikis');
            elseif ($url[1]=="ozellik-sec") adminView('ozellik_sec');
            elseif ($url[1]=="deger-gir") adminView('deger_gir');
            elseif ($url[1]=="filtre-ekle") adminView('filtre_ekle');
            elseif ($url[1]=="urun-bilgisi-ekle") adminView('urun_bilgisi_ekle');
            elseif ($url[1]=="musteri-listesi") adminView('musteri_listesi');
            elseif ($url[1]=="musteri-detay") adminView('musteri_detay');
            elseif ($url[1]=="genel-ayarlar") adminView('genel_ayarlar');
            elseif ($url[1]=="musteri-engelle") adminView('musteri_engelle');
            elseif ($url[1]=="musteri-engel-kaldir") adminView('musteri_engel_kaldir');
            elseif ($url[1]=="kategori-filtre-ekle") adminView('kategori_filtre_ekle');
            elseif ($url[1]=="filtre-deger-ekle") adminView('filtre_deger_ekle');
            elseif ($url[1]=="xml-islemleri") adminView('xml_islemleri');
            elseif ($url[1]=="siparis-detay") adminView('siparis_detay');
            elseif ($url[1]=="siparisler") adminView('siparisler');
            elseif ($url[1]=="anlik-sepetler") adminView('anlik_sepetler');
            elseif ($url[1]=="banka-bilgileri") adminView('banka_bilgileri');
            elseif ($url[1]=="banka-bilgisi-ekle") adminView('banka_bilgisi_ekle');
            elseif ($url[1]=="destek-mesaj") adminView('destek_mesaj');
            elseif ($url[1]=="sayfa-islemleri") adminView('sayfa_islemleri');
            elseif ($url[1]=="sayfa-duzenle") adminView('sayfa_duzenle');
            elseif ($url[1]=="sayfa-ekle") adminView('sayfa_ekle');
            else adminView('index');
          }
          else adminView('index');
          break;
      case 'sign-out':
        view('sign_out');
        break;
      default:
        view("index");
        break;
    }
  }
  else{
    view("index");
  }

?>
