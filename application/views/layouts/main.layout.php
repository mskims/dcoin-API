<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <title>디지텍코인 : <?=$this->pageTitle?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        .wrap { width: 500px; margin: 100px auto; }
        img { max-width: 100%; vertical-align: middle; }
        h1 { text-align: center; margin-bottom: 20px; }
        button { width: 100%; }
        .errors { font-weight: bold; color: #f00; }
    </style>
</head>
<body>
<div class="wrap">
    <header><h1><img src="http://static1.textcraft.net/data1/1/7/1783f0e02a4687c2c2b3fdf06f475aef13af86c0da39a3ee5e6b4b0d3255bfef95601890afd80709da39a3ee5e6b4b0d3255bfef95601890afd80709cb107d2d0c48f712b731fbfcb8f3a93d.png" alt=""></h1></header>
    <div class="view">
        <?=$this->yieldView()?>    
    </div>
</div>
</body>
</html>
