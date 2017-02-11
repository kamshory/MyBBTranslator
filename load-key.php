<?php

$dir = '';
if(isset($_GET['dir']))
$dir = @$_GET['dir'];
$dir = strip_tags(str_replace(array("\\", "/"), "", $dir));

$group = '';
if(isset($_GET['group']))
$group = @$_GET['group'];
$group = strip_tags(str_replace(array("\\", "/"), "", $group));

$module = '';
if(isset($_GET['module']))
$module = @$_GET['module'];
$module = strip_tags(str_replace(array("\\", "/"), "", $module));

$langref = '';
if(isset($_GET['langref']))
$langref = @$_GET['langref'];
$langref = strip_tags(str_replace(array("\\", "/"), "", $langref));

$lang2edit = '';
if(isset($_GET['lang2edit']))
$lang2edit = @$_GET['lang2edit'];
$lang2edit = strip_tags(str_replace(array("\\", "/"), "", $lang2edit));

if(($dir == 'public' || $dir == 'admin') && $langref != '' && $module != '')
{
	if($dir == 'public')
	$dir2 = '';
	if($dir == 'admin')
	$dir2 = '/admin';
	
	$parent1 = dirname(__FILE__)."/upload/".$langref.$dir2;
	$parent2 = dirname(__FILE__)."/upload/".$lang2edit.$dir2;
	
	$l = array();
	$module_file1 = $parent1."/".$module;
	if($group == '---' && $module == '---')
	{
		$module_file1 = dirname(__FILE__)."/upload/$langref$dir2/$module";
	}
	if(file_exists($module_file1))
	{
		include $module_file1;
	}
	$keys1 = array_keys($l);
	
	$l = array();
	
	$module_file2 = $parent2."/".$module;
	if($group == '---' && $module == '---')
	{
		$module_file2 = dirname(__FILE__)."/upload/$lang2edit$dir2/$module";
	}
	if(file_exists($module_file2))
	{
		include $module_file2;
	}
	$keys2 = array_keys($l);
	
	$data = array();
	foreach($keys1 as $k=>$v)
	{
		if(in_array($v, $keys2))
		{
			$exists = true;
		}
		else
		{
			$exists = false;
		}
		$data[] = array("key"=>$v, "exists"=>$exists);
	}
	
	echo json_encode($data);
}
?>