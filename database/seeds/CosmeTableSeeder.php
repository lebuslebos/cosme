<?php

use App\Brand;
use App\Price;
use App\Product;
use App\Review;
use Illuminate\Database\Seeder;

class CosmeTableSeeder extends Seeder
{
    public function create_brands(array $new_brands)
    {
        foreach ($new_brands as $new_brand) {
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
    }

    public function create_products(array $new_products)
    {
        foreach ($new_products as $new_product) {
            Product::create([
                'brand_id' => $new_product[0],
                'cat_id' => $new_product[1],
                'name' => $new_product[2],
                'common_name' => $new_product[3],
                'nick_name' => $new_product[4]
            ]);
        }
    }

    public function create_prices(array $new_prices)
    {
        $records = ['product_id', 'volume', 'price'];
        $prices = [];
        foreach ($new_prices as $data) {
            $prices[] = array_combine($records, $data);
        }
        DB::table('prices')->insert($prices);
    }

    public function run()
    {
        //新增品牌-->[品牌名，品牌英文名，品牌类似名，国家id，国家名，官方网站]
        $c = [
            1 => '中国', 2 => '美国', 3 => '日本', 4 => '英国', 5 => '法国', 6 => '意大利',
            7 => '德国', 8 => '澳大利亚', 9 => '俄罗斯', 10 => '韩国', 11 => '加拿大', 12 => '瑞士',
            13 => '中国台湾', 14 => '匈牙利', 15 => '瑞典', 16 => '荷兰', 17 => '以色列', 18 => '新西兰', 19 => '中国香港', 20 => '西班牙', 21 => '泰国',22=>'捷克'
        ];
        $new_brands = [
            ['黛玛蔻', 'Dermacol', '', 22 , $c[22], ''],
        ];
        $this->create_brands($new_brands);




        //新增商品-->[品牌id，分类id，名字，英文名，昵称]
        $new_products=[
            [370,23,'强力遮瑕膏','',''],
        ];
        $this->create_products($new_products);




        //新增价格-->[商品id，容量，价格]
        $new_prices=[
            [2181,'',380],
        ];
        $this->create_prices($new_prices);



        //新建点评-->
        //  [用户id，商品id，分类id，品牌id，评分，回购，购入场所，点评内容，点评图片]
        //  [12,800,4,61,6,0,2,'好用',['https://cache-cdn.rongcosme.com/reviews/111.jpeg','']]
        $new_reviews = [
            [16,700,5,50,7,0,0,'太好闻了，很滋润，干皮可以闭眼入。化妆再也不卡粉，初抗老首选。',['https://cache-cdn.rongcosme.com/reviews/bGmpWx6SnInPJZExv0lkFXKGsETbEs5loyoXPN8h.jpeg']],
        ];
        $this->create_reviews($new_reviews);
    }

    public function create_reviews(array $new_reviews)
    {
        foreach ($new_reviews as $new_review) {

            DB::table('products')->where('id', $new_review[1])->update(['has_login_review' => true]);

            Review::create(['user_id' => $new_review[0], 'product_id' => $new_review[1], 'cat_id' => $new_review[2], 'brand_id' => $new_review[3], 'rate' => $new_review[4], 'buy' => $new_review[5], 'shop' => $new_review[6], 'body' => $new_review[7], 'imgs' => json_encode($new_review[8]),]);

            $p_ids = Cache::get('p-ids', []);
            $p_ids[] = $new_review[1];
            Cache::forever('p-ids', $p_ids);
        }

        Cache::forget('reviews');
    }
}
