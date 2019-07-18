<?php
namespace app\index\controller;
use think\Db;
class Index
{
    public function index()
    {
       $ms=Db::table('user');
       $re=$ms->find(1);
       dump($re);
    }
}
