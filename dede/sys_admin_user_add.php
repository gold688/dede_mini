<?php
/**
 * ���ϵͳ����Ա
 *
 * @version        $Id: sys_admin_user_add.php 1 16:22 2010��7��20��Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_User');
require_once(DEDEINC."/typelink.class.php");
if(empty($dopost)) $dopost='';

if($dopost=='add')
{
    if(preg_match("#[^0-9a-zA-Z_@!\.-]#", $pwd) || preg_match("#[^0-9a-zA-Z_@!\.-]#", $userid))
    {
        ShowMsg('�������û������Ϸ���<br />��ʹ��[0-9a-zA-Z_@!.-]�ڵ��ַ���', '-1', 0, 3000);
        exit();
    }
    $safecodeok = substr(md5($cfg_cookie_encode.$randcode), 0, 24);
    if($safecode != $safecodeok )
    {
        ShowMsg('����д��ȫ��֤����', '-1', 0, 3000);
        exit();
    }
    $mpwd = md5($pwd);
    $pwd = substr(md5($pwd), 5, 20);

    $typeid = join(',', $typeids);
    if($typeid=='0') $typeid = '';
    
    //��̨����Ա
    $inquery = "INSERT INTO `#@__admin`(id,usertype,userid,pwd,uname,typeid,tname,email)
                                                    VALUES('$mid','$usertype','$userid','$pwd','$uname','$typeid','$tname','$email'); ";
    $rs = $dsql->ExecuteNoneQuery($inquery);

    $adminquery = "INSERT INTO `#@__member_tj` (`mid`,`article`,`album`,`archives`,`homecount`,`pagecount`,`feedback`,`friend`,`stow`)
                     VALUES ('$mid','0','0','0','0','0','0','0','0'); ";
    $dsql->ExecuteNoneQuery($adminquery);
    ShowMsg('�ɹ�����һ���û���', 'sys_admin_user.php');
    exit();
}
$randcode = mt_rand(10000, 99999);
$safecode = substr(md5($cfg_cookie_encode.$randcode), 0, 24);
$typeOptions = '';
$dsql->SetQuery(" SELECT id,typename FROM `#@__arctype` WHERE reid=0 AND (ispart=0 OR ispart=1) ");
$dsql->Execute('op');
while($row = $dsql->GetObject('op'))
{
    $topc = $row->id;
    $typeOptions .= "<option value='{$row->id}' class='btype'>{$row->typename}</option>\r\n";
    $dsql->SetQuery(" SELECT id,typename FROM `#@__arctype` WHERE reid={$row->id} AND (ispart=0 OR ispart=1) ");
    $dsql->Execute('s');
    while($row = $dsql->GetObject('s'))
    {
        $typeOptions .= "<option value='{$row->id}' class='stype'>��{$row->typename}</option>\r\n";
    }
}
include DedeInclude('templets/sys_admin_user_add.htm');