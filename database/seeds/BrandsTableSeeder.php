<?php

use Illuminate\Database\Seeder;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $c = [
            1 => '中国', 2 => '美国', 3 => '日本', 4 => '英国', 5 => '法国', 6 => '意大利',
            7 => '德国', 8 => '澳大利亚', 9 => '俄罗斯', 10 => '韩国', 11 => '加拿大', 12 => '瑞士',
            13 => '中国台湾', 14 => '匈牙利', 15 => '瑞典', 16 => '荷兰', 17 => '以色列', 18 => '新西兰', 19 => '中国香港', 20 => '西班牙', 21 => '泰国',
        ];
        $records = ['name', 'common_name', 'similar_name', 'country_id', 'country', 'official_website',];
        $datas = [
            ['肌肤之钥', 'Clé de Peau Beauté', 'CPB', 3, $c[3], 'www.cledepeau-beaute.com.cn'],
            ['香奈儿', 'CHANEL', '香奶奶', 5, $c[5], 'www.chanel.cn'],
            ['乔治·阿玛尼', 'GIORGIO ARMANI', '', 6, $c[6], 'www.giorgioarmanibeauty.cn'],
            ['纪梵希', 'GIVENCHY', '', 5, $c[5], 'www.givenchybeauty.cn'],
            ['兰蔻', 'LANCOME', '', 5, $c[5], 'www.lancome.com.cn'],
            ['理肤泉', 'LA ROCHE-POSAY', '', 5, $c[5], 'www.larocheposay.com.cn'],
            ['娇兰', 'Guerlain', '', 5, $c[5], 'www.guerlain.com.cn'],
            ['伊丽莎白雅顿', 'Elizabeth Arden', '', 2, $c[2], 'china.elizabetharden.com'],
            ['丝芙兰', 'SEPHORA', '', 5, $c[5], 'www.sephora.cn'],
            ['美宝莲纽约', 'MAYBELLINE NEW YORK', '美宝莲', 2, $c[2], 'www.maybellinechina.com'],
            ['雅诗兰黛', 'Estee Lauder', '', 2, $c[2], 'www.esteelauder.com.cn'],
            ['科颜氏', 'KIEHL\'S', '', 2, $c[2], 'www.kiehls.com.cn/'],
            ['迪奥', 'Dior', '', 5, $c[5], 'www.dior.cn'],
            ['巴黎欧莱雅', 'L\'Oréal Paris', '', 5, $c[5], 'www.lorealparis.com.cn'],
            ['魅可', 'M·A·C', '', 11, $c[11], 'www.maccosmetics.com.cn'],
            ['倩碧', 'CLINIQUE', '', 2, $c[2], 'www.clinique.com.cn '],
            ['ZOEVA', '', '', 7, $c[7], ''],
            ['纳斯彩妆', 'NARS', '', 2, $c[2], 'www.narscosmetics.com.cn'],
            ['圣罗兰', 'Yves Saint Laurent', 'ysl,杨树林', 5, $c[5], 'www.yslbeautycn.com'],
            ['汤姆福特', 'TOM FORD', '', 2, $c[2], ''],
            ['资生堂', 'SHISEIDO', '', 3, $c[3], 'www.shiseido.com.cn'],
            ['宝丽', 'POLA', '', 3, $c[3], 'www.pola-china.com'],
            ['黛珂', 'Cosme Decorte', '', 3, $c[3], 'www.cn-cosmedecorte.com'],
            ['悦诗风吟', 'Innisfree', '', 10, $c[10], 'www.innisfree.cn'],
            ['安娜苏', 'ANNA SUI', '', 2, $c[2], ''],
            ['茵芙莎', 'IPSA', '', 3, $c[3], 'www.ipsa.com.cn'],
            ['伊蒂之屋', 'Etude House', '爱丽小屋', 10, $c[10], 'www.etude.cn'],
            ['植村秀', 'Shu Uemura', '', 3, $c[3], 'www.shuuemura.com.cn'],
            ['兰芝', 'Laneige', '', 10, $c[10], 'www.laneige.com/cn'],
            ['美之匙', 'SK-Ⅱ', 'sk2', 3, $c[3], 'www.skii.com.cn'],
            ['百雀羚', 'PECHOIN', '', 1, $c[1], 'www.pechoin.com'],
            ['自然堂', 'Chando', '', 1, $c[1], 'www.chcedo.com'],
            ['相宜本草', 'Inoherb', '', 1, $c[1], 'www.inoherb.com'],
            ['玛丽黛佳', 'Marie Dalgar', '', 1, $c[1], 'www.mariedalgar.com'],
            ['蜜丝佛陀', 'Max Factor', '', 2, $c[2], 'www.maxfactor.com.cn'],
            ['贝玲妃', 'Benefit', '', 2, $c[2], 'www.benefitcosmetics.com.cn'],
            ['芭比波朗', 'Bobbi Brown', '', 2, $c[2], 'www.bobbibrown.com.cn'],
            ['露华浓', 'REVLON', '', 2, $c[2], ''],
            ['芙丽芳丝', 'freeplus', '', 3, $c[3], 'www.freeplus.cn'],
            ['佰草集', 'Herborist', '', 1, $c[1], 'www.herborist.com.cn'],
            ['悦木之源', 'ORIGINS', '', 2, $c[2], 'www.origins.com.cn'],
            ['海蓝之谜', 'LA MER', '', 2, $c[2], 'www.lamer.com.cn'],
            ['雪花秀', 'Sulwhasoo', '', 10, $c[10], 'www.sulwhasoo.com/cn'],
            ['雪肌精', 'SEKKISEI', '', 3, $c[3], 'sekkisei.kose.com.cn'],
            ['蝶翠诗', 'DHC', '', 3, $c[3], 'www.dhc.net.cn'],
            ['碧欧泉', 'BIOTHERM', '', 5, $c[5], 'www.biotherm.com.cn'],
            ['珂润', 'Curel', '', 3, $c[3], 'web.kao.com/cn/curel'],
            ['姬芮', 'Za', '', 3, $c[3], 'www.za-cosmetics.com.cn'],
            ['菲诗小铺', 'The Face Shop', 'tfs', 10, $c[10], 'www.thefaceshop.com.cn'],
            ['娇韵诗', 'CLARINS', '', 5, $c[5], 'www.clarins.com.cn'],
            ['馥蕾诗', 'fresh', '', 2, $c[2], 'www.fresh.com'],
            ['芳珂', 'FANCL', '无添加', 3, $c[3], 'www.fancl.com.cn'],
            ['雅漾', 'Avène', '', 5, $c[5], 'www.eau-thermale-avene.cn'],
            ['希思黎', 'sisley', '', 5, $c[5], 'www.sisley.com.cn'],
            ['香缇卡', 'Chantecaille', '', 5, $c[5], 'www.ctbeaute.com'],
            ['雅莉格丝', 'Elegance', '', 5, $c[5], ''],
            ['日月晶采', 'LUNASOL', '', 3, $c[3], 'www.lunasol.cn'],
            ['SUQQU', '', '', 3, $c[3], 'www.suqqu.com'],
            ['莱珀妮', 'La prairie', '', 12, $c[12], 'www.laprairie.com.cn'],
            ['傲丽', 'Covermark', '', 3, $c[3], 'www.covermarkchina.com'],
            ['奥碧虹', 'ALBION', '奥尔滨，奥比虹', 3, $c[3], 'http://www.albion-cosmetics.cn/cn'],
            ['THREE ', '', '', 3, $c[3], ''],
            ['银座', 'THE GINZA', '', 3, $c[3], ''],
            ['梦妆', 'Mamonde', '', 10, $c[10], 'www.mamonde.com'],
            ['欧舒丹 ', 'L\'occitane', '', 5, $c[5], 'www.loccitane.cn'],
            ['茱莉蔻 ', 'Jurlique', '', 8, $c[8], 'www.jurlique.com.cn'],
            ['欧树', 'NUXE', '', 5, $c[5], ''],
            ['欧缇丽 ', 'Caudalie', '', 5, $c[5], 'cn.caudalie.com'],
            ['赫莲娜 ', 'Helena Rubinstein', '', 5, $c[5], 'www.helenarubinstein.cn'],
            ['宝拉珍选', 'Paula‘s Choice', '', 2, $c[2], ''],
            ['修丽可', 'SkinCeuticals', '', 2, $c[2], ''],
            ['安热沙', 'ANESSA', '安耐晒', 3, $c[3], 'www.anessa.cn'],
            ['怡丽丝尔', 'ELIXIR', '', 3, $c[3], 'www.elixir.com.cn'],
            ['苏菲娜', 'SOFINA', '', 3, $c[3], 'web.sofina.com/cn'],
            ['后', 'Whoo', '', 10, $c[10], 'www.whoo.com.cn'],
            ['羽西', 'YUE SAI', '', 1, $c[1], 'www.yuesai.com'],
            ['井田', 'CANMAKE', '砍妹', 3, $c[3], ''],
            ['Charlotte Tilbury', '', 'CT', 4, $c[4], 'www.charlottetilburychina.com'],
            ['萝拉蜜思', 'Laura Mercier', '罗拉玛斯亚，罗拉', 2, $c[2], ''],
            ['凯朵', 'KATE', '', 3, $c[3], 'www.kate-kanebo.net'],
            ['玫珂菲', 'Make Up For Ever', '', 2, $c[2], 'www.makeupforever.cn'],
            ['RMK', '', '', 3, $c[3], ''],
            ['玉兰油', 'OLAY', '', 2, $c[2], 'www.olay.com.cn'],
            ['妙巴黎', 'BOURJOIS', '', 5, $c[5], ''],
            ['奇士美', 'KISS ME', '', 3, $c[3], 'www.isehan.cn'],
            ['城野医生', 'Dr.Ci:Labo', '', 3, $c[3], ''],
            ['欧珀莱', 'AUPRES', '', 1, $c[1], 'www.aupres.com.cn'],
            ['雅呵雅丝睿', 'AQUA SPRINA', '雅呵雅', 3, $c[3], 'www.aqua-sprina.cn'],
            ['水之印', 'AQUA LABEL', '', 3, $c[3], ''],
            ['英国AA网', 'AA Skincare', 'AA网', 4, $c[4], ''],
            ['帕尔玛之水', 'ACQUA DI PARMA', '', 6, $c[6], ''],
            ['艾凡达', 'AVEDA', '', 2, $c[2], ''],
            ['ALLIE ', '', '', 3, $c[3], ''],
            ['伊索', 'Aesop', '', 8, $c[8], ''],
            ['艾诗缇', 'ASTALIFT', '', 3, $c[3], ''],
            ['水之密语', 'AQUAIR', '', 1, $c[1], 'www.aquair-china.com'],
            ['小蜜蜂', 'BURT\'S BEES', '', 2, $c[2], ''],
            ['碧柔', 'Biore', '', 3, $c[3], 'web.kao.com/cn/biore/'],
            ['我的美丽日记', 'My Beauty Diary', '', 13, $c[13], ''],
            ['宝格丽', 'BVLGARI', '', 6, $c[6], 'www.bulgari.cn/zh-cn'],
            ['博柏利', 'Burberry', '巴宝莉，巴宝丽', 4, $c[4], 'cn.burberry.com'],
            ['博朗', 'BRAUN', '', 7, $c[7], 'www.braun.com.cn'],
            ['博倩叶', 'Boscia', '', 3, $c[3], ''],
            ['博姿', 'Boots', '', 4, $c[4], ''],
            ['Beauty Blender', '', 'BB', 2, $c[2], ''],
            ['蜂花', 'Bee&Flower', '', 1, $c[1], 'www.beeflower-cn.com'],
            ['贝德玛', 'BIODERMA', '', 5, $c[5], 'www.bioderma.net.cn'],
            ['泰利', 'By Terry', '', 5, $c[5], ''],
            ['贝佳斯', 'Borghese', '', 6, $c[6], ''],
            ['卡尔文·克雷恩', 'Calvin Klein', 'CK', 2, $c[2], 'www.calvinklein.cn'],
            ['克里斯提·鲁布托', 'Christian Louboutin', 'CL', 5, $c[5], 'asia.christianlouboutin.com/cn_sc'],
            ['可伶可俐', 'Clean & Clear', '', 2, $c[2], 'www.cleanandclear.com.cn'],
            ['水宝宝', 'Coppertone', '', 2, $c[2], 'www.coppertone.net.cn'],
            ['卡姿兰', 'Carslan', '', 1, $c[1], 'www.carslan.com.cn'],
            ['丝塔芙', 'Cetaphil', '', 5, $c[5], 'www.cetaphil.com.cn'],
            ['小蜜缇', 'Carmex', '', 2, $c[2], ''],
            ['大卫杜夫', 'DAVIDOFF', '', 12, $c[12], ''],
            ['李医生', 'DOCTOR LI', '', 1, $c[1], 'www.doctorli.cn'],
            ['德美乐嘉', 'Dermalogica', '', 2, $c[2], ''],
            ['杜嘉班纳', 'Dolce＆Gabbana', 'DG', 6, $c[6], ''],
            ['森田', 'Dr.Morita', '', 13, $c[13], ''],
            ['蒂佳婷', 'Dr.Jart+', '', 10, $c[10], 'www.drjartchina.cn'],
            ['大宝', 'Dabao', '', 1, $c[1], 'www.dabao.com'],
            ['朵梵', 'Darphin', '迪梵', 5, $c[5], ''],
            ['依云', 'Evian', '', 5, $c[5], ''],
            ['伊欧诗', 'eos', '', 2, $c[2], ''],
            ['Eminence', '', '', 14, $c[14], ''],
            ['艾杜莎', 'Ettusais', '', 3, $c[3], ''],
            ['优色林', 'Eucerin', '', 7, $c[7], ''],
            ['宠爱之名', 'FOR BELOVED ONE', '', 13, $c[13], 'www.forbelovedonechina.com'],
            ['菲拉格慕', 'Salvatore Ferragamo', '', 6, $c[6], 'www.ferragamo.cn'],
            ['斐珞尔', 'FOREO', '', 15, $c[15], ''],
            ['凡士林', 'Vaseline', '', 2, $c[2], 'www.vaseline.cn'],
            ['馥绿德雅', 'RENE FURTERER', '', 5, $c[5], 'www.renefurterer.cn'],
            ['高丝', 'KOSE', '', 3, $c[3], 'www.kose.com.cn'],
            ['爱马仕', 'HERMES', '', 5, $c[5], 'www.hermes.cn'],
            ['曼秀雷敦', 'Mentholatum', '', 2, $c[2], ''],
            ['沙漏', 'Hourglass', '', 2, $c[2], ''],
            ['赫妍', 'HERA', '赫拉', 10, $c[10], 'www.hera.com/cn/zh'],
            ['Twany', '', '', 3, $c[3], ''],
            ['印象之美', 'Impress', '', 3, $c[3], 'www.kanebo.com/cn/brands/impress'],
            ['三宅一生', 'ISSEY MIYAKE', '', 5, $c[5], 'www.isseymiyake.com/cn'],
            ['艾诺碧', 'IOPE', '亦博', 10, $c[10], 'www.iope.com/cn/zh'],
            ['伊思', 'IT\'S SKIN', '', 10, $c[10], 'www.itsskincn.com'],
            ['吉尔·斯图亚特', 'Jill Stuart', '', 3, $c[3], 'cn.jillstuart-beauty.com/zh-jp'],
            ['祖玛珑', 'JO MALONE LONDON', '祖马龙', 4, $c[4], 'www.jomalone.com.cn'],
            ['歌剧魅影', 'KRYOLAN', '', 7, $c[7], ''],
            ['肌美精', 'Hadabisei', '', 3, $c[3], ''],
            ['艾丝', 'est', '嫒色', 3, $c[3], ''],
            ['巴黎卡诗', 'KÉRASTASE', '卡诗', 5, $c[5], ''],
            ['康如', 'KLORANE', '', 5, $c[5], 'www.klorane.cn'],
            ['花王', 'Kao', '', 3, $c[3], 'www.kao.com/cn/'],
            ['可悠然', 'Kuyura', '', 3, $c[3], 'www.aquair-china.com/others/pro2'],
            ['欧莱雅专业美发', 'L\'oreal Professionnel', '', 5, $c[5], 'www.lorealprofessionnel.cn'],
            ['拉杜丽', 'LADURÉE', '', 5, $c[5], ''],
            ['无添加主义', 'HABA', '', 3, $c[3], 'www.haba.com.cn'],
            ['岚舒', 'LUSH', '露诗', 4, $c[4], ''],
            ['李施德林', 'LISTERINE', '', 2, $c[2], 'www.listerine.com.cn'],
            ['谜尚', 'MISSHA', '', 10, $c[10], ''],
            ['毛戈平', 'MGPIN', '', 1, $c[1], 'www.maogepingbeauty.com'],
            ['MARIO BADESCU', '', 'MB', 2, $c[2], ''],
            ['马克贾可比', 'MARC JACOBS', '马克·雅可布', 2, $c[2], ''],
            ['玛贝拉', 'MARYEPIL', '', 6, $c[6], ''],
            ['美帕', 'MedSPA', '', 5, $c[5], ''],
            ['美即', 'MG Mask', '', 1, $c[1], ''],
            ['膜法世家', 'Mask Family 1908', '', 1, $c[1], 'www.mfsj1908.com'],
            ['恋爱魔镜', 'Majolica Majorca', '', 3, $c[3], ''],
            ['露得清', 'Neutrogena', '', 2, $c[2], 'www.neutrogena.com.cn'],
            ['妮维雅', 'NIVEA', '', 7, $c[7], 'www.nivea.com.cn'],
            ['自然共和国', 'NATURE REPUBLIC', '', 10, $c[10], 'www.naturerepublic.cn'],
            ['娜丽丝优物语', 'Naris Up', '', 3, $c[3], ''],
            ['NYX', '', '', 2, $c[2], ''],
            ['奥蜜思', 'Orbis', '', 3, $c[3], 'www.orbis.com.cn'],
            ['娥佩兰', 'OPERA', '', 3, $c[3], ''],
            ['欧蕙', 'O HUI', '', 10, $c[10], 'www.ohui.com.cn'],
            ['皓乐齿', 'ora2', '', 3, $c[3], 'cn.ora2.com'],
            ['一叶子', 'One leaf', '', 1, $c[1], 'www.oneleafchina.com'],
            ['欧乐-B', 'Oral-B', '', 2, $c[2], 'www.oralb.com.cn'],
            ['欧诗漫', 'OSM', '', 1, $c[1], 'www.osmun.com.cn'],
            ['欧派', 'O・P・I', '', 2, $c[2], 'www.opi.cn'],
            ['欧乐菊', 'PAUL&JOE', '', 5, $c[5], ''],
            ['旁氏', 'POND\'S', '', 2, $c[2], ''],
            ['松下', 'Panasonic', '', 3, $c[3], 'panasonic.cn'],
            ['珀莱雅', 'PROYA', '', 1, $c[1], 'www.proya.com'],
            ['彼得罗夫', 'Peter Thomas Roth', '', 2, $c[2], ''],
            ['泊美', 'Pure&Mild', '', 1, $c[1], 'www.puremild.com'],
            ['飞利浦', 'Philips', '', 16, $c[16], 'www.philips.com.cn'],
            ['自然哲理', 'Philosophy', '', 2, $c[2], ''],
            ['巧迪尚惠', 'Qdsuh', '', 1, $c[1], 'www.qiaodi.com'],
            ['芮谜', 'RIMMEL', '', 4, $c[4], ''],
            ['香邂格蕾', 'Roger&Gallet', '', 5, $c[5], ''],
            ['吕', 'RYO', '', 10, $c[10], ''],
            ['施华蔻', 'Schwarzkopf', '', 7, $c[7], ''],
            ['心机彩妆', 'MAQUILLAGE', '', 3, $c[3], 'www.shiseido.com.cn'],
            ['Sabon', '', '', 17, $c[17], ''],
            ['苏秘37˚', 'Su:m 37º', 'sum37度', 10, $c[10], 'www.sum37.cn'],
            ['莎娜', 'SANA', '', 3, $c[3], 'www.sanachina.com.cn'],
            ['水之璨', 'Suisai', '', 3, $c[3], ''],
            ['夏依', 'Summer\'s Eve', '', 2, $c[2], ''],
            ['思亲肤', 'SKINFOOD', '', 10, $c[10], 'www.theskinfoodchina.cn'],
            ['诗狄娜', 'Stila Cosmetics', '', 2, $c[2], ''],
            ['双妹', 'VIVE', '', 1, $c[1], 'www.shanghaivive.com.cn'],
            ['魅力彩盒', 'Smashbox', '', 2, $c[2], ''],
            ['珊珂', 'SENKA', '洗颜专科，SENGANSENKA', 3, $c[3], 'www.senka-china.com'],
            ['桑诺丝', 'SOLONE', '', 13, $c[13], 'www.solone.com.cn'],
            ['丁家宜', 'TJOY', '', 1, $c[1], ''],
            ['丝蓓绮', 'TSUBAKI', '', 3, $c[3], 'www.tsubakichina.com'],
            ['T3', '', '', 2, $c[2], ''],
            ['趣乐活', 'trilogy', '', 18, $c[18], ''],
            ['Tarte', '', '', 2, $c[2], ''],
            ['津尔氏', 'Thayers', '金缕梅', 2, $c[2], 'www.thayers.com.cn'],
            ['星期四农庄', 'Thursday Plantation', '', 8, $c[8], 'www.thursdayplantation.net.cn'],
            ['Too Faced', '', '', 2, $c[2], ''],
            ['托尼魅力', 'TONY MOLY', '魔法森林', 10, $c[10], ''],
            ['悠莱', 'URARA', '', 1, $c[1], 'www.urara.cn'],
            ['依泉', 'URIAGE', '', 5, $c[5], ''],
            ['吾诺', 'Uno', '', 3, $c[3], ''],
            ['佑天兰', 'utena', '', 3, $c[3], 'www.utena.com.cn'],
            ['衰败城市', 'URBAN DECAY', '', 2, $c[2], ''],
            ['沙宣', 'VS', '', 2, $c[2], 'www.vs.com.cn/zh-cn'],
            ['范思哲', 'VERSACE', '', 6, $c[6], 'www.versace.cn'],
            ['薇婷', 'Veet', '', 4, $c[4], 'www.veet.com.cn'],
            ['梵克雅宝', 'Van Cleef & Arpels', '', 5, $c[5], 'cn.vancleefarpels.com'],
            ['薇薇安·威斯特伍德', 'vivienne westwood', '', 4, $c[4], ''],
            ['华伦天奴', 'Valentino', '', 6, $c[6], 'www.valentino.cn'],
            ['屈臣氏', 'Watsons', '', 19, $c[19], 'www.watsons.com.cn'],
            ['薇诺娜', 'WINONA', '', 1, $c[1], 'www.winona.cn'],
            ['御泥坊', '', '', 1, $c[1], 'www.yunifang.com'],
            ['柔亚', 'ZOYA', '', 2, $c[2], ''],
            ['玉泽', 'Dr. Yuze', '', 1, $c[1], 'www.jahwa.com.cn/yuze'],
            ['欣兰', 'DoMeCare', '', 13, $c[13], 'www.domecare.cn'],
            ['RCMA', '', 'RCMA Makeup', 2, $c[2], ''],
            ['江原道', 'Koh Gen Do', '', 3, $c[3], ''],
            ['赛贝格', 'Dr Sebagh', '', 5, $c[5], ''],
            ['Elta MD', '', '', 2, $c[2], ''],
            ['RE:CIPE', '', '', 10, $c[10], 'www.recipe.vipcom.cn'],
            ['乐敦', 'Rohto', '', 3, $c[3], 'www.rohto.com.cn'],
            ['爸爸的礼物', 'PAPA RECIPE', '', 10, $c[10], ''],
            ['毛孔抚子', 'Keana Nadeshiko', '', 3, $c[3], ''],
            ['法国珍贵水', 'Eau Precieuse', '', 5, $c[5], ''],
            ['Anastasia', '', '', 2, $c[2], ''],
            ['MARIO BADESCU', '', '', 2, $c[2], ''],
            ['达尔肤', 'DR.WU', '', 13, $c[13], 'www.drwuskincare.com.cn'],
            ['传皙娜', 'TRANSINO', '', 3, $c[3], ''],
            ['玉肌', 'Tamanohada', '日本玉肌', 3, $c[3], ''],
            ['奥伦纳素', 'Erno Laszlo', '', 14, $c[14], 'www.ernolaszlo.com.cn'],
            ['欧微泉萨', 'Omorovicza', '', 14, $c[14], ''],
            ['肌研', 'Hada Labo', '', 3, $c[3], 'www.hadalabo.com.cn'],
            ['Pair', '', '', 3, $c[3], ''],
            ['无印良品', 'MUJI', '', 3, $c[3], 'www.muji.com.cn'],
            ['CeraVe', '', '', 2, $c[2], ''],
            ['舒蔻', 'Silcot', '', 3, $c[3], 'www.unicharm.com.cn/silcot'],
            ['封面女郎', 'COVERGIRL', '', 2, $c[2], ''],
            ['怡思丁', 'ISDIN', '', 20, $c[20], ''],
            ['澳佳宝', 'BLACKMORES', '', 8, $c[8], 'www.blackmores.com.cn'],
            ['多芬', 'Dove', '', 2, $c[2], 'www.dove.com.cn/cn'],
            ['EVE LOM', '', '', 4, $c[4], 'www.evelom-cn.com'],
            ['Beauty Buffet', '', '', 21, $c[21], ''],
            ['大创', 'DAISO', '', 3, $c[3], 'www.daisoshanghai.com.cn'],
            ['美迪惠尔', 'MEDIHEAL', '', 10, $c[10], 'www.medihealcos.cn'],
            ['Saborino', '', '', 3, $c[3], ''],
            ['The Ordinary', '', '', 11, $c[11], ''],
            ['A.H.C', '', '', 10, $c[10], ''],
            ['罗伯茨', 'Roberts', '', 6, $c[6], ''],
            ['伊诗露', 'Esthe Dew', '', 3, $c[3], 'www.esthedew.com'],
            ['菊正宗', 'Kikumasamune', '', 3, $c[3], ''],
            ['芭乐雅', 'Balea', '', 7, $c[7], 'www.gykjch.com'],
            ['朗仕', 'Lab Series', '', 2, $c[2], 'www.labseries.com.cn'],
            ['Rhonda Allison', '', '', 2, $c[2], ''],
            ['盛田屋', 'Tofu-Moritaya', '', 3, $c[3], ''],
            ['德国世家', 'Dr.Hauschka', '', 5, $c[5], ''],
            ['ASDM', '', '', 2, $c[2], ''],
            ['明色', 'Meishoku', '', 3, $c[3], ''],
            ['TATCHA', '', '', 2, $c[2], ''],
            ['美人糠', '', 'REAL纯米美人', 3, $c[3], ''],
            ['敏感话题', 'd program', '', 3, $c[3], ''],
            ['纯肌粹', 'JUNKISUI', '', 3, $c[3], ''],
            ['蜜浓', 'MINON', '', 3, $c[3], 'minon-china.com'],
            ['诺吟', 'nooni', '', 10, $c[10], ''],
            ['日本盛', 'Nihonsakari', '', 3, $c[3], ''],
            ['Tunemakers', '', '', 3, $c[3], 'www.tunemakers.net.cn'],
            ['丽思', 'Lits', '', 3, $c[3], ''],
            ['喜辽复', 'Hiruscar', '', 12, $c[12], 'www.hiruscar.com.cn'],
            ['ColourPop', '', '卡乐泡泡', 2, $c[2], ''],
            ['庭润', 'THANN', '', 21, $c[21], ''],
            ['Mistine', '', '', 21, $c[21], ''],
            ['LUCAS PAPAW', '', '', 8, $c[8], ''],
            ['玛蒂德肤', 'MartiDerm', '', 20, $c[20], ''],
            ['MSH', '', '', 3, $c[3], ''],
            ['湿又野', 'Wet \'n Wild', '', 2, $c[2], ''],
            ['Essence', '', '', 7, $c[7], ''],
            ['HOUSE OF ROSE', '', '', 3, $c[3], ''],
            ['Regenerate', '', '', 5, $c[5], ''],
            ['近江兄弟', 'MENTURM', '', 3, $c[3], ''],
            ['excel', '', '', 3, $c[3], ''],
            ['KIKO MILANO', '', '', 6, $c[6], 'www.kikocosmetics.com'],
            ['黎珐', 'ReFa', '', 3, $c[3], 'www.mtg-cn.com/cn'],
            ['zelens', '', '', 4, $c[4], ''],
            ['娜芙', 'Nov', '', 3, $c[3], ''],
            ['Alpha Skin Care', '', '', 2, $c[2], ''],
            ['Addiction', '', '', 3, $c[3], 'www.addiction-beauty.com/zh-jp'],
            ['Bouncia', '', '', 3, $c[3], ''],
            ['牛乳石硷', 'COW STYLE', '', 3, $c[3], 'www.cow-style.cn'],
            ['Flow Fushi', '', '', 3, $c[3], ''],
            ['艾天然', 'Attenir', '', 3, $c[3], ''],
            ['缤若诗', 'Bifesta', '曼丹', 3, $c[3], 'www.bifesta.cn'],
            ['雅倩美', 'ACSEINE', '', 3, $c[3], ''],
            ['ONE BY KOSE', '', '', 3, $c[3], ''],
            ['蜜葳特', 'MELVITA', '', 5, $c[5], ''],
            ['昂法', 'Angfa', '', 3, $c[3], ''],
            ['Club', '', '', 3, $c[3], ''],
            ['TAKAMI', '', '', 3, $c[3], ''],
            ['倩丽', 'CEZANNE', '', 3, $c[3], ''],
            ['绮丝碧', 'Esprique', '', 3, $c[3], 'esprique.kose.com.cn'],
            ['Fujiko', '', '', 3, $c[3], ''],
            ['碧丽妃', 'BENEFIQUE', '', 3, $c[3], ''],
            ['黛佳碧', 'Dejavu', '', 3, $c[3], ''],
            ['Tangle Teezer', '', '', 4, $c[4], ''],
            ['H&M', '', '', 15, $c[15], ''],
            ['完美意境', 'Integrate Gracy', '', 3, $c[3], ''],
            ['绝色魅瘾', 'Integrate', '', 3, $c[3], ''],
            ['Deonatulle', '', '', 3, $c[3], 'www.deonatullesoftstone.com'],
            ['MTG', '', '', 3, $c[3], 'www.mtg-cn.com/cn'],
            ['东洋美学', 'AYURA', '', 3, $c[3], ''],
            ['D-UP', '', 'DUP，D.U.P', 3, $c[3], ''],
            ['Dolly Wink', '', '益若翼', 3, $c[3], ''],
            ['蔻吉', 'KOJI', '益若翼，KOJI-HONPO，本铺', 3, $c[3], ''],
            ['实曈', 'SEED', '', 3, $c[3], ''],
            ['Angelcolor', '', '', 3, $c[3], ''],
            ['糖果娃娃', 'Candy Doll', '益若翼', 3, $c[3], ''],
            ['Candy Magic', '', '', 3, $c[3], ''],
            ['太阳社', '', '', 3, $c[3], ''],
            ['芭露蒂', 'Palty', '', 3, $c[3], ''],
            ['乐而雅', 'Laurier', '', 3, $c[3], 'web.kao.com/cn/laurier'],
            ['苏菲', 'Sofy', '', 3, $c[3], 'www.sofyclub.cn/index.html'],
            ['优衣库', 'uniqlo', '', 3, $c[3], 'www.uniqlo.cn'],
            ['华歌尔', 'Wacoal', '', 3, $c[3], 'www.wacoal.com.cn/'],
            ['mediqtto', '', '', 3, $c[3], ''],
            ['优佳雅', 'Yojiya', '', 3, $c[3], ''],
            ['露姬婷', 'ROSETTE', '', 3, $c[3], ''],
            ['月光力', 'Penelopi Moon', '', 3, $c[3], ''],
            ['悠斯晶', 'Yuskin', '', 3, $c[3], ''],
            ['黛飒丝', 'DEESSE\'S', '玫丽盼Milbon', 3, $c[3], ''],
            ['Nursery', '', '', 3, $c[3], ''],
            ['CHICCA', '', '', 3, $c[3], ''],
            ['亚邦丝', 'AVANCE', '', 3, $c[3], ''],
            ['比那氏', 'Propolinse', '', 3, $c[3], ''],
            ['参天制药', 'Santen', '', 3, $c[3], 'www.santen-china.cn'],
            ['贝印', 'KAI', '', 3, $c[3], 'www.kai-china.com'],
            ['柳屋', 'Yanagiya', '', 3, $c[3], ''],
            ['得鲜', 'the Saem', '', 10, $c[10], ''],
            ['Sleek', '', '', 4, $c[4], ''],
            ['霏丝佳', 'PHYSIOGEL', '施泰福', 2, $c[2], ''],
            ['碧缇丝', 'Batiste', '', 3, $c[3], ''],
            ['佳洁士', 'Crest', '', 2, $c[2], 'www.crest.com.cn'],
            ['Pure vivi', '', '', 3, $c[3], ''],
            ['瞬时美肌', 'Clear Last', '', 3, $c[3], ''],
            ['smile cosmetique', '', '', 3, $c[3], ''],
            ['爱丽思', 'IRIS', '', 3, $c[3], ''],
            ['小泉成器', 'KOIZUMI', '', 3, $c[3], ''],
            ['伊藤良品', '', '贝德氏', 1, $c[1], ''],
            ['Kevyn Aucoin', '', '', 2, $c[2], ''],
            ['IT Cosmetics', '', '', 2, $c[2], ''],
            ['eyeme', '', '', 10, $c[10], ''],
            ['格莱魅', 'GLAMGLOW', '', 2, $c[2], ''],
            ['MUA MAKEUP ACADEMY', '', '', 4, $c[4], ''],
            ['ESPIE ROUGE', '', '', 3, $c[3], ''],
            ['Catrice', '', '', 7, $c[7], ''],
            ['L.A.Girl', '', '', 2, $c[2], ''],
            ['黛玛蔻', 'Dermacol', '', 22, $c[22], ''],
            ['Visee', '', '', 3, $c[3], ''],
            ['魅雅', 'Bbia', '', 10, $c[10], ''],
            ['芭妮兰', 'Banila Co.', '', 10, $c[10], ''],
            ['珂莱欧', 'CLIO', '', 10, $c[10], ''],
            ['氨基梅森', 'Amino Mason', '', 3, $c[3], ''],
            ['玛馨妮', 'MA CHERIE', '玛宣妮，马彩妮，玛彩妮，玛彩泥', 3, $c[3], 'www.macheriechina.com'],
            ['BOTANIST', '', '', 3, $c[3], ''],
            ['澳丝', 'Aussie', '澳洲袋鼠', 8, $c[8], ''],
            ['持田制药', 'mochida', '', 3, $c[3], ''],
            ['Christophe Robin', '', '', 5, $c[5], ''],
            ['熊野油脂', 'Beaua', '', 3, $c[3], ''],
            ['药师堂', 'Sonbahyu', '', 3, $c[3], ''],
            ['摩洛哥油', 'MOROCCANOIL', '', 17, $c[17], ''],
            ['ORIBE', '', '', 2, $c[2], ''],
            ['Real Techniques', '', 'RT化妆工具，RT化妆刷', 2, $c[2], ''],
            ['涂酷', 'TOO COOL FOR SCHOOL', '', 10, $c[10], ''],
            ['薇姿', 'VICHY', '', 5, $c[5], 'www.vichy.com.cn'],
            ['PONY EFFECT', '', '', 10, $c[10], ''],
            ['稚优泉', 'Chioture', '', 1, $c[1], 'www.zhiyouquan.com'],
            ['橘朵', 'Judydoll', '', 1, $c[1], ''],
            ['卡奇色彩', 'Kckc Color', '', 1, $c[1], ''],
            ['花娜小姐', 'Miss Hana', '', 13, $c[13], ''],
            ['碧雅诗', 'Kesalan Patharan', '', 3, $c[3], ''],
            ['香蒲丽', 'Shangpree', '', 10, $c[10], ''],
            ['Imagic', '', '', 1, $c[1], ''],
            ['VNK', '', '', 1, $c[1], ''],
            ['HEDONE', '', '', 1, $c[1], 'www.lovehedone.com'],
            ['NOVO', '', '', 1, $c[1], ''],
            ['菲鹿儿', 'FOCALLURE', '', 5, $c[5], ''],
            ['Hold Live', '', '', 1, $c[1], ''],
            ['赛施黛梦', 'SESDERMA', '', 20, $c[20], ''],
            ['LB', '', '', 3, $c[3], ''],
            ['好莱坞的秘密', 'CINEMA SECRETS', '', 13, $c[13], ''],


            //['', '', '',2, $c[2], ''],
        ];
        $brands = [];
        foreach ($datas as $data) {
            $brands[] = array_combine($records, $data);
        }
        DB::table('brands')->insert($brands);

    }
}
