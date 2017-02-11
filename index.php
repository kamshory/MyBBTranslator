<?php
function listdir($parent)
{
	$arrdir = array();
	if(file_exists($parent))
	{
		if ($handle = opendir($parent))
		{
			$i=0;
			while (false !== ($ufile = readdir($handle))) 
			{ 
				$fn = "$parent/$ufile";
				if($ufile == "." || $ufile == ".." ) 
				{
				continue;
				}
				try{
				$filetype = filetype($fn);
				
				unset($obj);
				if($filetype=="dir")
				{
					$obj['fullpath'] = $fn;
					$obj['basename'] = basename($fn);
					$arrdir[] = $obj;
				}
				}
				catch(Exception $e)
				{
					try{
						unset($obj);
						if(is_dir($fn))
						{
							$obj['fullpath'] = $fn;
							$obj['basename'] = basename($fn);
							$arrdir[] = $obj;
						}
						
					}
					catch(Exception $e)
					{
					}
				}
			}
		
		}
	}
	return $arrdir;
}
function listfile($parent)
{
	$arrfile = array();
	if(file_exists($parent))
	{
		if ($handle = opendir($parent))
		{
			$i=0;
			while (false !== ($ufile = readdir($handle))) 
			{ 
				$fn = "$parent/$ufile";
				if($ufile == "." || $ufile == ".." || stripos($ufile, '.php') === false) 
				{
					continue;
				}
				try{
				$filetype = filetype($fn);
				
				unset($obj);
				if($filetype=="file")
				{
					$obj['fullpath'] = $fn;
					$obj['basename'] = basename($fn);
					$arrfile[] = $obj;
				}
				}
				catch(Exception $e)
				{
					try{
						unset($obj);
						if(is_dir($fn))
						{
							$obj['fullpath'] = $fn;
							$obj['basename'] = basename($fn);
							$arrfile[] = $obj;
						}
						
					}
					catch(Exception $e)
					{
					}
				}
			}
		
		}
	}
	return $arrfile;
}
function optiondir($parent, $curdir = null)
{
	$arrdir = listdir($parent);
	$html = "";
	foreach($arrdir as $key=>$val)
	{
		$sel = '';
		if($curdir != null)
		{
			if($val['basename'] == $curdir)
			{
				$sel = ' selected="selected"';
			}
		}
		$html .= '<option value="'.$val['basename'].'"'.$sel.'>'.$val['basename'].'</option>'."\r\n";
	}
	return $html;
}
function optionfile($parent, $curfile = null)
{
	$arrfile = listfile($parent);
	$html = "";
	foreach($arrfile as $key=>$val)
	{
		$sel = '';
		if($arrfile != null)
		{
			if($val['basename'] == $curfile)
			{
				$sel = ' selected="selected"';
			}
		}
		$html .= '<option value="'.$val['basename'].'"'.$sel.'>'.$val['basename'].'</option>'."\r\n";
	}
	return $html;
}

$dir = '';
if(isset($_GET['dir']))
$dir = @$_GET['dir'];
$dir = strip_tags(str_replace(array("\\", "/"), "", $dir));

$lang2edit = '';
if(isset($_GET['lang2edit']))
$lang2edit = @$_GET['lang2edit'];
$lang2edit = strip_tags(str_replace(array("\\", "/"), "", $lang2edit));

$langref = '';
if(isset($_GET['langref']))
$langref = @$_GET['langref'];
$langref = strip_tags(str_replace(array("\\", "/"), "", $langref));


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="shortcut icon" type="image/jpeg" href="favicon.png" />
<title>MyBB Translator</title>
<meta name="dir" content="<?php echo $dir;?>">
<meta name="lang2edit" content="<?php echo $lang2edit;?>">
<meta name="langref" content="<?php echo $langref;?>">
<meta name="group" content="">
<meta name="module" content="">
<meta name="key" content="">
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="script.js"></script>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<div class="control-bar">
<form name="form1" method="get" action="">
  Directory
  <select name="dir" id="dir">
    <option value="public"<?php if($dir == 'public') echo ' selected="selected"';?>>Public</option>
    <option value="admin"<?php if($dir == 'admin') echo ' selected="selected"';?>>Admin</option>
  </select> 
  Language to be Edited
  <select name="lang2edit" id="lang2edit">
    <?php
	if($dir == 'public')
	{
		echo optiondir(dirname(__FILE__).'/upload/', $lang2edit);
	}
	if($dir == 'admin')
	{
		echo optiondir(dirname(__FILE__).'/upload/', $lang2edit);
	}
	?>
  </select> 
  Reference	  
  <select name="langref" id="langref">
    <?php
	if($dir == 'public')
	{
		echo optiondir(dirname(__FILE__).'/upload/', $langref);
	}
	if($dir == 'admin')
	{
		echo optiondir(dirname(__FILE__).'/upload/', $langref);
	}
	?>
  </select> 
  <input type="submit" name="show" value="Show">
  <input type="submit" name="compare" value="Compare">
</form>
</div>
<div class="main-bar">

<?php

if($lang2edit != "" && $langref != "")
{

if($lang2edit != $langref)
{

if(isset($_GET['show']))
{
	if($dir == 'public')
	$dir2 = '';
	if($dir == 'admin')
	$dir2 = '/admin';
?>

<div class="two-side-bar">
<div class="left-bar">


<div class="label">File List</div>
<div class="filelist">
	<?php
	$arrdir = array();
	if($dir == 'public' && $langref != '')
	{
		$arrfile = listfile(dirname(__FILE__).'/upload/'.$langref, $langref);
	}
	if($dir == 'admin' && $langref != '')
	{
		$arrfile = listfile(dirname(__FILE__).'/upload/'.$langref.'/admin', $langref);
	}
	if(count($arrfile))
	{
	?>
    <ul>
		<?php
		foreach($arrfile as $key=>$val)
		{
		?><li><a href="#" data-group="<?php echo $dir2;?>" data-module="<?php echo $val['basename'];?>"><?php echo $val['basename']?></a></li>
        <?php
		}
		?>
    </ul>
    <?php
	}
	?>
</div>

</div>
<div class="right-bar">

    <div class="label">Key List</div>
    <div class="keylist">
        <ul>
        </ul>
    </div>


</div>

</div>


<div class="two-side-bar">
<form name="form2" method="post" action="">
<div class="left-bar">
    <div class="label">Values: <span class="key2edit"></span></div>
    <div class="edit-area"><textarea name="val2edit" id="val2edit"></textarea></div>
    <div class="button-area">
    <input type="button" id="prev" value="&laquo; Prev" onclick="prevVal()"> 
    <input type="button" id="next" value="Next &raquo;" onclick="nextVal()"> 
    <input type="button" id="save" value="Save" onclick="saveVal('#val2edit')"> 
    <input type="button" id="clear" value="Clear" onclick="clearVal('#val2edit')"> 
    <span class="status">&nbsp;</span>
    </div>
</div>

<div class="right-bar">
    <div class="label">Reference: <span class="key2edit"></span></div>
    <div class="edit-area"><textarea name="valref" id="valref"></textarea></div>
</div>    
</form>
</div>

<?php
}
else if(isset($_GET['compare']))
{
?>
Compare
<?php

if(($dir == 'public' || $dir == 'admin') && $langref != '')
{
	if($dir == 'public')
	$dir2 = '';
	if($dir == 'admin')
	$dir2 = '/admin';
	
	$dataref = array();
	$data2edit = array();
	$arrdir = array();
	$arrdir = listdir(dirname(__FILE__).'/upload/'.$langref.$dir2, $langref);
	
	$index1 = dirname(__FILE__)."/upload/$langref$dir2/";
	$index2 = dirname(__FILE__)."/upload/$lang2edit$dir2/";
	
	$missing_idx1 = array();
	$missing_idx2 = array();
	$arrfile = listfile($index1);
	if(count($arrfile))
	{
		foreach($arrfile as $key2=>$val2)
		{
			$dataref['base'][$val2['basename']] = array();
			$data2edit['base'][$val2['basename']] = array();
			
			$l = array();
			
			if(file_exists(dirname(__FILE__)."/upload/$lang2edit$dir2/".$val2['basename']) && stripos($val2['basename'], '.php') !== false)
			{
				include dirname(__FILE__)."/upload/$lang2edit$dir2/".$val2['basename'];
				$data2edit['base'][$val2['basename']] = $l;
			}
			
			$l = array();
			if(file_exists(dirname(__FILE__)."/upload/$langref$dir2/".$val2['basename']) && stripos($val2['basename'], '.php') !== false)
			{
				include dirname(__FILE__)."/upload/$langref$dir2/".$val2['basename'];
				
				$dataref['base'][$val2['basename']] = $l;
			}
			
		}
	}
	
	if(count($arrdir))
	{
		foreach($arrdir as $key=>$val)
		{
			$dataref[$val['basename']] = array();
			$data2edit[$val['basename']] = array();
			$arrfile = listfile($val['fullpath']);
			if(count($arrfile))
			{
				foreach($arrfile as $key2=>$val2)
				{
					$dataref[$val['basename']][$val2['basename']] = array();
					$data2edit[$val['basename']][$val2['basename']] = array();
					
					$l = array();
					
					if(file_exists(dirname(__FILE__)."/upload/$lang2edit$dir2/".$val['basename']."/".$val2['basename']) && stripos($val2['basename'], '.php') !== false)
					{
						include dirname(__FILE__)."/upload/$lang2edit$dir2/".$val['basename']."/".$val2['basename'];
						$data2edit[$val['basename']][$val2['basename']] = $l;
					}
					
					$l = array();
					if(file_exists(dirname(__FILE__)."/upload/$langref$dir2/".$val['basename']."/".$val2['basename']) && stripos($val2['basename'], '.php') !== false)
					{
						include dirname(__FILE__)."/upload/$langref$dir2/".$val['basename']."/".$val2['basename'];
						
						$dataref[$val['basename']][$val2['basename']] = $l;
					}
					
				}
			}
		}
	}


	
	$missing_idx = array();
	$keys1 = array_keys($missing_idx1);
	$keys2 = array_keys($missing_idx2);
	foreach($missing_idx1 as $k1=>$v1)
	{
		if(!in_array($k1, $keys2))
		{
			$missing_idx[$lang2edit.".php"][$k1] = "-- MISSING --";
		}
	}
	
	$missing = array();
	foreach($dataref as $k1=>$v1) 
	{
		$missing[$k1] = array();
		if(!isset($data2edit[$k1]))
		{
			//$data2edit[$k1] = array();
		}
		foreach($v1 as $k2=>$v2)
		{
			$missing[$k1][$k2] = array();
			if(isset($data2edit[$k1][$k2]))
			{
				//$data2edit[$k1][$k2] = array();
			}
			foreach($v2 as $k3=>$v3)
			{
				if(!isset($data2edit[$k1][$k2][$k3]))
				{
					$missing[$k1][$k2][$k3] = "-- MISING --";
				}
			}
			if(count($missing[$k1][$k2]) == 0)
			{
				unset($missing[$k1][$k2]);
			}
		}
		if(count($missing[$k1]) == 0)
		{
			unset($missing[$k1]);
		}
	}
	$count_missing = 0;
	if(count($missing))
	{
	?>
    <ul class="missing-item">
    	<?php
		foreach($missing_idx as $k2=>$v2)
		{
			?>
			<li><?php echo $k2;
			if(is_array($v2))
			{
				?>
				<ul>
					<?php
					foreach($v2 as $k3=>$v3)
					{
						$count_missing++;
						?>
						<li><?php echo $k3." &raquo; ".$v3;?></li>
						<?php
					}
					?>
				</ul>
				<?php
			}
			?></li>
			<?php
		}
		
		
		
		foreach($missing as $k1=>$v1)
		{
			if($k1 != 'base')
			continue;
			?>
            <li><?php echo '<span class="directory">['.$k1.']</span>';
			if(count($v1))
			{
				?>
				<ul>
					<?php
					foreach($v1 as $k2=>$v2)
					{
						?>
						<li><?php echo $k2;
						if(is_array($v2))
						{
							?>
							<ul>
								<?php
								foreach($v2 as $k3=>$v3)
								{
									$count_missing++;
									?>
									<li><?php echo $k3." &raquo; ".$v3;?></li>
									<?php
								}
								?>
							</ul>
							<?php
						}
						?></li>
						<?php

					}

					?>
				</ul>
				<?php
			}
			?></li>
            <?php

		}
		?>
        <li>
        <ul>
        <?php
		foreach($missing as $k1=>$v1)
		{
			if($k1 == 'base')
			continue;
			?>
            <li><?php echo '<span class="directory">['.$k1.']</span>';
			if(count($v1))
			{
				?>
				<ul>
					<?php
					foreach($v1 as $k2=>$v2)
					{
						?>
						<li><?php echo $k2;
						if(is_array($v2))
						{
							?>
							<ul>
								<?php
								foreach($v2 as $k3=>$v3)
								{
									$count_missing++;
									?>
									<li><?php echo $k3." &raquo; ".$v3;?></li>
									<?php
								}
								?>
							</ul>
							<?php
						}
						?></li>
						<?php

					}

					?>
				</ul>
				<?php
			}
			?></li>
            <?php

		}
		?>
        </ul>
        </li>
        <?php


		?>
    </ul>
    <?php
	}
	echo "Total missing: $count_missing";

}

}
?>
<?php
}
else
{
?>
<div class="warning">Language must be different.</div>
<?php
}
}
else
{
?>
<div class="warning">Language must be set.</div>
<?php
}
?>
</div>

</body>
</html>