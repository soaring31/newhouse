<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年2月17日
*/
namespace CoreBundle\Functions;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Materiallibrary extends ServiceBase
{
    /**
     * 容器
     * @var object
     */
    protected $container;
   
    protected $color;
    
    protected $folderArr;
    

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function getColor()
    {
        return $this->color;
    }
    
    public function getFolderArr()
    {
        return $this->folderArr;
    }
    
    public function files()
    {
        $color = $this->get('request')->get('color');
        $folder = $this->get('request')->get('folder');
        $folder = $folder ? $folder : 'canyin';
        $folderArr = array(
            'canyin' => '餐饮',
            'wedding' => '婚纱摄影',
            'fangdichan' => '房地产',
            'tour' => '旅游',
            'jianshenmeirong' => '健身美容',
            'health' => '医疗保健',
            'edu' => '教育培训',
            'car' => '汽车',
            'hotel' => '酒店'
        );
    
        $icons = array(
            'canyin' => array(
                'name' => '餐饮',
                'height' => 60
            ),
            'hotel' => array(
                'name' => '酒店',
                'height' => 60
            ),
            'car' => array(
                'name' => '汽车',
                'height' => 60
            ),
            'tour' => array(
                'name' => '旅游',
                'height' => 60
            ),
            'fangdichan' => array(
                'name' => '房地产',
                'height' => 60
            ),
            'edu' => array(
                'name' => '教育培训',
                'height' => 60
            ),
            'jianshenmeirong' => array(
                'name' => '健身美容',
                'height' => 60
            ),
            'health' => array(
                'name' => '医疗保健',
                'height' => 60
            ),
            'wedding' => array(
                'name' => '婚纱摄影',
                'height' => 60
            ),
    
            'lovely' => array(
                'name' => '卡通图标',
                'height' => 60,
                'files' => array(
                    '1.png',
                    'backpack-2.png',
                    'bill.png',
                    'bookmark.png',
                    'bookshelf.png',
                    'briefcase.png',
                    'bus.png',
                    'calc.png',
                    'candy.png',
                    'car.png',
                    'chalkboard.png',
                    'clock.png',
                    'cloud-check.png',
                    'cloud-down.png',
                    'cloud-error.png',
                    'cloud-refresh.png',
                    'cloud-up.png',
                    'donut.png',
                    'drop.png',
                    'eye.png',
                    'flag.png',
                    'glasses.png',
                    'glove.png',
                    'hamburger.png',
                    'hand.png',
                    'hotdog.png',
                    'knife.png',
                    'label.png',
                    'map.png',
                    'map2.png',
                    'marker.png',
                    'mcfly.png',
                    'medicine.png',
                    'mountain.png',
                    'muffin.png',
                    'open-letter.png',
                    'packman.png',
                    'paper-plane.png',
                    'photo.png',
                    'piggy.png',
                    'pin.png',
                    'pizza.png',
                    'r2d2.png',
                    'rocket.png',
                    'skull.png',
                    'speakers.png',
                    'store.png',
                    'tactics.png',
                    'toaster.png',
                    'train.png',
                    'umbrella.png',
                    'watch.png',
                    'www.png',
                    '2.png',
                    '3.png',
                    '4.png',
                    '5.png',
                    '6.png',
                    '7.png',
                    '8.png',
                    '9.png',
                    '10.png',
                    '11.png'
                )
            ),
            'colorful' => array(
                'name' => '彩色图标',
                'height' => 70,
                'files' => array(
                    '1.png',
                    '2.png',
                    '3.png',
                    '4.png',
                    '5.png',
                    '6.png',
                    '7.png',
                    '8.png',
                    '9.png',
                    '10.png',
                    '11.png'
                )
            ),
            'white' => array(
                'name' => '白色图标',
                'height' => 50,
                'files' => array(
                    '1.png',
                    '2.png',
                    '3.png',
                    '4.png',
                    '5.png',
                    '6.png',
                    '7.png',
                    '8.png',
                    '9.png',
                    '10.png',
                    '11.png',
                    '12.png',
                    '13.png',
                    '14.png',
                    '15.png',
                    '16.png'
                )
            ),
            'line' => array(
                'name' => '线条图标',
                'height' => 50,
                'files' => array(
                    'banknote.png',
                    'bubble.png',
                    'bulb.png',
                    'calendar.png',
                    'camera.png',
                    'clip.png',
                    'clock.png',
                    'cloud.png',
                    'cup.png',
                    'data.png',
                    'diamond.png',
                    'display.png',
                    'eye.png',
                    'fire.png',
                    'food.png',
                    'heart.png',
                    'key.png',
                    'lab.png',
                    'like.png',
                    'location.png',
                    'lock.png',
                    'mail.png',
                    'megaphone.png',
                    'music.png',
                    'news.png',
                    'note.png',
                    'paperplane.png',
                    'params.png',
                    'pen.png',
                    'phone.png',
                    'photo.png',
                    'search.png',
                    'settings.png',
                    'shop.png',
                    'sound.png',
                    'stack.png',
                    'star.png',
                    'study.png',
                    't-shirt.png',
                    'tag.png',
                    'trash.png',
                    'truck.png',
                    'tv.png',
                    'user.png',
                    'vallet.png',
                    'video.png',
                    'vynil.png',
                    'world.png'
                )
            )
        );
    
        $folderArr = array_flip($folderArr);
    
        $this->folderArr = $folderArr;
        $this->color = $color;
    
        if (in_array($folder, $folderArr, true)) {
            // 增加icon图标需要指定数量，并且是每个颜色拥有相同的个数
            $numArr = array(
                'canyin' => 24,
                'hotel' => 27,
                'car' => 28,
                'tour' => 25,
                'fangdichan' => 24,
                'edu' => 28,
                'jianshenmeirong' => 25,
                'health' => 25,
                'wedding' => 21
            );
    
            $num = $numArr[$folder];
    
            if ($color) {
                $filesArr = array();
                // Has Color
                for ($j = 1; $j <= $num; $j ++) {
                    $filesArr[] = "{$folder}_{$color}/{$j}.png";
                }
                $icons[$folder]['files'] = $filesArr;
            } else {
                // 'No color'
                $allColor = array(
                    'red',
                    'orange',
                    'yellow',
                    'green',
                    'blue',
                    'gray',
                    'purple',
                    'brown',
                    'white'
                );
    
                $arr = array();
                foreach ($allColor as $k => $v) {
                    for ($j = 1; $j <= $num; $j ++) {
                        $filesArr[$k][] = "{$folder}_{$v}/{$j}.png";
                    }
                    $arr[$k] = $filesArr[$k];
                }
                $icons[$folder]['files'] = array_merge($arr[0], $arr[1], $arr[2], $arr[3], $arr[4], $arr[5], $arr[6], $arr[7], $arr[8]);
            }
        }
    
        $background = array(
            'view' => '',
            'canyin' => array(
                'name' => '餐饮',
                'height' => 100,
                'files' => array(
                    '1.jpg',
                    '2.jpg',
                    '3.jpg',
                    '4.jpg',
                    '5.jpg',
                    '6.jpg',
                    '7.jpg',
                    '8.jpg'
                )
            ),
            'hotel' => array(
                'name' => '酒店',
                'height' => 100,
                'files' => array(
                    '1.jpg',
                    '2.jpg',
                    '3.jpg',
                    '4.jpg',
                    '5.jpg'
                )
            ),
            'car' => array(
                'name' => '汽车',
                'height' => 100,
                'files' => array(
                    '1.jpg',
                    '2.jpg',
                    '3.jpg',
                    '4.jpg',
                    '5.jpg',
                    '6.jpg',
                    '7.jpg',
                    '8.jpg',
                    '9.jpg',
                    '10.jpg',
                    '11.jpg',
                    '12.jpg'
                )
            ),
            'tour' => array(
                'name' => '旅游',
                'height' => 100,
                'files' => array(
                    '1.jpg',
                    '2.jpg',
                    '3.jpg',
                    '4.jpg',
                    '5.jpg'
                )
            ),
            'fangdichan' => array(
                'name' => '房地产',
                'height' => 100,
                'files' => array(
                    '1.jpg',
                    '2.jpg',
                    '3.jpg',
                    '4.jpg',
                    '5.jpg',
                    '6.jpg',
                    '7.jpg',
                    '8.jpg',
                    '9.jpg'
                )
            ),
            'edu' => array(
                'name' => '教育培训',
                'height' => 100,
                'files' => array(
                    '1.jpg',
                    '2.jpg',
                    '3.jpg',
                    '4.jpg',
                    '5.jpg',
                    '6.jpg',
                    '7.jpg',
                    '8.jpg',
                    '9.jpg',
                    '10.jpg',
                    '11.jpg',
                    '12.jpg',
                    '13.jpg',
                    '14.jpg',
                    '15.jpg',
                    '16.jpg',
                    '17.jpg',
                    '18.jpg',
                    '19.jpg',
                    '20.jpg',
                    '21.jpg',
                    '22.jpg',
                    '23.jpg',
                    '24.jpg',
                    '25.jpg',
                    '26.jpg',
                    '27.jpg'
                )
            ),
            'jianshenmeirong' => array(
                'name' => '健身美容',
                'height' => 100,
                'files' => array(
                    '1.jpg',
                    '2.jpg',
                    '3.jpg',
                    '4.jpg',
                    '5.jpg',
                    '6.jpg',
                    '7.jpg',
                    '8.jpg',
                    '9.jpg',
                    '10.jpg'
                )
            ),
            'health' => array(
                'name' => '医疗保健',
                'height' => 100,
                'files' => array(
                    '1.jpg',
                    '2.jpg',
                    '3.jpg',
                    '4.jpg',
                    '5.jpg',
                    '6.jpg',
                    '7.jpg',
                    '8.jpg',
                    '9.jpg',
                    '10.jpg',
                    '11.jpg',
                    '12.jpg',
                    '13.jpg',
                    '14.jpg'
                )
            ),
            'wedding' => array(
                'name' => '婚纱摄影',
                'height' => 100,
                'files' => array(
                    '1.jpg',
                    '2.jpg',
                    '3.jpg',
                    '4.jpg',
                    '5.jpg',
                    '6.jpg',
                    '7.jpg',
                    '8.jpg',
                    '9.jpg'
                )
            )
        )
        ;
    
        if ($_GET['type'] == 'background') {
            $view = array();
            $view['canyin'] = array_map(array(__CLASS__,"canyinMap"),$background['canyin']['files']);
            $view['hotel'] = array_map(array(__CLASS__,"hotelMap"),$background['hotel']['files']);
            $view['car'] = array_map(array(__CLASS__,"carMap"),$background['car']['files']);
            $view['tour'] = array_map(array(__CLASS__,"tourMap"),$background['tour']['files']);
            $view['fangdichan'] = array_map(array(__CLASS__,"fangdichanMap"),$background['fangdichan']['files']);
            $view['edu'] = array_map(array(__CLASS__,"eduMap"),$background['edu']['files']);
            $view['jianshenmeirong'] = array_map(array(__CLASS__,"jianshenmeirongMap"),$background['jianshenmeirong']['files']);
            $view['health'] = array_map(array(__CLASS__,"healthMap"),$background['health']['files']);
            $view['wedding'] = array_map(array(__CLASS__,"weddingMap"),$background['wedding']['files']);
            $view['view'] = array(
                '1.jpg',
                '2.jpg',
                '3.jpg',
                '4.jpg',
                '5.jpg',
                '6.jpg',
                '7.jpg',
                '8.jpg',
                '9.jpg'
            );
    
            $background['view'] = array(
                'name' => '默认',
                'height' => 100,
                'files' => array_merge($view['view'], $view['canyin'], $view['hotel'], $view['car'], $view['tour'], $view['fangdichan'], $view['edu'], $view['jianshenmeirong'], $view['health'], $view['wedding'])
            );
        }
    
        $focus = array(
    
            'default' => '',
            'canyin' => array(
                'name' => '餐饮',
                'height' => 70,
                'files' => array(
                    '1.jpg',
                    '2.jpg',
                    '3.jpg',
                    '4.jpg',
                    '5.jpg',
                    '6.jpg',
                    '7.jpg',
                    '8.jpg',
                    '9.jpg',
                    '10.jpg',
                    '11.jpg',
                    '12.jpg',
                    '13.jpg',
                    '14.jpg'
                )
            ),
            'hotel' => array(
                'name' => '酒店',
                'height' => 70,
                'files' => array(
                    '1.jpg',
                    '2.jpg',
                    '3.jpg',
                    '4.jpg',
                    '5.jpg',
                    '6.jpg',
                    '7.jpg',
                    '8.jpg',
                    '9.jpg'
                )
            ),
            'car' => array(
                'name' => '汽车',
                'height' => 70,
                'files' => array(
                    '1.jpg',
                    '2.jpg',
                    '3.jpg',
                    '4.jpg',
                    '5.jpg',
                    '6.jpg',
                    '7.jpg',
                    '8.jpg',
                    '9.jpg',
                    '10.jpg',
                    '11.jpg',
                    '12.jpg',
                    '13.jpg',
                    '14.jpg',
                    '15.jpg',
                    '16.jpg'
                )
            ),
            'tour' => array(
                'name' => '旅游',
                'height' => 70,
                'files' => array(
                    '1.jpg',
                    '2.jpg',
                    '3.jpg',
                    '4.jpg',
                    '5.jpg',
                    '6.jpg',
                    '7.jpg',
                    '8.jpg',
                    '9.jpg',
                    '10.jpg',
                    '11.jpg',
                    '12.jpg',
                    '13.jpg',
                    '14.jpg'
                )
            ),
            'fangdichan' => array(
                'name' => '房地产',
                'height' => 70,
                'files' => array(
                    '1.jpg',
                    '2.jpg',
                    '3.jpg',
                    '4.jpg',
                    '5.jpg',
                    '6.jpg',
                    '7.jpg',
                    '8.jpg',
                    '9.jpg'
                )
            ),
            'edu' => array(
                'name' => '教育培训',
                'height' => 70,
                'files' => array(
                    '1.jpg',
                    '2.jpg',
                    '3.jpg',
                    '4.jpg',
                    '5.jpg',
                    '6.jpg',
                    '7.jpg',
                    '8.jpg',
                    '9.jpg',
                    '10.jpg',
                    '11.jpg',
                    '12.jpg',
                    '13.jpg',
                    '14.jpg',
                    '15.jpg',
                    '16.jpg',
                    '17.jpg'
                )
            ),
            'jianshenmeirong' => array(
                'name' => '健身美容',
                'height' => 70,
                'files' => array(
                    '1.jpg',
                    '2.jpg',
                    '3.jpg',
                    '4.jpg',
                    '5.jpg',
                    '6.jpg',
                    '7.jpg',
                    '8.jpg',
                    '9.jpg',
                    '10.jpg',
                    '11.jpg',
                    '12.jpg',
                    '13.jpg'
                )
            ),
            'health' => array(
                'name' => '医疗保健',
                'height' => 70,
                'files' => array(
                    '1.jpg',
                    '2.jpg',
                    '3.jpg',
                    '4.jpg',
                    '5.jpg',
                    '6.jpg',
                    '7.jpg',
                    '8.jpg',
                    '9.jpg',
                    '10.jpg',
                    '11.jpg'
                )
            ),
            'wedding' => array(
                'name' => '婚纱摄影',
                'height' => 70,
                'files' => array(
                    '1.jpg',
                    '2.jpg',
                    '3.jpg',
                    '4.jpg',
                    '5.jpg',
                    '6.jpg',
                    '7.jpg',
                    '8.jpg',
                    '9.jpg',
                    '10.jpg',
                    '11.jpg',
                    '12.jpg',
                    '13.jpg'
                )
            )
        );
        if ($_GET['type'] == 'focus') {
    
            function canyinMap($eachV)
            {
                return '../canyin/' . $eachV;
            }
    
            function hotelMap($eachV)
            {
                return '../hotel/' . $eachV;
            }
    
            function carMap($eachV)
            {
                return '../car/' . $eachV;
            }
    
            function tourMap($eachV)
            {
                return '../tour/' . $eachV;
            }
    
            function fangdichanMap($eachV)
            {
                return '../fangdichan/' . $eachV;
            }
    
            function eduMap($eachV)
            {
                return '../edu/' . $eachV;
            }
    
            function jianshenmeirongMap($eachV)
            {
                return '../jianshenmeirong/' . $eachV;
            }
    
            function healthMap($eachV)
            {
                return '../health/' . $eachV;
            }
    
            function weddingMap($eachV)
            {
                return '../wedding/' . $eachV;
            }
    
            $view['canyin'] = array_map(array(__CLASS__,"canyinMap"), $focus['canyin']['files']);
            $view['hotel'] = array_map(array(__CLASS__,"hotelMap"), $focus['hotel']['files']);
            $view['car'] = array_map(array(__CLASS__,"carMap"), $focus['car']['files']);
            $view['tour'] = array_map(array(__CLASS__,"tourMap"), $focus['tour']['files']);
            $view['fangdichan'] = array_map(array(__CLASS__,"fangdichanMap"), $focus['fangdichan']['files']);
            $view['edu'] = array_map(array(__CLASS__,"eduMap"), $focus['edu']['files']);
            $view['jianshenmeirong'] = array_map(array(__CLASS__,"jianshenmeirongMap"), $focus['jianshenmeirong']['files']);
            $view['health'] = array_map(array(__CLASS__,"healthMap"), $focus['health']['files']);
            $view['wedding'] = array_map(array(__CLASS__,"weddingMap"), $focus['wedding']['files']);
            $view['view'] = array(
                '1.gif',
                '2.jpg',
                '3.jpg',
                '4.jpg',
                '5.gif',
                '6.jpg'
            );
    
            $focus['default'] = array(
                'name' => '默认',
                'height' => 100,
                'files' => array_merge($view['view'], $view['canyin'], $view['hotel'], $view['car'], $view['tour'], $view['fangdichan'], $view['edu'], $view['jianshenmeirong'], $view['health'], $view['wedding'])
            );
        }
        $music = array(
            'default' => array(
                'name' => '默认',
                'files' => array(
                    array(
                        'file' => '1.mp3',
                        'name' => '汪峰-一起摇摆'
                    ),
                    array(
                        'file' => '2.mp3',
                        'name' => '方大同-明天我要嫁给你了'
                    ),
                    array(
                        'file' => '3.mp3',
                        'name' => '今天你要嫁给我'
                    ),
                    array(
                        'file' => '4.mp3',
                        'name' => '钢琴曲卡农'
                    )
                )
            )
        );
        return array(
            'icon' => $icons,
            'background' => $background,
            'music' => $music,
            'focus' => $focus
        );
    }
    
    public function canyinMap($eachV){
        return '../canyin/'.$eachV;
    }
    public function hotelMap($eachV){
        return '../hotel/'.$eachV;
    }
    public function carMap($eachV){
        return '../car/'.$eachV;
    }
    public function tourMap($eachV){
        return '../tour/'.$eachV;
    }
    public function fangdichanMap($eachV){
        return '../fangdichan/'.$eachV;
    }
    public function eduMap($eachV){
        return '../edu/'.$eachV;
    }
    public function jianshenmeirongMap($eachV){
        return '../jianshenmeirong/'.$eachV;
    }
    public function healthMap($eachV){
        return '../health/'.$eachV;
    }
    public function weddingMap($eachV){
        return '../wedding/'.$eachV;
    }
    
    public function __clone(){}
}