
{{--全部分类的排行榜--目前用在首页--}}
{{--红榜--}}
<ranking :ranking-type="true"
         :cats="{{$all_cats}}"
         :rand-cat="{{$red_cat}}"
         :products="{{$red_products}}"
></ranking>
{{--黑榜--}}
<ranking class="pt-5"
         :ranking-type="false"
         :cats="{{$all_cats}}"
         :rand-cat="{{$black_cat}}"
         :products="{{$black_products}}"
></ranking>
