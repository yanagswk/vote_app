*  /Applications/MAMP/htdocs/fullstack-webdev/900_投票アプリ/part1/start/php/libs/helper.php  
 - get_param関数のように、$_POSTや$_GETなどのスーパーグロバールはいろんなところに記述るのではなく,一つの関数にまとめて、この関数から出ないとgetやpostにアクセスできないようにする。


*  auth.php 全体を()で囲んで「*」演算子にすると、一つ一つがfalseは0、trueは1と計算されて、0が一つでもあると()のなかは0になる。
```php
            // 全て処理されることになるので、エラーメッセージは全て出力される。
            if (!($user->isValidId()
                * $user->isValidPwd()
                * $user->isValidNickname())) {
                return false;
            }
```

* メッセージが何回も出ないようにするには?? → セッションだっけ？  
  * セッションを読んだ後に、そのセッションを削除する。try-finally構文。
* 名前空間はバックスラッシュで記述する必要がある。そのために ""　をする際は「\\」のようにバックスラッシュを2本にする。　　
* archive.phpにはログインしていないと、アクセスできないようにする。→　ログインチェックしてダメなら、home.phpへリダイレクト  
* ログインメッセージのレベルごとに、色を変える。
* パラメータ値はどう取得してる？ =4など
* 過去の一覧からログインユーザー以外の投稿が削除されているのはなぜ？
* session['topic user']はいつ呼ばれたか。
* リダイレクト(redirect())されたらgetが呼ばれる。
この投票アプリの場合、redirect()をpostでよくするが、getが呼ばれている。  
* エラー時に前のメッセージを残した時は、セッションに値を保存して、画面がリダイレクトしても参照できるようにする。
* 2つのテーブルを更新するときは、トランザクションを使うようにする。


## TODO  
- 過去の投稿画面で、viewが大きくなり過ぎたら、「何k」とする処理を追加する。  
- fetchById,fetchByTopicIdのユーザーIDチェック機能実装
- ログイン画面のJSを自力で実装  
- 全てのPHPdocsを記述する。  
- topic.query.phpのupdateとinsertメソッドの値のチェックを実装する。(topic.model.phpに実装する) (解答：246バリテーションチェック、265も続けてやる)  
- クラス内で共通の処理は、クラス変数などにする。
- viewがカウントされない不具合あり？

## 質問  
- 今回、ほぼクラスメソッドで実装しているのは、インスタンスを何個も生成する必要がないからか。
今回のアプリのように、インスタンスを何個も生成する必要のない処理に関しては、クラスメソッドで実装した方が良いのか。  
→ そもそもインスタンスが必要な状況というのは、保持する値が生成されるインスタンス毎に変わってくる状況です。  
一方、クラスメソッドや静的プロパティで保持するものはアプリ全体を通して共通して使用されるメソッドや値ですね。  
そういう意味で大雑把に使い分けを行うとすれば、インスタンスに格納されたプロパティをメソッド内部で使用する必要があるものはインスタンスメソッドで記述しますが、  
そうでないもの（インスタンスのプロパティを使用しないもの）はクラスメソッドで記述しますね。