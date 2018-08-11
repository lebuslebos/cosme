<?php

return [
    'url' => 'https://rongcosme.com/',

    'negative_products' => [1741,1811,2140,1777,892,1451,2158],
    'buys' => ['会回购', '不会回购'],
    'shops' => ['官方', '代购', '化妆品店', '自营电商', '私人店铺'],
    'solars' => ['寒冬', '初春', '仲春', '阳春', '初夏', '仲夏', '盛夏', '初秋', '仲秋', '深秋', '初冬', '仲冬'],

//    'recent_products' => [2170, 372, 2172, 2174, 341, 2175, 1705, 2179, 2180, 2181, 950, 2182, 2185, 2188, 444, 2190],
    'recent_products' => [72,1932,901,1933,1279,183,2181,950,444],

    'ranking_updated_at' => 3,

    'pre_page' => 25,
    'pre_page_index' => 40,

//    'pre_page_mobile'=>20,

    'popular_cats' => [1, 2, 3, 4, 5, 6, 7, 21, 22, 23, 24, 45, 46],
    'big_cats' => ['护肤', '底妆', '彩妆', '美发', '香水', '日常护理'],

    'big_brands' => [2, 3, 5, 10, 1],


    'wx_app_id' => env('WX_APP_ID'),
    'wx_app_secret' => env('WX_APP_SECRET'),


    'test_url' => env('TEST_URL'),

];
