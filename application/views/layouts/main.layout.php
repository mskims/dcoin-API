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
    <header><img src="/public/images/logo-md.png" alt=""></header>
    <div class="view">
        <?=$this->yieldView()?>    
    </div>
</div>
</body>
</html>
