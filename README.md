PHPで超簡単自作フレームワーク
#目的
frameworkと友達になる
~~MVCフレームワークをより理解する為~~

##1. メリット
frameworkの仕様に沿ってコードを書くだけで、保守運用性と開発効率を上げる

##2. どうやって？
frameworkの開発者が幸せになる様にと願いを込めて使いやすい仕様とパワフルなライブラリを書いたから

##3. 事前準備
1. urlパースがよくわからない人はURLを取得できる<a href="http://php.net/manual/ja/reserved.variables.server.php">$_SERVER['PATH_INFO']</a>
2. 動的言語機能が良くわからない人は動的に呼ぶクラスを変更できる<a href="http://php.net/manual/ja/language.namespaces.dynamic.php">動的言語機能</a>

##4. 作ってみよう

###今回実装する内容

1.urlの初めに来るurlに相対したmodelとviewを呼ぶオートローダー機能

例：localhost/blogでリクエスト

```php
include('./models/blog.php');
```

```php
include('./views/blog.php');
```

2.modelはファイル名と同じクラスで作成し、その中のindex(@params)メソッドが呼ばれる
例：localhost/blogでリクエスト

```php
<?php
class blog {
    function index($params) {
        return array('str'=>'Hello World!');
    }
}
```
3.リクエストに対してのviewファイルが存在しなかった場合、定番の404 Not Foundを見せる
4.トップページはどーん！とカッコよく宣伝する！

###ソースコード全容

```php
<?php

if (empty($_SERVER['PATH_INFO'])) {
    //紹介ページを表示
    include('./views/index.php');
    exit;
}

//スラッシュで区切られたurlを取得します
$analysis = explode('/', $_SERVER['PATH_INFO']);
$call;

foreach ($analysis as $value) {

    if ($value !== "") {
        $call = $value;
        break;
    }
}

//modelをインクルードします
if (file_exists('./models/'.$call.'.php')) {

    include('./models/'.$call.'.php');
    //$call名のクラスをインスタンス化します
    $class = new $call();
    //modelのindexメソッドを呼ぶ仕様です
    $ret = $class->index($analysis);
    //配列キーが設定されている配列なら展開します
    if (!is_null($ret)) {
        if(is_array($ret)){
           extract($ret);
        }
    }
}

//viewをインクルードします
if (file_exists('./views/'.$call.'.php')) {
    include('./views/'.$call.'.php');
} else {
    include('./views/error.php');
}
```

##解説
1.パラメーター１つ目が呼ばれるmvと相対します。 

```php
//スラッシュで区切られたurlを取得します
$analysis = explode('/', $_SERVER['PATH_INFO']);
$call;

foreach ($analysis as $value) {
    if($value !== ""){
        $call = $value;
        break;
    }
}

```
2.モデルを呼び、viewに渡す値があれば展開する事で実現します

```php
//modelをインクルードします
if (file_exists('./models/'.$call.'.php')) {

    include('./models/'.$call.'.php');
    //$call名のクラスをインスタンス化します
    $class = new $call();
    //modelのindexメソッドを呼ぶ仕様です
    $ret = $class->index($analysis);
    //配列キーが設定されている配列なら展開します
    if (!is_null($ret)) {
        if(is_array($ret)){
           extract($ret);
        }
    }
}
```

3.viewを呼んで終わり

```php
//viewをインクルードします
if (file_exists('./views/'.$call.'.php')) {
    include('./views/'.$call.'.php');
} else {
    include('./views/error.php');
}
```
ポイントはelse文かなぁ

4.localhost/やlocalhost////って入力された際の対策

```php
if(empty($_SERVER['PATH_INFO'])){
    //紹介ページを表示
    include('./views/index.php');
    exit;
}
```
##結論
頑張って皆が使ってくれる凄いもの作りたいなぁ。
あ、私はLaravel派ですよ！

##Qiita
http://qiita.com/k-okina/items/175b82295ab683ffb624

##参考サイト
* http://choilog.com/katty0324/blog/6
* http://php.net/manual/ja/reserved.variables.server.php
* http://php.net/manual/ja/language.namespaces.dynamic.php