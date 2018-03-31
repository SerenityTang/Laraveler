<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/1/29
 * Time: 上午2:00
 */

namespace App\Helpers;

use App\Models\Answer;
use App\Models\Attention;
use App\Models\Collection;
use App\Models\Comment;
use App\Models\Question;
use App\Models\Support_opposition;
use App\Models\User_data;
use App\Models\Vote;
use App\User;
use Illuminate\Support\Facades\Auth;

class Helpers {
    /*生成头像图片地址*/
    //if(! function_exists('get_user_avatar')){
        public static function get_user_avatar($user_id, $size='middle', $extension='jpg'){
            return route('image.avatar',['avatar_name' => $user_id.'_'.$size.'.'.$extension]);
        }
    //}

    /*获取用户*/
    public static function get_user($user_id){
        $user = User::where('id', $user_id)->first();
        return $user;
    }

    /*获取用户数据*/
    public static function get_user_data($user_id){
        $user_data = User_data::where('user_id', $user_id)->first();
        return $user_data;
    }

    /*获取回答*/
    public static function get_answer($answer_id){
        $answer = Answer::where('id', $answer_id)->first();
        return $answer;
    }

    /*获取问答*/
    public static function get_question($question_id){
        $question = Question::where('id', $question_id)->first();
        return $question;
    }

    /**
     * 验证是否是中国验证码.
     *
     * @param string $number
     * @return bool
     */
    public static function validateChinaPhoneNumber(string $number)
    {
        return (bool) preg_match('/^(\+?0?86\-?)?((13\d|14[57]|15[^4,\D]|17[3678]|18\d)\d{8}|170[059]\d{7})$/', $number);
    }

    /**
     * 验证用户名是否合法.
     *
     * @param string $username
     * @return bool
     */
    public static function validateUsername(string $username)
    {
        return (bool) preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $username);
    }

    //判断是否支持
    public static function support($mode_id, $mode_type, $mode)
    {
        if (Auth::check()) {
            if ($mode_type === 'Answer' && $mode === 'support') {
                $answer = Answer::where('id', $mode_id)->first();
                $sup_opp = Support_opposition::where('user_id', Auth::user()->id)->where('sup_opp_able_id', $mode_id)->where('sup_opp_able_type', get_class($answer))->where('sup_opp_mode', $mode)->first();
                if ($sup_opp) {
                    return $sup_opp;
                }

                return null;
            } else if ($mode_type === 'Answer' && $mode === 'opposition') {
                $answer = Answer::where('id', $mode_id)->first();
                $sup_opp = Support_opposition::where('user_id', Auth::user()->id)->where('sup_opp_able_id', $mode_id)->where('sup_opp_able_type', get_class($answer))->where('sup_opp_mode', $mode)->first();
                if ($sup_opp) {
                    return $sup_opp;
                }

                return null;
            } else if ($mode_type === 'Comment' && $mode === 'support') {
                $comment = Comment::where('id', $mode_id)->first();
                $sup_opp = Support_opposition::where('user_id', Auth::user()->id)->where('sup_opp_able_id', $mode_id)->where('sup_opp_able_type', get_class($comment))->where('sup_opp_mode', $mode)->first();
                if ($sup_opp) {
                    return $sup_opp;
                }

                return null;
            }
        }
        return null;
    }

    //判断是否投票
    public static function vote($mode_id, $mode_type)
    {
        if (Auth::check()) {
            if ($mode_type === 'Question') {
                $question = Question::where('id', $mode_id)->first();
                $vote = Vote::where('user_id', Auth::user()->id)->where('entityable_id', $mode_id)->where('entityable_type', get_class($question))->first();
                if ($vote) {
                    return $vote;
                }

                return null;
            } else if ($mode_type === 'Question') {
                $question = Question::where('id', $mode_id)->first();
                $vote = Vote::where('user_id', Auth::user()->id)->where('entityable_id', $mode_id)->where('entityable_type', get_class($question))->first();
                if ($vote) {
                    return $vote;
                }

                return null;
            }
        }
        return null;
    }

    //判断是否关注
    public static function attention($mode_id, $mode_type, $curr_userid = null)
    {
        if (Auth::check()) {
            //关注问答
            if ($mode_type === 'Question') {
                $question = Question::where('id', $mode_id)->first();
                $attention = Attention::where('user_id', Auth::user()->id)->where('entityable_id', $mode_id)->where('entityable_type', get_class($question))->first();
                if ($attention) {
                    return $attention;
                }

                return null;
            } else if ($mode_type === 'User') {
                //关注用户
                $user = User::where('id', $mode_id)->first();
                $attention = Attention::where('user_id', $curr_userid)->where('entityable_id', $mode_id)->where('entityable_type', get_class($user))->first();
                if ($attention) {
                    return $attention;
                }

                return null;
            }
        }
        return null;
    }

    //判断是否收藏
    public static function collection($mode_id, $mode_type)
    {
        if (Auth::check()) {
            if ($mode_type === 'Question') {
                $question = Question::where('id', $mode_id)->first();
                $collection = Collection::where('user_id', Auth::user()->id)->where('entityable_id', $mode_id)->where('entityable_type', get_class($question))->first();
                if ($collection) {
                    return $collection;
                }

                return null;
            } else if ($mode_type === 'Question') {
                $question = Question::where('id', $mode_id)->first();
                $collection = Collection::where('user_id', Auth::user()->id)->where('entityable_id', $mode_id)->where('entityable_type', get_class($question))->first();
                if ($collection) {
                    return $collection;
                }

                return null;
            }
        }
        return null;
    }

    /**
     * 裁切生成缩略图(支持本地及阿里oss驱动)
     * @access public
     * @param string $path             路径(必须获得配置upload_folder)
     * @param string $savepath         要保存的路径（路径得包含文件名）
     * @param string $width            宽度
     * @param string $height           高度
     * @param int    $watermark        [可选]，是否添加水印 true:是，false:否，null:从watermark.php配置文件加载设置
     * @param array  $watermark_params [可选]，水印参数，null:从watermark.php配置文件加载设置
     * @return string
     */
    public static function fitImage($path, $savepath, $width = null, $height = null, $watermark = null, $watermark_params = null)
    {

        if (starts_with($path, 'http://') === true || starts_with($path, 'https://') === true) {
            return $path;
        }

        $default_path = 'imgs/img.jpg';

        $query = ['x-oss-process=image'];
        $temp = ['resize'];
        if (!is_null($width)) {
            $temp[] = 'w_' . $width;
        }
        if (!is_null($height)) {
            $temp[] = 'h_' . $height;
        }
        $temp[] = 'm_fill,limit_0';
        $query[] = implode(',', $temp);
        $query[] = 'auto-orient,0/quality,q_100';

        if ($path) {
            if ($watermark === null) {
                $watermark = config('watermark.use_watermark');
            }
            if ($watermark === true && ($watermark_logo = config('watermark.logo'))) {
                if ($watermark_params === null) {
                    $watermark_params = config('watermark.params');
                }
                $temp = [];
                if ($watermark_params) {
                    foreach ($watermark_params as $key => $value) {
                        $temp[] = $key . '_' . $value;
                    }
                }
                $query[] = 'watermark,image_' . self::urlsafe_b64encode($watermark_logo . '?x-oss-process=image/resize,P_10') . ($temp ? ',' . implode(',', $temp) : '');
            } else {
                $watermark = false;
            }
        } else {
            $path = $default_path;
        }

        if (config('global.aliyun_oss')) {
            //已开启了阿里云oss
            $filePath = $path;
        } else {
            #region 图片处理
            $filePath = $savepath . $path;
            $savepath = config('global.upload_folder') . $filePath;

            //创建路径
            $dir = dirname($savepath);
            if (!is_dir($dir)) {
                if (false === mkdir($dir, 0777, true) && !is_dir($dir)) {
                    throw new FileException(sprintf('无法创建 "%s" 文件夹', $dir));
                }
            } elseif (!is_writable($dir)) {
                throw new FileException(sprintf('无法写入 "%s" 文件夹', $dir));
            }

            $savepath = public_path($savepath);
            if (!file_exists($savepath)) {
                $originpath = public_path(config('global.upload_folder') . '/' . $path);
                if (!file_exists($originpath)) {
                    $originpath = public_path($default_path);
                }
                try {
                    $img = Image::make($originpath);
                    $scaleSize = self::scaleImageSize($img, $width, $height);
                    $img->fit($scaleSize['width'], $scaleSize['height']);
                    if ($watermark === true && isset($watermark_logo)) {
                        self::setImageWatermark($img, $watermark_logo, $watermark_params);
                    }
                    $img->save($savepath);
                } catch (\Exception $e) {
                    $filePath = 'imgs/img.jpg';
                }
            }
            #endregion 图片处理
        }

        return self::resourceImage($filePath, $query);
    }

    /**
     * 获取图片资源的请求url
     * @param string       $path  路径
     * @param string|array $query 参数
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public static function resourceImage($path, $query = '')
    {
        if (empty($path)) {
            return self::asset('imgs/img.jpg');
        }
        if (starts_with($path, 'http://') === true || starts_with($path, 'https://') === true) {
            return $path;
        }
        $path = ltrim(config('global.upload_folder') . '/' . ltrim($path, "/\\"), "/\\");
        $path .= (strpos($path, '?') !== false ? '&' : '?') . (is_array($query) ? implode('/', $query) : $query);

        $resource_domain = config('global.resource_domain');
        if (!$resource_domain) {
            $resource_domain = rtrim(url('/'), "/\\");
        }

        return $resource_domain . '/' . $path;
    }

    public static function setActive($path, $active = 'active')
    {
        //dump($path);
        return call_user_func_array('Request::is', (array)$path) ? $active : '';
    }
}

