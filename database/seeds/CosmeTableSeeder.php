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

    public function create_reviews(array $new_reviews)
    {
        foreach ($new_reviews as $new_review) {

            DB::table('products')->where('id', $new_review[1])->update(['has_login_review' => true]);

            Review::create([
                'user_id' => $new_review[0],
                'product_id' => $new_review[1],
                'cat_id' => $new_review[2],
                'brand_id' => $new_review[3],
                'rate' => $new_review[4],
                'buy' => $new_review[5],
                'shop' => $new_review[6],
                'body' => $new_review[7],
                'imgs' => json_encode($new_review[8]),
            ]);

            $p_ids = Cache::get('p-ids', []);
            $p_ids[] = $new_review[1];
            Cache::forever('p-ids', $p_ids);
        }

        Cache::forget('reviews');
    }

    public function run()
    {
        //新增品牌-->[品牌名，品牌英文名，品牌类似名，国家id，国家名，官方网站]
        /*$c = [
            1 => '中国', 2 => '美国', 3 => '日本', 4 => '英国', 5 => '法国', 6 => '意大利',
            7 => '德国', 8 => '澳大利亚', 9 => '俄罗斯', 10 => '韩国', 11 => '加拿大', 12 => '瑞士',
            13 => '中国台湾', 14 => '匈牙利', 15 => '瑞典', 16 => '荷兰', 17 => '以色列', 18 => '新西兰', 19 => '中国香港', 20 => '西班牙', 21 => '泰国',
        ];
        $new_brands = [
            ['参天制药', 'Santen', '', 3, $c[3], 'www.santen-china.cn'],
        ];
        $this->create_brands($new_brands);*/


        //新增商品-->[品牌id，分类id，名字，英文名，昵称]
        /*$new_products=[
            [348,103,'FX NEO眼药水','',''],
        ];
        $this->create_products($new_products);*/


        //新增价格-->[商品id，容量，价格]
        /*$new_prices=[
            [2135,'',53],
        ];
        $this->create_prices($new_prices);*/

        //新建点评-->
        //  [用户id，商品id，分类id，品牌id，评分，回购，购入场所，点评内容，点评图片]
        $new_reviews = [
//          [12,800,4,61,6,0,2,'好用',['https://cache-cdn.rongcosme.com/reviews/111.jpeg','']]
            [12,330,45,19,7,0,0,'官网购入的 圆管46号钟情，带有刻字服务，超有纪念意义，记得提前预定哦。46号完全不挑肤色，是一款元气蜜桃粉橘色，滋润、显白又肥肠少女~~ 上嘴还有治愈泡泡糖香味，简直就是约会斩男必备！爱哭~',['https://cache-cdn.rongcosme.com/reviews/E5AA66909E450C9285CAA30C2D3C484B.png','https://cache-cdn.rongcosme.com/reviews/IMG_2805.JPG']],
        ];
        $this->create_reviews($new_reviews);


    }
}
