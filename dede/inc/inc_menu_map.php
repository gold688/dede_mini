<?php
/**
 * �˵���ͼ
 *
 * @version        $Id: inc_menu_map.php 1 10:32 2010��7��21��Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/../config.php");

$maparray = array(1=>'�ĵ����',2=>'ϵͳ����',3=>'���븨������',6=>'����ģ����');

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
$menusMain = "
-----------------------------------------------

<m:top mapitem='1' item='1_' name='���ݹ���' display='block'>
  $addset
  <m:item name='ר�����' ischannel='1' link='content_s_list.php' linkadd='spec_add.php' channelid='-1' rank='spec_New' target='main' />
</m:top>

<m:top mapitem='1' item='1_' name='Ƶ��ģ��' display='block' rank='t_List,t_AccList,c_List,temp_One'>
  <m:item name='����ģ�͹���' link='mychannel_main.php' rank='c_List' target='main' />
  <m:item name='�Զ����' link='diy_main.php' rank='c_List' target='main' />
</m:top>

<m:top mapitem='3' item='1_3_3' name='����ά��' display='block'>
  <m:item name='����ϵͳ����' link='sys_cache_up.php' rank='sys_ArcBatch' target='main' />
  <m:item name='�ĵ��ؼ���ά��' link='article_keywords_main.php' rank='sys_Keyword' target='main' />
  <m:item name='���ݿ������滻' link='sys_data_replace.php' rank='sys_ArcBatch' target='main' />
</m:top>

<m:top mapitem='1' item='1_' name='�Զ�����' notshowall='1'  display='block' rank='sys_MakeHtml'>
  <m:item name='һ��������վ' link='makehtml_all.php' rank='sys_MakeHtml' target='main' />
  <m:item name='����ϵͳ����' link='sys_cache_up.php' rank='sys_ArcBatch' target='main' />
</m:top>

<m:top mapitem='3' item='1_6_' name='��������' display='none' rank='sys_Upload,sys_MyUpload,plus_�ļ�������'>
  <m:item name='�ļ�ʽ������' link='media_main.php?dopost=filemanager' rank='plus_�ļ�������' target='main' />
</m:top>

<m:top mapitem='2' item='10_' name='ϵͳ����' display='none' rank='sys_User,sys_Group,sys_Edit,sys_Log,sys_Data'>
  <m:item name='��֤��ȫ����' link='sys_safe.php' rank='sys_verify' target='main' />
  <m:item name='SQL�����й���' link='sys_sql_query.php' rank='sys_Data' target='main' />
  <m:item name='�ļ�У��[S]' link='sys_verifies.php' rank='sys_verify' target='main' />
  <m:item name='����ɨ��[S]' link='sys_safetest.php' rank='sys_verify' target='main' />
  <m:item name='ϵͳ�����޸�[S]' link='sys_repair.php' rank='sys_verify' target='main' />
</m:top>

<m:top mapitem='2' item='10_7_' name='ģ�����' display='none' rank='temp_One,temp_Other,temp_MyTag,temp_test,temp_All'>
  <m:item name='Ĭ��ģ�����' link='templets_main.php' rank='temp_All' target='main'/>
</m:top>

";

//�������˵�
$plusset = '';
$dsql->SetQuery("SELECT * FROM `#@__plus` WHERE isshow=1 ORDER BY aid ASC");
$dsql->Execute();
while($row = $dsql->GetObject()) 
{
    $plusset .= $row->menustring."\r\n";
}

$menusMain .= "
<m:top mapitem='6' name='ģ�����' c='6,' display='block'>
  <m:item name='ģ�����' link='module_main.php' rank='sys_module' target='main' />
  <m:item name='�ϴ���ģ��' link='module_upload.php' rank='sys_module' target='main' />
  <m:item name='ģ��������' link='module_make.php' rank='sys_module' target='main' />
</m:top>

<m:top mapitem='6' item='7' name='�������' display='block'>
  <m:item name='���������' link='plus_main.php' rank='10' target='main' />
  $plusset
</m:top>
";

$mapstring = '';
$dtp = new DedeTagparse();
$dtp->SetNameSpace('m','<','>');
$dtp->LoadString($menusMain);

foreach($maparray as $k=>$bigname)
{
    $mapstring .= "<dl class='maptop'>\r\n";
    $mapstring .= "<dt class='bigitem'>$bigname</dt>\r\n";
    $mapstring .= "<dd>\r\n";
    foreach($dtp->CTags as $ctag)
    {
        if($ctag->GetAtt('mapitem') == $k)
        {
            $mapstring .= "<dl class='mapitem'>\r\n";
            $mapstring .= "<dt>".$ctag->GetAtt('name')."</dt>\r\n";
            $mapstring .= "<dd>\r\n<ul class='item'>\r\n";
            $dtp2 = new DedeTagParse();
            $dtp2->SetNameSpace('m', '<', '>');
            $dtp2->LoadSource($ctag->InnerText);
            foreach($dtp2->CTags as $j=>$ctag2)
            {
                $mapstring .= "<li><a href='".$ctag2->GetAtt('link')."' target='".$ctag2->GetAtt('target')."'>".$ctag2->GetAtt('name')."</a></li>\r\n";
            }
            $mapstring .= "</ul>\r\n</dd>\r\n</dl>\r\n";
        }
    }
    $mapstring .= "</dd>\r\n</dl>\r\n";
}