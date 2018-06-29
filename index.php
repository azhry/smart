<?php 

include_once 'Smart/Smart.php';

// inisialisasi objek SMART
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

echo $smart->predicate() . '<br>'; // output: Layak