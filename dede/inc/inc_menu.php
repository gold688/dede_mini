<?php
/**
 * ��̨����˵���
 *
 * @version        $Id: inc_menu.php 1 10:32 2010��7��21��Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/../config.php");

//����ɷ���Ƶ��
$addset = '';

//�����õ�����ģ��
if($cfg_admin_channel = 'array' && count($admin_catalogs) > 0)
{
    $admin_catalog = join(',', $admin_catalogs);
    $dsql->SetQuery(" SELECT channeltype FROM `#@__arctype` WHERE id IN({$admin_catalog}) GROUP BY channeltype ");
}
else
{
    $dsql->SetQuery(" SELECT channeltype FROM `#@__arctype` GROUP BY channeltype ");
}
$dsql->Execute();
$candoChannel = '';
while($row = $dsql->GetObject())
{
    $candoChannel .= ($candoChannel=='' ? $row->channeltype : ','.$row->channeltype);
}
if(empty($candoChannel)) $candoChannel = 1;
$dsql->SetQuery("SELECT id,typename,addcon,mancon FROM `#@__channeltype` WHERE id IN({$candoChannel}) AND id<>-1 AND isshow=1 ORDER BY id ASC");
$dsql->Execute();
while($row = $dsql->GetObject())
{
    $addset .= "  <m:item name='{$row->typename}' ischannel='1' link='{$row->mancon}?channelid={$row->id}' linkadd='{$row->addcon}?channelid={$row->id}' channelid='{$row->id}' rank='' target='main' />\r\n";
}
//////////////////////////

$remoteMenu = ($cfg_remote_site=='Y')? "<m:item name='Զ�̷�����ͬ��' link='makeremote_all.php' rank='sys_ArcBatch' target='main' />" : "";
$menusMain = "
-----------------------------------------------

<m:top item='1_' name='���ò���' display='block'>
  <m:item name='��վ��Ŀ����' link='catalog_main.php' ischannel='1' addalt='������Ŀ' linkadd='catalog_add.php?listtype=all' rank='t_List,t_AccList' target='main' />
  <m:item name='���е����б�' link='content_list.php' rank='a_List,a_AccList' target='main' />
  <m:item name='����˵ĵ���' link='content_list.php?arcrank=-1' rank='a_Check,a_AccCheck' target='main' />
  <m:item name='�ҷ������ĵ�' link='content_list.php?mid=".$cuserLogin->getUserID()."' rank='a_List,a_AccList,a_MyList' target='main' />
  <m:item name='���ݻ���վ' link='recycling.php' addalt='��ջ���վ' rank='a_List,a_AccList,a_MyList' target='main' />
</m:top>
<m:top item='1_' name='HTML����' notshowall='1' display='none' rank='sys_MakeHtml' >
  <m:item name='������ҳHTML' link='makehtml_homepage.php' rank='sys_MakeHtml' target='main' />
  <m:item name='������ĿHTML' link='makehtml_list.php' rank='sys_MakeHtml' target='main' />
  <m:item name='�����ĵ�HTML' link='makehtml_archives.php' rank='sys_MakeHtml' target='main' />
  <m:item name='������վ��ͼ' link='makehtml_map_guide.php' rank='sys_MakeHtml' target='main' />
  <m:item name='����RSS�ļ�' link='makehtml_rss.php' rank='sys_MakeHtml' target='main' />
  <m:item name='��ȡJS�ļ�' link='makehtml_js.php' rank='sys_MakeHtml' target='main' />
  <m:item name='����ר��HTML' link='makehtml_spec.php' rank='sys_MakeHtml' target='main' />
</m:top>
<m:top item='1_' name='ϵͳ����' display='none' rank='sys_User,sys_Group,sys_Edit,sys_Log,sys_Data'>
  <m:item name='ϵͳ��������' link='sys_info.php' rank='sys_Edit' target='main' />
  <m:item name='ϵͳ�û�����' link='sys_admin_user.php' rank='sys_User' target='main' />
  <m:item name='�û����趨' link='sys_group.php' rank='sys_Group' target='main' />
  <m:item name='ͼƬˮӡ����' link='sys_info_mark.php' rank='sys_Edit' target='main' />
  <m:item name='���ݿⱸ��/��ԭ' link='sys_data.php' rank='sys_Data' target='main' />
</m:top>
-----------------------------------------------
";