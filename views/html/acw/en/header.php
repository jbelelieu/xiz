<!doctype html>
<html class="no-js" lang="{{meta.lang}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{meta.title}}</title>
    <meta name="description" content="{{meta.description}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/milligram.css">
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/mobile.css">
    <link rel="stylesheet" href="/css/slicknav.css">
    <link rel="stylesheet" href="/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,700">

    <script src="/js/vendor/modernize.js"></script>
    <script src="//code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="/js/app.js"></script>
    <script src="/js/vendor/jquery.slicknav.min.js"></script>

    <script>
        $(function(){
            $('#menu').slicknav({
                label: 'Menu'
            });
        });
    </script>
</head>
<body>

<!--[if lte IE 9]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
<![endif]-->

<div class="container-fluid" id="titleBar">
    <div class="row">
        <div class="column" id="logo">
            <a href="/">
                <img src="/img/icon.png" alt="" />
            </a>
        </div>
        <div class="column" id="sidebar">
            <ul id="menu">
                <li><a href="/">Home</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="container-fluid" id="subbar">
    <div class="row">
        <h1 class="upperHeader">{{meta.title}}</h1>
    </div>
</div>
