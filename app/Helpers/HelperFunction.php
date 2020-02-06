<?php

/**
 * curl get的形式访问
 *
 * @param $url
 * @return mixed
 */
function httpGet($url, $headers = "")
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if ($headers != "") {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    $res = curl_exec($ch);
    curl_close($ch);

    return $res;
}

/**
 * curl post的形式访问
 *
 * @param $url
 * @param $array
 * @param string $headers
 * @return bool|string
 */
function httpPost($url, $array, $headers = "")
{
    if (is_array($array)) {
        $data = json_encode($array);
    } else {
        $data = $array;
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    if ($headers != "") {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }
    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
}

/**
 * curl put的形式访问
 *
 * @param $url
 * @param $array
 * @param string $headers
 * @return mixed
 */
function httpPut($url, $array, $headers = "")
{
    $ch = curl_init(); //初始化CURL句柄
    curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); //设置请求方式
    if (count($array) > 0) {
        $data = json_encode($array);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//设置提交的字符串
    }
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    if ($headers != "") {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    $res = curl_exec($ch);
    curl_close($ch);

    return $res;
}

function httpPutContent($url, $content, $headers = "")
{
    $ch = curl_init(); //初始化CURL句柄
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FILETIME, true);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, false);
    curl_setopt($ch, CURLOPT_HEADER, true); // 输出HTTP头 true
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5184000);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
    $res = curl_exec($ch);
    curl_close($ch);

    return $res;
}

/**
 * curl delete的形式访问
 *
 * @param $url
 * @return mixed
 */
function httpDelete($url, $headers = "")
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    $res = curl_exec($ch);
    curl_close($ch);

    return $res;
}

/**
 * 图片url转base64（去掉‘data:image/jpeg;base64,’）
 *
 * @param string $img
 * @param bool $imgHtmlCode
 * @return string
 */
function imgToBase64($img = '', $imgHtmlCode = true)
{
    $imageInfo = getimagesize($img);
    $base64 = "" . chunk_split(base64_encode(file_get_contents($img)));
    return chunk_split(base64_encode(file_get_contents($img)));
}

/**
 * pdf文件url转换
 *
 * @param $filePath
 * @return string
 */
function fileToBase64Md5($filePath)
{
    //获取文件MD5的128位二进制数组
    $md5file = md5_file($filePath, true);
    //计算文件的Content-MD5
    return base64_encode($md5file);
}

function base642pdf($formTxt, $toPdf)
{
    $file = file_get_contents($formTxt);//读
    $data = base64_decode($file);//转换
    file_put_contents($toPdf, $data);//写

    //$img = base64_decode($formTxt);
    //$a = file_put_contents(storage_path('app/pubic/pdf/test.pdf'), $img);
}

/**
 * 生成手机随机6位数字验证码
 *
 * @return string
 */
function phoneCode()
{
    $key = '';
    $pattern = '1234567890';
    for ($i = 0; $i < 6; $i++) {
        $key .= $pattern[mt_rand(0, 9)];
    }
    return $key;
}

/**
 * 生成指定个数随机数字
 *
 * @return string
 */
function randomCode($number)
{
    $key = '';
    $pattern = '1234567890';
    for ($i = 0; $i < $number; $i++) {
        $key .= $pattern[mt_rand(0, 9)];
    }
    return $key;
}

/**
 * 删除文件夹及文件夹下所有文件
 *
 * @param $dir
 * @return bool
 */
function deldir($dir)
{
    //先删除目录下的文件：
    if (is_dir($dir)) {
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    deldir($fullpath);
                }
            }
        }
        closedir($dh);
        //删除当前文件夹：
        if (rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * 17位是时间戳（yyyyMMddHHmmssSSS）
 *
 * @return string
 */
function get_millisecond()
{

    list($usec, $sec) = explode(" ", microtime());
    $msec = round($usec * 1000);

    return date('YmdHis', time()) . $msec;
}

/**
 * 获取pdf页数
 *
 * @param $path
 * @return bool|int|mixed
 */
function getPageTotal($path)
{
    // 打开文件
    if (!$fp = @fopen($path, "r")) {
        $error = "打开文件{$path}失败";
        return false;
    } else {
        $max = 0;
        while (!feof($fp)) {
            $line = fgets($fp, 255);
            if (preg_match('/\/Count [0-9]+/', $line, $matches)) {
                preg_match('/[0-9]+/', $matches[0], $matches2);
                if ($max < $matches2[0]) $max = $matches2[0];
            }
        }
        fclose($fp);
        // 返回页数
        return $max;
    }
}

/**
 * 生成批次号
 *
 * @param $shopId
 * @return string
 */
function getBatchNumber($shopId)
{
    $date = date("Ymd");
    $fiveShopNo = str_pad($shopId, 5, "0", STR_PAD_LEFT);

    $startOfDay = date("Y-m-d 00:00:00");
    $endOfDay = date("Y-m-d 23:59:59");
    $batchno = \App\Models\BalancePay::where('shopid', $shopId)->whereBetween('dateline', [strtotime($startOfDay), strtotime($endOfDay)])
        ->pluck('batchno')->toArray();
    $batchCount = count(array_unique($batchno));
    if ($batchCount == 0) {
        $batchCount = 1;
    } elseif ($batchCount >= 1) {
        $batchCount++;
    }
    $fourShopNo = str_pad($batchCount, 4, "0", STR_PAD_LEFT);

    return $date . $fiveShopNo . $fourShopNo;
}

// 客户端 ip
function getHttpClientIP()
{
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
        $ip = getenv("HTTP_CLIENT_IP");
    } else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    } else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
        $ip = getenv("REMOTE_ADDR");
    } else if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
        $ip = $_SERVER['REMOTE_ADDR'];
    } else {
        $ip = "unknown";
    }
    return $ip;
}
