<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <title>디지텍코인 : <?=$this->pageTitle?></title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        .wrap { width: 500px; margin: 100px auto; }
        img { max-width: 100%; vertical-align: middle; }
        header { text-align: center; margin-bottom: 20px; }
        button { width: 100%; }
        .btn { margin-bottom: 10px; }
        .errors { font-weight: bold; color: #f00; }
    </style>
</head>
<body>
<div class="wrap">
    <header><img src="http://static1.textcraft.net/data1/4/8/48f2b7d59430cff90c9abb89f6653b961e37aa3cda39a3ee5e6b4b0d3255bfef95601890afd80709da39a3ee5e6b4b0d3255bfef95601890afd8070974dd369b504ea3a8dc200c335cfe8070.png" alt=""></header>
    <div class="view">
        <?=$this->yieldView()?>    
    </div>
</div>
</body>
</html>
