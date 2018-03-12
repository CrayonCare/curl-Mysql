<?php

/**
 * @param string $img_url 下载文件地址
 * @param string $save_path 下载文件保存目录
 * @param string $filename 下载文件保存名称
 * @return bool
 */
class down extends Mysql
{
    public function __construct()
    {

    }

    function curlDownFile($img_url, $save_path = '', $filename = '')
    {
        if (trim($img_url) == '') {
            return false;
        }
        if (trim($save_path) == '') {
            $save_path = './';
        }

        //创建保存目录
        if (!file_exists($save_path) && !mkdir($save_path, 0777, true)) {
            return false;
        }
        if (trim($filename) == '') {
            $img_ext = strrchr($img_url, '.');
            $img_exts = array('.txt', '.xls');
            if (!in_array($img_ext, $img_exts)) {
                return false;
            }
            $filename = 'Songs' . $img_ext;
            if (file_exists('Songs.txt')) {
                unlink('Songs.txt');
            }
        }

        // curl下载文件
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $img_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $img = curl_exec($ch);
        curl_close($ch);


        // 保存文件到制定路径
        file_put_contents($filename, $img);

        unset($img, $url);
        return true;
    }


}

class Mysql
{
    public function __construct()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->debug = true;
        $this->connect = mysqli_connect('localhost', 'root', 'root', 'table') or die('Unale to connect');
        // 执行utf8
        $ret = mysqli_query($this->connect, "set names utf8");
        if ($ret) {
            $this->db_log("The database connection is successful.");
        } else {
            $this->db_log("The database connection is failed.");
        }
    }


    public function db_log($log)
    {
        if ($this->debug) {
            echo $log;
        }
    }

    //读取文件内容
    public function leadingIn()
    {
        $file = fopen('Songs.txt', 'r');
        $data = array();
        $i = 0;
        while (!feof($file)) {
            $data[$i] = fgets($file);
            $i++;
        }
        fclose($file);
        $data = array_filter($data);
        return $data;
    }

    //批量插入song_sheet 表
    public function Inser($sql)
    {
        $result = mysqli_query($this->connect, $sql) or die(mysqli_error($this->connect));
        if (!$result) {
            return false;
        }
        return true;
    }



}
            $down = new down();
            //请curlDownFile 里 填写http 文件地址
            $down->curlDownFile("http://test.com/test.txt");
            $Mysql = new Mysql();
            $data = $Mysql->leadingIn();
            $array = array();
            //拼接sql 语句 ,批量一起插入
            $sql = "INSERT INTO `song_sheet` (`song_no`,`song_name`,`singer_name`,`language`,`film_classification`,`dance_category`,`music_classification`,`epidemic_classification`,`picture_type`,`max_volume`,`song_count`,`backup_information`,`picture`,`retain`,`retain_date`) VALUES";
      foreach ($data as $key => $val) {
                $array = explode('|', $val);
                array_shift($array);
                array_pop($array);
          //数据字段较多~没有处理,就这样把~
          $sql .= '("' . $array[0] . '",' . '"' .str_replace( '"','',$array[1]) . '",' . '"' . $array[2] . '",' . '"' . $array[3] . '",' . '"' . $array[4] . '",' . '"' . $array[5] . '",' . '"' . $array[6] . '",' . '"' . $array[7] . '",' . '"' . $array[8] . '",' . '"' . $array[9] . '",' . '"' . $array[10] . '",' . '"' . $array[11] . '",' . '"' . $array[12] . '",' . '"' . $array[13] . '",' . '"' . $array[14] . '"),';
      }
//最后一条会多出一个,符号 所以需要把他切割出来,以免sql报错
    $sql = substr($sql, 0, -1) ;
//执行sql INSERT INTO 
    $result = $Mysql -> Inser($sql);

