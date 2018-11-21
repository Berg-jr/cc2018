# cc2018
## 概要
ものづくりアイデア展2018 向けのweb問題.

セッションID(PHPSESSID)を奪取することで, adminとしてログインし, 表示されているFLAGを入手する.

## 想定している回答経路
入力フォーム(name,key,text)は入力文字列が適切にエスケープされていないため, JavaScriptによるXSSが行えることがわかり, 送信はGETリクエストで行われていることがわかる.

また, 画面上の説明から管理者 ADMN が定期的にログインすること, nameが空でない時は入力されたtextがname宛に送信されることがURL等からわかる(開発者ツールで判明するかもしれないし, URLでわかるかもしれない).

このことから, 管理者のセッションIDを何らかの方法で自分自身へ送信すれば良く, その宛先はおそらく name に指定すればいいことが考えられる.

よって, 
* 1. JSによるcookieの表示方法 ≒ 管理者のcookieの奪取方法
* 2. JSによるGETパラメータの送信方法(クッキーを宛先nameに送るため), およびサーバー内のPHP処理
* 3. htmlにおいてJSを実行する方法.
が**最低限** わかれば, この問題は突破できる...........はず.

1.については`document.cookie`で, 各ユーザのクッキーが表示できる.

2.については'window.location="/foo/var"'で /foo/var へジャンプできるため, これと合わせてphpへGETパラメータを投げつけるために次のようにして, textフォームに入力すればいい.(ユーザ名hogehogeの時)

`<script>window.location="http://localhost/cc2018/where_is_cookie.php?name=hogehoge&text="+document.cookie</script>`

無限に画面読み込みが開始されるが, 管理者がログインするとそのセッションIDが返信一覧に書き込まれるので, それをcookieに設定しておしまい.

ブラウザだけでcookieを設定するなら, 開発者ツールのコンソールで`document.cookie="PHPSESSID=hugahuga"`かURLに`PHPSESSID=hugahuga`を付け加える.

curlとか使ってもできる, はず.

## 問題点
現状では管理者のログインが手動で行う必要があるが, これは firefox の headless modeでJSを実行することで回避可能なので現在開発中.
さもなくば別端末か別窓で開くしかない。
