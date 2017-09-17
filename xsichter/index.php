<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>unbequeme xsichter</title>
		<link rel="stylesheet" type="text/css" href="css/default.css" />
		<link rel="stylesheet" type="text/css" href="css/component.css" />
		<script src="js/modernizr.custom.js"></script>
	</head>
	<body>
<?php
	if (isset($_GET['index'])) {
		if (is_numeric($_GET['index'])){
			if(intval($_GET['index']) > 0) {
				$dirindex = intval($_GET['index']);
			} else {
				$dirindex = 0;
			}
		}
		else
		{
			$dirindex = 0;
		}
	} else {
		$dirindex = 0;
	}

        if (isset($_GET['p'])) {
                if (is_numeric($_GET['p'])){
			if (intval($_GET['p']) >= 0){
	                        $pindex = intval($_GET['p']);
			} else {
				$pindex = 0;
			}
                }
                else
                {
                        $pindex = 0;
                }
        } else {
                $pindex = 0;
        }

	require_once('preg_find.php');

	$location = '/var/www/html/twistlerpics';
	$pathtoimg = '../twistlerpics/'; //relative
	$domain = 'https://transmonster.wegbuxen.biz';
	$gridmax = 500;
	$lenloc = strlen($location) + 1;

	echo "<div class=\"container\"><header class=\"clearfix\"><h1>@unbequem xsichter archive</h1></header>";

	$dirs = getAllSubDirectories($location,'/');
	sort($dirs, SORT_NATURAL | SORT_FLAG_CASE);
	$count = 0;
	echo "<table><tr>";
	foreach($dirs as $value){
		if (strlen(substr($value,27)) > 2){
			if ($count % 6 === 0) {
				echo "</tr><tr>";
			}
			$fi = new FilesystemIterator($value, FilesystemIterator::SKIP_DOTS);
			$fileCount = iterator_count($fi);
			if($count === $dirindex){
				$output = "<td><a href='?index=". $count ."'><b>" . substr($value,$lenloc,-1) . "</a> (". $fileCount ." files)</b><br /></td>\n";
			}else {
				$output = "<td><a href='?index=". $count ."'>" . substr($value,$lenloc,-1) . "</a> (". $fileCount ." files)<br /></td>\n";
			}
		echo $output;
		}
	$count++;
	}
	echo "</tr></table><br /> <br /><a href='../upload/'>User Upload</a><br/> <br />";

	if (isset($dirindex)) {
		if ($dirindex >0) {
			if(gmp_cmp($dirindex,$count) == -1) {
				$lensubloc = $lenloc + strlen(substr($dirs[$dirindex],$lenloc));
				$namesubdir = substr($dirs[$dirindex],$lenloc,-1);
				echo "Files in <b>". $namesubdir ."</b>:<br />\n";
				echo "<div class=\"main\"><ul id=\"og-grid\" class=\"og-grid\">\n";
				$files = preg_find('/./', $pathtoimg."".$namesubdir , PREG_FIND_RECURSIVE|PREG_FIND_RETURNASSOC |PREG_FIND_SORTMODIFIED);
				$files=array_keys($files);
				for($i = $pindex;$i<count($files);$i++)
				{
					if($i > $pindex + $gridmax -1)
					{
						break;
					}
					$file = $files[$i];
					$title = str_replace($pathtoimg."".$namesubdir."/","",$file);
					$memurl = "../memerize/index.php?img=".$file;
					echo "<li><a href='".$memurl."' data-largesrc='".$file."' data-title='".$title."'><img src='thumb.php?src=".$file."&size=128x72'' alt='upsi error' /></a></li>\n";
				}
				$max = count($files) - $gridmax;
				if (count($files) > $gridmax) {
					$max = count($files) - $gridmax;
					$linkmin = "<a href='?index=".$dirindex."'><<</a>";
					$linkmax = "<a href='?index=".$dirindex."&p=".$max."'>>></a>";
					$linknext = "<a href='?index=".$dirindex."&p=".$i."'>></a>";
					$prevp = $pindex - $gridmax;
					$linkprev = "<a href='?index=".$dirindex."&p=".$prevp."'><</a>";
					echo "</ul><br /><center><b>";
					$output ="";
					if ($pindex > 0) {
						$output .= $linkmin." ";
					}
					if ($pindex > 499 ) {
						$output .= $linkprev." ";
					}
					if ($pindex < count($files) - 500) {
						$output .= $linknext." ".$linkmax;
					}
					echo $output;
				} else {
					echo "</ul><br /><center><b>all files on display";
				}
				echo "</b></center>";
			}
		}
	}



//------------------------------------------------------------------------------------


function getAllSubDirectories( $directory, $directory_seperator ) {
	$dirs = array_map( function($item)use($directory_seperator){ return $item . $directory_seperator;}, array_filter( glob( $directory . '*' ), 'is_dir') );

        foreach( $dirs AS $dir ) {
                $dirs = array_merge( $dirs, getAllSubDirectories( $dir, $directory_seperator ) );
        }

	return $dirs;
}
//------------------------------------------------------------------------------------
?>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="js/grid.js"></script>
		<script>
			$(function() {
				Grid.init();
			});
		</script>

                <p>twitter: <a href="https://twitter.com/unbequem" target="_blank">@unbequem</a><br /> email contact:  <a href='mailto:public@wegbuxen.biz'>public@wegbuxen.biz</a></p>
               </div></div>




	</body>
</html>
