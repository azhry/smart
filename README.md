# SMART
Simple Multi Attribute Rating Technique (SMART) method for developing Decision Support System

### Konfigurasi Metode SMART
Konfigurasi metode ini dapat dilihat pada `Smart/config.json`.
Terdapat dua `key` yang harus dikonfigurasi, yaitu `criteria` dan `predicate`.

### Contoh kasus:<br>
#### Kriteria Penghasilan Orang Tua
Bobot: 20%.

Penghasilan | Nilai
------------- | -------------
&#8804; 1.500.000 | 100
1.500.001 - 2.000.000 | 80
2.000.001 - 2.500.000 | 60
&#8805; 2.500.001 | 40

#### Kriteria Tanggungan Orang Tua
Bobot: 10%.

Tanggungan | Nilai
------------- | -------------
&#8805; 4 | 100
3 | 80
2 | 60
1 | 40

#### Predikat

Range Nilai | Predikat
------------- | -------------
80 - 100 | Layak
0 - 79 | Tidak Layak

Sehingga `config.json`-nya akan terlihat seperti ini
```json
{
  "criteria": [
    {
      "label": "Penghasilan Orang Tua",
      "name": "penghasilan_orang_tua",
      "weight": 20,
      "type": "range",
      "rules": [
        {
          "max": 1500000,
          "value": 100
        },
        {
          "max": 2000000,
          "min": 1500001,
          "value": 80
        },
        {
          "max": 2500000,
          "min": 2000001,
          "value": 60
        },
        {
          "min": 2500001,
          "value": 40
        }
      ]
    },
    {
      "label": "Tanggungan Orang Tua",
      "name": "tanggungan_orang_tua",
      "weight": 10,
      "type": "range",
      "rules": [
        {
          "min": 4,
          "value": 100
        },
        {
          "max": 3,
          "min": 3,
          "value": 80
        },
        {
          "max": 2,
          "min": 2,
          "value": 60
        },
        {
          "max": 1,
          "min": 1,
          "value": 40
        }
      ]
    }
  ],
  "predicates": [
    {
      "label": "Layak",
      "max": 100,
      "min": 80
    },
    {
      "label": "Tidak Layak",
      "max": 79,
      "min": 0
    }
  ]
}
```

### Contoh Penggunaan
Lihat di index.php.
```php
<?php
include_once 'Smart/Smart.php';

// instansiasi objek SMART
$smart = new Smart();

// beri data yang akan dihitung
$smart->fit(
  [
    'ipk' => 3.50,
    'prestasi_non_akademik' => 'Internasional',
    'penghasilan_orang_tua' => 1300000,
    'tanggungan_orang_tua' => 2
  ]
);

echo $smart->result() . '<br>'; // output: 88
echo $smart->predicate() . '<br>'; // output: Layak
```

### Contoh Penggunaan dengan Framework Codeigniter
Copy-Paste folder `Smart` ke dalam `application/libraries/`.
```php
<?php

class ContohController extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
  }
  
  public function index()
  {
    // instansiasi objek SMART
    $this->load->library("Smart/smart");

    // beri data yang akan dihitung
    $this->smart->fit(
      [
        'ipk' => 3.50,
        'prestasi_non_akademik' => 'Internasional',
        'penghasilan_orang_tua' => 1300000,
        'tanggungan_orang_tua' => 2
      ]
    );

    echo $this->smart->result() . '<br>'; // output: 88
    echo $this->smart->predicate() . '<br>'; // output: Layak
  }
}
```

Azhary Arliansyah &copy; 2018
