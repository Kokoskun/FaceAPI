<?php
    //save file image
    $subscriptionKey="{SubscriptionKey}";
	$uploadDir="upload/";
	$fileImage=$_FILES["fileImage"]["name"];    
    $ext=pathinfo($fileImage,PATHINFO_EXTENSION);

	//set name file image
    $nameFile=mktime().".".$ext;
    $uploadfile = $uploadDir.basename($nameFile);
    move_uploaded_file($_FILES["fileImage"]["tmp_name"],$uploadfile);

    //get url of image
    $pageURL='http';
    if($_SERVER["HTTPS"]=="on"){
        $pageURL.="s";
    }
    $pageURL.='://'.$_SERVER["SERVER_NAME"].'/'.'upload/'.$nameFile;

    //set header curl
    $arrHeader=array();
    $arrHeader[]="Content-Type: application/json";
    $arrHeader[]="Ocp-Apim-Subscription-Key: ".$subscriptionKey;
    $url='https://southeastasia.api.cognitive.microsoft.com/face/v1.0/detect?returnFaceId=true&returnFaceLandmarks=false&returnFaceAttributes=age,gender,glasses,emotion,hair,noise';
    $ch=curl_init(); 
    curl_setopt($ch,CURLOPT_URL,$url); 
    curl_setopt($ch,CURLOPT_HEADER,false); 
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$arrHeader);
    $arrPostData=array();
    $arrPostData['Url']=$pageURL;
    curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($arrPostData));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE); 
    $head=curl_exec($ch);
    $httpCode=curl_getinfo($ch,CURLINFO_HTTP_CODE); 
    curl_close($ch);

    //send json
    $response = json_decode($head,true);
    echo json_encode($response);
?>