<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/17
 * Time: 13:36
 */
//$ms=mysqli_connect('localhost','root','','test');
//$sql="select * from `user`";
//$result=mysqli_query($ms,$sql);
//
//while ($row=mysqli_fetch_assoc($result)){
//    echo $row['id'].'<br/>';
//}
//$ms=new mysqli('localhost','root','','test');
//var_dump($ms);
include_once "Animal.php";
class Cat implements Animal {
    public  function run()
    {
        // TODO: Implement run() method.
        echo '我在跑';
    }
    public function study()
    {
        // TODO: Implement study() method.
    }
}
$cat=new Cat();
$cat->run();