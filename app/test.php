<?php
Cache::forget('reviews');

for($i=0;$i<2148;$i++){Cache::tags('products-'.$i.'-reviews')->flush();}
