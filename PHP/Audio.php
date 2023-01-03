<!DOCTYPE html><html><head><title>Audio</title><link rel ="stylesheet" type="text/css" href = css/bootstrap.min.css><link rel ="stylesheet" type="text/css" href = css/bootstrap.min.css>?</head>

<style> body{background-attachment: fixed;background-size: 50%; } #songButton {display: inline-block; width :60%; height: 50px;padding-right: 1px; margin-left: -10px; background: linear-gradient(to left,rgba(16,0,0,0), rgba(16,0,0,1)); border-bottom: 1px solidpink; border-radius: 0;} input[type="file"] {display:none; }.btn{box-shadow: 0 0px 1px 0 rgba(0,0,0,0,2),0 6px 9px 0 rgba(0,0,0,0,19); display: inline-block;; cursor: pointer;}.btn_add{color: black; cursor: pointer; padding: 6px; border-radius: 3px; background-color: white;} .bottom{font-size: 10px; float: left; background-color: black; opacity: 0.7; padding: 2px;border-radius: 3px; margin-left: -26px;}.playBox{position: fixed;left: 50%; transform: translate(-50%,0); } .audioPlayer img{height: 50px; width: 50px;float: left;} .audioPlayer{width: 100%; } </style><center>
	
	<body id="body"> <div class="row"><div class ="col-md-4" style="padding-bottom: 100px;"></div>
		<div class="col-md-4 col-md-4 col-sm-12 playBox">

		<table id="myTable"	style="max-width: 100%; border-radius: 8px; opacity: .9; ">
			<tr>
				<td colspan="3" style="padding: 6px;">
					<img src ="2.jpg" id="albumArt" width="550px" height="320px" style=" padding: 6px" align="center"></td>
			</tr>
			<tr><td colspan="1" style="padding: 3px;">
				<button class="btn" data-toggle="tooltip" title="Seek in +15s" onclick="seekplus()" style="background-color: white; border: none;"  >>></span></button></td>
				<button class="btn" data-toggle="tooltip" title="Seek out -15s" onclick="seekminus()" style="background-color: white; border: none;"><<</button></td>

				<td><marquee id="titlemarquee" behavior="scroll" delay="1400" style="width: 275px; color: white; text-shadow: 2px 2px 1px black; font:17px;"></marquee></td>
			</tr>
            <tr>
            	<td style="padding-left: 3px;">
            		<form method="post" action="#" id="form" enctype="multipart/form-data">
            			<label class="btn_add" style="" data-toggle="tooltip" title="Add New Songs" data-placement="bottom">
            				<input name="upload[]" type="file" id="file" multiple="multiple"/>Add</label>
            				<input type="submit" name="uploading" hidden> </form>
            				<script> document.getElementById("file").onchange= function(){
            				var sub=document.getElementById("form").submit();
            			}
            				</script></td>
            				<td> <button class="btn" data-toggle="tooltip" title="Next" data-placement="bottom" onclick="next()" 
            					style="background-color: white; border:none;">-></button></td>
            					<td colspan="2" >
            						<audio id="player" controls autoplay="autoplay" style="float: left; width: 270px;">
            						<source src="" id="uploadedFile" type='audio/mp3' > </audio></td>
            	</tr>
            </table>

        </div>
    </div>
    <div class="row">
    	<?php require_once('id3/getid3/getid3.php');
    	$getid3=new getID3;
    	error_reporting(E_ERROR | E_WARNING | E_PARSE);


    	$total = count($_FILES['upload']['name']);

    		for($y=0 ; $y < $total ; $y++ )
    		{
    			$tmpFilePath = $_FILES['upload']['tmp_name'][$y];

    			$file=$_FILES['upload']['name'][$y];

    			$newFilePath = "songs/" .$file;

    				if(!move_uploaded_file($tmpFilePath, $newFilePath))
    				{
    					echo "Unable to upload";
    				}
    		}

    		chdir('./songs');
    		$filesname= glob('*.mp3');
    		rsort($filesname);
    		#$totalSongs= sizeof($filesname);
    		$i=0; $count=0;
    		$albumArtCount=0;
    		$songNo=0;
    		foreach ($filesname as $file)
    		{
    			$ThisFileInfo=$getid3->analyze($file);
    			getid3_lib::CopyTagsToComments($ThisFileInfo);

    			if (isset($ThisFileInfo['comments']['picture'][0]))
    			{
    				$picture[] = 'data:' .$ThisFileInfo['comments']['picture'][0]['image_mime'].';charset=utf-8;base64,'.
    				base64_encode($ThisFileInfo['comments']['picture'][0]['data']);
    			}

    			$count++; ?>
    			<div class="audioPlayer">
    				<div class="albumDetail">
    					<button name="songButtonName" class="btn btn-danger" id="songButton" value="<?php echo $songNo ;?>" onclick="
    					fun(this.value);">
    					<img src="<?php echo $picture[$albumArtCount];?>" class="" style="margin-top: -4px;">
    					<label class="bottom"><?php echo @$ThisFileInfo['playtime_string']?></label>
    					<label style="float: left; margin-left: 10px; " class="text-justify"><?php echo $file."<br>";?>
    					 <?php echo @$ThisFileInfo ['comments_html']['artist'][0]."<br>";?>
    					</label></button></div></div>

    					<?php
    					$songNo++;
    					$albumArtCount++;
    				}?>

    					<script type="text/javascript">
    						var songarray = <?php echo json_encode($filesname); ?>;
    						var albumArt = <?php echo json_encode($picture); ?>;
    						var titlejs = <?php echo json_encode($filesname); ?>;

    						var randomColor = Math.floor(Math.random()=7);
    					</script>

    				</body>
    				</html>

    			<script>
    				bdy=document.getElementById("body");
    				var music = document.getElementById("player");
    				var albumArtId = document.getElementById("albumArt");

    				var colors=['#ff7422', '#306843', '#561344' , '#ddab45' , '#f25436' , '#f96968' , '#621044'];
    				var a = document.getElementById("playBox");

    			function fun(value){
    				var randomColor=Math.floor(Math.random()*7);
    				document.getElementById("myTable").style.backgroundcolor=colors[randomColor];
    				var songNo= value;
    				albumArtId.src =albumArt[songNo];
    				bdy.background= albumArt[songNo];
    				music.src ="songs/"+songarray[songNo];
    				document.getElementById("titlemarquee").textContent=titlejs[songNo];

    				music.addEventListener('ended',function(e)
    			{
    				songNo++;
    				bdy.background=albumArt[songNo];
    				var randomColor=Math.floor(Math.random()*7);
    				document.getElementById("myTable"),style.backgroundcolor=colors[randomColor];
    				albumArtId.src=albumArtId[songNo];
    				document.getElementById("titlemarquee").textContent=titlejs[songNo];
    				music.src = "songs/"+songarray[songNo];
    				music.load();
    				music.play();

    			}
    		);		
		}

		function seekplus(){
			music.currentTime=music.currentTime+15;}

		function seekminus(){
			music.currentTime=music.currentTime-15;}
		function next(){
			music.currentTime=music.currentTime+1500;}
	</script>





    	
   
            					
            				
            			
            		</form>>
            		
            	</td>
            </tr>

					
				</td>
			</tr>
			<tr>
				<td >
					
				</td>
			</tr>
				
			</td>
				
			</tr>

		
	</body>
</center>>