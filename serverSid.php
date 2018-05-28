<?php
//载入ucpass类
require_once('lib/Ucpaas.class.php');

//初始化必填
//填写在开发者控制台首页上的Account Sid
$options['accountsid']='d9ddebbc3fdebc6937457de53c6bad89';
//填写在开发者控制台首页上的Auth Token
$options['token']='47ffa872afee477d909564777f8991ba';

//初始化 $options必填
$ucpass = new Ucpaas($options);