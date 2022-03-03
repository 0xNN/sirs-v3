<?php

/** Created by Muhammad Sendi */

return [
  'name' => 'Bridging - RSUD Siti Fatimah',
  'subname' => 'RS Online V3',
  'version' => "3",
  'api' => [
    'base_url' => 'http://202.70.136.24:3020/api/',
    'url_sirs' => 'https://sirs.kemkes.go.id/fo/cov19/',
    'base_url2' => 'http://sirs.kemkes.go.id/fo/index.php/',
  ],
  // 'get_url' => [
  //   'kewarganegaraan',
  //   'asalpasien',
  //   'jenispasien',
  //   'kelompokgejala',
  //   'pekerjaan',
  //   'statuspasien',
  //   'statusrawat',
  //   'terapi',
  //   'statuskeluar',
  //   'kasuskematian',
  //   'penyebabkematianlangsung',
  //   'alatoksigen',
  //   'dosisvaksin',
  //   'jenisvaksin',
  //   'provinsi',
  //   'kabkota',
  //   'kecamatan',
  //   'kelurahan'
  // ],
  'token' => '',
  'login' => [
    'kode_rs' => '1671347',
    'password' => 'S!rs2020!!'
  ],
  'login2' => [
    'X-rs-id' => '1671347',
    'X-pass' => '112233',
  ],
  'menu' => [
    'laporan' => 'Covid 19',
    'covid' => 'Data Covid 19',
    'ruangan' => 'Ruangan & TT',
    'sdm' => 'SDM',
    'alkes' => 'Alkes',
    'pcrnakes' => 'PCR Nakes',
    'nakesterinfeksi' => 'Nakes Terinfeksi',
    'oksigenasi' => 'Oksigenasi'
  ],
  'jamlapor' => date('H:i:s', mktime(10, 00, 00)),
  'code_covid' => [
    'B34.2',
    'Z03.8',
    // 'R50.9', 
    // 'J12'
  ],
  'room' => [
    '247','175','176','177','178','179','180','181','182','183','184','185','186','187'
  ]
];