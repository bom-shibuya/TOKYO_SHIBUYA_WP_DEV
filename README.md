# TOKYO SHIBUYA DEV for WORDPRESS
---

TOKYO SHIBUYA DEVはホームページ手作り用キットです。<br>
サクッとWPのテーマ開発したいよねというときに使います。<br>
完全に個人目線で開発をしていますが、ありきたりな構成ではあるので、cloneしてくればだれでもすぐに開発が始められるでしょう。


## USAGE

### ■ 置き場

cloneしてくる場所はどこでもいいです。どこでもいいと言いましたが、全く別の場所に置くとこまります。  
僕は [Local by Flywheel](https://local.getflywheel.com/)で開発しているんですが、wordpressレポジトリと平行に置いています。  
このように。

    app
      └── public
          ├── build => これ
          ├── index.php
          └── wp

### ■ 最初に

あとは `buildSetting.js` をまず見てください。そして、環境に合うように書き換えてください。この中に `ENV` という定数がありますが、これを `STATIC` または `WP` に書き換えることによって動作と吐き出されるものが変わります。

#### ENV = 'STATIC'

最初静的に構築するときはこれで設定してください。これにすると `build/app/dest` の中にファイルが吐き出され、ローカルサーバーもここを見に行きます。`yarn run release` でcss/jsともに圧縮したものが吐かれます。  
なお、この時は`build/app/src/**/*.html`を監視します。

#### ENV = 'WP'

この場合、`buildSetting.js`で指定したテーマフォルダに吐き出しに行きます。`build/app/src/style.css`や`build/app/src/style-editor.css`、`build/app/src/screenshot.png`も吐き出しに行くので必要があれば変更してください。なお、この場合、htmlは監視せずに、`build/app/src/php/**/*.php`を監視し、テーマフォルダの中に吐き出すので、ここ`single.php`などのファイルを作成していってください。一応たいしたことない雛形も置いています。  
`yarn run release`でcss/jsともに圧縮したものが吐かれます。

### ■ 注意

`ENV`を`WP`にしているとき、`yarn start`あるいは`yarn run release` を叩くたびにテーマフォルダを一回削除して再生成します(`STATIC`の時は`app/dest`フォルダがそうなる)。なので、**このディレクトリをテーマフォルダにおくことは絶対にやめてください。**わけわかんないことなります。  
同じ理由で、**直接テーマフォルダの中に何かを置くのはやめてください。消えます(`app/dest`の中も)。**


## 推奨プラグイン

|投稿を便利にする系|概要|
|:--|:--|
|[Yoast SEO](https://ja.wordpress.org/plugins/wordpress-seo/)|SEO対策|
|[Advanced Custom Field](https://www.advancedcustomfields.com/)|カスタムフィールドの追加|
|[Custom Post Type UI](https://ja.wordpress.org/plugins/custom-post-type-ui/)|カスタム投稿 + カスタムタクソノミーの設定|
|[MW WP Form](https://ja.wordpress.org/plugins/mw-wp-form/)|コンタクトフォーム|
|[Duplicate Post](https://ja.wordpress.org/plugins/duplicate-post/)|記事やページの複製|
|[Intuitive Custom Post Order](https://ja.wordpress.org/plugins/intuitive-custom-post-order/)|ドラッグ&ドロップで記事の並べ替え|
|[Parent Category Toggler](https://ja.wordpress.org/plugins/parent-category-toggler/)|小カテゴリを選択した時に親カテゴリも一緒に選択されるようにできる|
|[Admin Menu Editor](https://ja.wordpress.org/plugins/admin-menu-editor/)|管理画面の編集用|
|[TablePress](https://ja.wordpress.org/plugins/tablepress/)|テーブルを手軽に入力させたい時|
|[TinyMCE Advanced](https://ja.wordpress.org/plugins/tinymce-advanced/#screenshots)|WIGYWIGに機能追加|

|画像系|概要|
|:--|:--|
|[Imsanity](https://ja.wordpress.org/plugins/imsanity/)|大きい画像のアップロードを防止|
|[Regenerate Thumbnails](https://ja.wordpress.org/plugins/regenerate-thumbnails/)|サムネイルを再生成できる|

|セキュリティ系|概要|
|:--|:--|
|[SiteGuard WP Plugin](https://ja.wordpress.org/plugins/siteguard/)|セキュリティ管理|
|[All-in-One WP Migration](https://ja.wordpress.org/plugins/all-in-one-wp-migration/)|引っ越し用|
|[BackWPup](https://ja.wordpress.org/plugins/backwpup/)|自動/手動バックアップ|

|デバッグ用|概要|
|:--|:--|
|[Show Current Template](https://ja.wordpress.org/plugins/show-current-template/)|現在のテンプレートの表示|
|[Debug Bar](https://ja.wordpress.org/plugins/debug-bar/)|管理バーにデバッグ情報を表示|

|その他便利系|概要|
|:--|:--|
|[Search Regex](https://ja.wordpress.org/plugins/search-regex/)|文字列置換用|
|[WP Fastest Cache](https://ja.wordpress.org/plugins/wp-fastest-cache/)|キャッシュの設定|
|[WP-Optimize](https://ja.wordpress.org/plugins/wp-optimize/)|データベースを最適化(古いリビジョンなどいらないものを削除)|
|[Multi Device Switcher](https://ja.wordpress.org/plugins/multi-device-switcher/)|デバイスによってテーマを切り替える|
|[No Category Base (WPML)](https://ja.wordpress.org/plugins/no-category-base-wpml/)|ブログのURLから'category'を除去|

## 構成

### node

* >= 7.0.0

他でも動くと思うけど、動作してるのは7.5.0

### パッケージマネージャ

* yarn

入っていれば`yarn`で、なければhomebrewなりでyarnを落としてくるか、`npm i`でも叩きましょう。おそらくそれでも入ってくると思います。

### タスクランナーなどの構成

* gulp
  * pug -- htmlをどうこうするのに
  * gulp-file-include -- pug使わないときのために一応置いてる。
  * sass(scssでない)
  * pleeease -- cssをいい感じに
  * webpack3 -- jsをどうこうするのに
  * imagemin -- 画像圧縮
  * browser-sync -- ローカルホスト立ち上げ用

* babel
  * env
  * babel-plugin-transform-object-rest-spread
  * babel-polyfill

大まかには以上で、詳しいことはpackage.jsonで

### 元から入れてるプラグイン

**css**
* TOKYO SHIBUYA RESET -- 僕が作った全消しリセット

**js**
* jquery -- どこでも使えるようにしてある
* modernizr -- touch eventだけ
* gsap
* imagesloaded
* webfontloader

## コマンド

開発タスク -- watch

    $ yarn start

開発タスク -- 吐き出しだけ

    $ yarn run build

eslint

    $ yarn run lint

リリースタスク

    $ yarn run release

リリースされたものの確認

    $ yarn run server

## 詳細

### ディレクトリとファイル

ディレクトリは以下

    app -- _release リリースフォルダ
      |  ├ dest ステージング
      |  ├ src 開発
      |     ├ assets
      |       ├ js
      |       ├ img
      |       ├ sass
      |         ├ lib
      |           ├ modules...
      |
    package.json ...

ディレクトリはpackage.jsonとどう階層においてあるDirectoryManager.jsをgulpfileとwebpack configで使っています。<br>
それぞれ、pathの書き方が違うので、そこを柔軟にするために関数化して、必要なら引数を食わせることにしました。  
ディレクトリ構成を変更する場合はそこも確認してみてください。

### webpackとbabelとeslint

**webpack configについて**

現在主流なのはwebpack configをcommmon/dev/prodの3枚とかに分けることだとおもうのですが、今回は対して違いがないので、全て1枚のファイルにまとめています。そしてオブジェクトにぶら下げてわたすことで、gulpで読み込むときにどの設定を読み込むかを分けています。
現状2パターンあります。(dev/prod)

**webpack3とbabel**

babelで jsのトランスパイルを行っていますが、webpack3の絡みのせいでややこしいことになっています。<br>
なぜなら、webpack3ではes6 modules(import/export)をfalseにしないとtree shakingがおこなわれないけれど、設定ファイルである、gulpfile.babel.jsとwebpack.config.babel.jsではimportとか、いろいろ使いたいみたいな気持ちがあったからです。<br>
つまり、.babelrcに設定ファイル用をかいているが、実際のjsをコンパイルするとき用のbabelの設定はwebpack.config内で別途記述しているということです。<br>
もしかしたらなんとかするかもしれません。

**eslint**

FREE CODE CAMPのものをパクってきて使ってます。

### special thanks

inagaki氏のsassファイルからmixins, variablesのutils, colorファイルの構成を使用させてもらってます。<br>
thunk you inagakiiii!!
