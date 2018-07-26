<?php

use App\Brand;
use App\Price;
use App\Product;
use Illuminate\Database\Seeder;

class CosmeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*//新增品牌-->放入new_brands
        $c = [
            1 => '中国', 2 => '美国', 3 => '日本', 4 => '英国', 5 => '法国', 6 => '意大利',
            7 => '德国', 8 => '澳大利亚', 9 => '俄罗斯', 10 => '韩国', 11 => '加拿大', 12 => '瑞士',
            13 => '中国台湾', 14 => '匈牙利', 15 => '瑞典', 16 => '荷兰', 17 => '以色列', 18 => '新西兰', 19 => '中国香港', 20 => '西班牙', 21 => '泰国',
        ];
        $new_brands = [
            ['参天制药', 'Santen', '', 3, $c[3], 'www.santen-china.cn'],
            ['贝印', 'KAI', '', 3, $c[3], 'www.kai-china.com'],
            ['柳屋', 'Yanagiya', '', 3, $c[3], ''],
            ['得鲜', 'the Saem', '', 10, $c[10], '']
        ];
        foreach ($new_brands as $new_brand){
            Brand::create([
                'name' => $new_brand[0],
                'common_name' => $new_brand[1],
                'similar_name' => $new_brand[2],
                'country_id' => $new_brand[3],
                'country' => $new_brand[4],
                'official_website' => $new_brand[5]
            ]);
        }
        Cache::forget('country-brands');

        //新增商品
        $new_products=[
            [348,103,'FX NEO眼药水','',''],
            [348,103,'Beauty Eye 玫瑰眼药水','',''],
            [348,103,'PC对策眼药水','',''],
            [348,103,'FX V Plus眼药水','',''],
            [349,48,'小型睫毛夹','',''],
            [349,48,'修眉刀','',''],
            [349,48,'小号刮眉刀','',''],
            [349,101,'4层刃女用脱毛刀','',''],
            [350,61,'发根营养液','Hair Tonic',''],
            [351,23,'丝滑遮瑕液/棒','Cover Proction Tip Concealer',''],
            [351,45,'按压哑光唇膏','Eco Soul Kiss Button Lips Matte',''],
            [351,45,'按压水润唇膏','Eco Soul KISS Button Lips',''],
            [351,45,'双头气垫口红','Saemmul Kok Kok Cushion Tint',''],
            [351,1,'绿茶卸妆水','Healing Tea Garden Green Tea Cleansing Water',''],
            [351,47,'自然完美指甲油','Perfect Nail Wear',''],
            [351,7,'99%济州岛清新芦荟胶','Jeju Fresh Aloe Soothing Gel',''],
            [351,1,'茶树卸妆水','Healing Tea Garden Tea Tree Cleansing Water',''],
            [351,45,'双头气垫蜡笔唇膏','','']
        ];
        foreach($new_products as $new_product){
            Product::create([
                'brand_id'=>$new_product[0],
                'cat_id'=>$new_product[1],
                'name'=>$new_product[2],
                'common_name'=>$new_product[3],
                'nick_name'=>$new_product[4]
            ]);
        }*/

        //新增价格
        $records = ['product_id', 'volume', 'price'];
        $new_prices=[
            [2135,'',53],
            [2136,'5只',22],
            [2137,'L型3把',32],
            [2137,'T型3把',32],
            [2138,'3把',36],
            [2140,'',32],
            [2141,'',55],
            [2142,'',55],
            [2144,'300ml',42],
            [2146,'300ml',35],
            [2147,'300ml',42],
            [2148,'',49],
        ];
        $prices = [];
        foreach ($new_prices as $data) {
            $prices[] = array_combine($records, $data);
        }
        DB::table('prices')->insert($prices);


    }
}
