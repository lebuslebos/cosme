<?php

use App\Brand;
use App\Price;
use App\Product;
use App\Review;
use Illuminate\Database\Seeder;

class CosmeTableSeeder extends Seeder
{
    public function run()
    {
        //新增品牌-->[品牌名，品牌英文名，品牌类似名，国家id，国家名，官方网站]
        $c = [
            1 => '中国', 2 => '美国', 3 => '日本', 4 => '英国', 5 => '法国', 6 => '意大利',
            7 => '德国', 8 => '澳大利亚', 9 => '俄罗斯', 10 => '韩国', 11 => '加拿大', 12 => '瑞士',
            13 => '中国台湾', 14 => '匈牙利', 15 => '瑞典', 16 => '荷兰', 17 => '以色列', 18 => '新西兰', 19 => '中国香港', 20 => '西班牙', 21 => '泰国', 22 => '捷克'
        ];
        $new_brands = [
            ['Graphico', '', '', 3, $c[3], ''],
            ['美露露', 'belulu', '', 3, $c[3], ''],
            ['metabolic', '', '', 3, $c[3], ''],
            ['Herb健康本铺', 'Herb-Kenko', '', 3, $c[3], ''],
            ['新谷酵素', 'shinyakoso', '', 3, $c[3], ''],
            ['Swisse', '', '', 8, $c[8], ''],
            ['山本汉方', 'yamamoto kanpo', '', 3, $c[3], ''],
            ['美达施', 'Meta-Mucil', '', 2, $c[2], ''],
            ['PILLBOX', '', '', 3, $c[3], ''],
            ['丝蓓缇', 'SVELTY', '', 3, $c[3], ''],
            ['小林制药', 'Kobayashi Pharmaceutical', '', 3, $c[3], ''],
            ['健安喜', 'GNC', '', 2, $c[2], 'www.gnc.com.cn'],
            ['菲拉思德', 'FatBlaster', '', 8, $c[8], ''],
            ['爱妆堂', '', '', 3, $c[3], ''],
        ];
        foreach ($new_brands as $new_brand) {
            Brand::create(['name' => $new_brand[0], 'common_name' => $new_brand[1], 'similar_name' => $new_brand[2], 'country_id' => $new_brand[3], 'country' => $new_brand[4], 'official_website' => $new_brand[5]]);
        }
//        Cache::forget('country-brands');


        //新增商品-->[品牌id，分类id，名字，英文名，昵称]
        $new_products = [
            [13,42,'全新五色眼影盘','5 Couleurs EyeShadow',''],
            [52,105,'极速减肥烧脂完美纤体丸W','Perfect Slim W',''],
            [52,105,'纤体热控消脂片','Calorie Limit',''],
            [406,105,'白芸豆减肥酵素','','宛如白吃丸'],
            [406,8,'假面蒸汽眼罩','',''],
            [407,106,'Cavi style纤体仪','',''],
            [408,9,'酵素X酵母','',''],
            [408,9,'酵素X酵母-增强版','',''],
            [45,105,'魔力消脂因子','',''],
            [409,105,'植物酵素金装增强版','Dokkan Abura Gold',''],
            [410,105,'睡眠瘦酵素-普通版','',''],
            [410,105,'睡眠瘦酵素-增强版','',''],
            [410,105,'睡眠瘦酵素-美丽装','',''],
            [411,9,'胶原蛋白液体口服液','',''],
            [411,9,'葡萄籽片','Grape Seed葡萄籽精华',''],
            [411,9,'高浓度蔓越莓胶囊','',''],
            [411,9,'胶原蛋白片','',''],
            [411,1,'微胶束小黄瓜卸妆液','',''],
            [411,9,'睡眠片','',''],
            [411,9,'叶绿素片','',''],
            [411,9,'叶绿素口服液','',''],
            [412,9,'大麦若叶100%青汁粉末','',''],
            [412,9,'大麦若叶100%青汁颗粒','',''],
            [412,105,'脂流茶','',''],
            [412,9,'大麦若叶乳酸菌青汁','',''],
            [413,105,'无糖膳食纤维素粉','',''],
            [414,105,'onaka膳食营养素','',''],
            [415,105,'pakkun糖质分解酵母','生成酵素',''],
            [416,103,'眼病预防洗眼液','',''],
            [416,102,'内裤清洗剂','',''],
            [416,101,'液体创可贴','',''],
            [416,101,'清凉喷雾','',''],
            [416,10,'祛斑霜','',''],
            [416,10,'暗疮痘痘贴','',''],
            [416,10,'暗疮痘痘膏','',''],
            [416,101,'安美露','',''],
            [416,105,'腹部排油丸','',''],
            [417,105,'左旋肉碱片-运动加强型','',''],
            [418,105,'代餐奶昔','',''],
            [419,9,'日本256生酵素胶囊','',''],
            [22,9,'红莓酵素','',''],
            [22,9,'葡萄石榴蓝莓酵素','',''],
            [22,9,'樱花蜜大麦若叶青汁','',''],
            [22,105,'MEGA BURN纤体丸','',''],
            [22,105,'MEGA CUT热控粉','',''],
        ];
        foreach ($new_products as $new_product) {
            Product::create(['brand_id' => $new_product[0], 'cat_id' => $new_product[1], 'name' => $new_product[2], 'common_name' => $new_product[3], 'nick_name' => $new_product[4]]);
        }


        //新增价格-->[商品id，容量，价格]
        $new_prices = [
            [2414,'',600],
            [2451,'60片',119],
        ];
        $records = ['product_id', 'volume', 'price'];
        $prices = [];
        foreach ($new_prices as $data) {
            $prices[] = array_combine($records, $data);
        }
        DB::table('prices')->insert($prices);





        //新建点评-->
        //  [用户id，商品id，分类id，品牌id，评分，回购，购入场所，点评内容，点评图片]
        //  [12,800,4,61,6,0,2,'好用',['https://cache-cdn.rongcosme.com/reviews/111.jpeg','']]
        $new_reviews = [
            [16, 700, 5, 50, 7, 0, 0, '太好闻了，很滋润，干皮可以闭眼入。化妆再也不卡粉，初抗老首选。', ['https://cache-cdn.rongcosme.com/reviews/bGmpWx6SnInPJZExv0lkFXKGsETbEs5loyoXPN8h.jpeg']],
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
