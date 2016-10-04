<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <title>디지텍코인 : <?=$this->pageTitle?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        .wrap { width: 500px; margin: 100px auto; }
        h1 { text-align: center; margin-bottom: 20px; }
        button { width: 100%; }
        .errors { font-weight: bold; color: #f00; }
    </style>
</head>
<body>
<div class="wrap">
    <header><h1>디지텍코인</h1></header>
    <div class="view">
        <?=$this->yieldView()?>    
    </div>
</div>
</body>
</html>
