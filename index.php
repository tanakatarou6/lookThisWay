<?php
ini_set('log_errors', 'off');  //ログを取るか
ini_set('error_log', 'php.log');  //ログの出力ファイルを指定
session_start(); //セッション使う

// 対戦相手 格納用
$opponents = array();

// キャラクタータイプ クラス(クラス定数)
class Type
{
  const ANIMAL = 0;
  const MAN = 1;
  const WOMAN = 2;
  const OJIISAN = 3;
  const OBAASAN = 4;
}

// 表情 クラス
class Expression
{
  const NORMAL = 1;
  const WIN = 2;
  const LOSE = 3;
  const DROW = 4;
  const HEAL = 5;
}

// 抽象クラス (人間クラス)
abstract class Human
{
  // プロパティ
  protected $name;
  protected $choice;
  protected $choiceImg;
  // メソッド(オーバーライドして使う)
  abstract public function janken();
  abstract public function lookThisWay();
  // セッター＆ゲッター
  public function setName($str)
  {
    $this->name = $str;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setChoice($str)
  {
    $this->choice = $str;
  }
  public function getChoice()
  {
    return $this->choice;
  }
  public function setChoiceImg($str)
  {
    $this->choiceImg = $str;
  }
  public function getChoiceImg()
  {
    return $this->choiceImg;
  }
}

// プレイヤークラス
class Player extends Human
{
  // プロパティ
  protected $life;
  // コンストラクタ
  public function __construct($choice, $choiceImg)
  {
    $this->choice = $choice;
    $this->choiceImg = $choiceImg;
  }
  // メソッド
  public function janken()
  {
    // じゃんけんの処理
  }
  public function lookThisWay()
  {
    // あっちむいてホイの処理
  }
  // セッター&ゲッター
  public function setLife($num)
  {
    $this->life = $num;
  }
  public function getLife()
  {
    return $this->life;
  }
}

// 対戦相手クラス
class Opponent extends Human
{
  // プロパティ
  protected $char;
  protected $charImg;
  protected $talk = 'じゃんけん！';
  protected $type;
  // コンストラクタ
  public function __construct($name, $char, $charImg, $type)
  {
    $this->name = $name;
    $this->char = $char;
    $this->charImg = $charImg;
    $this->type = $type;
  }
  // メソッド
  public function janken()
  {
    $jankenChoice = mt_rand(0, 2);
    switch ($jankenChoice) {
      case 0:
        // グーを選択
        $this->choice = 'g';
        $this->choiceImg = 'janken_g.png';
        break;
      case 1:
        // チョキを選択
        $this->choice = 'c';
        $this->choiceImg = 'janken_c.png';
        break;
      case 2:
        // パーを選択
        $this->choice = 'p';
        $this->choiceImg = 'janken_p.png';
        break;
    }
  }
  public function lookThisWay()
  {
    $lookThisWayChoice = mt_rand(0, 3);
    switch ($lookThisWayChoice) {
      case 0:
        // うえを選択
        $this->choice = 'up';
        $this->choiceImg = 'cursor_up.png';
        break;
      case 1:
        // みぎを選択
        $this->choice = 'right';
        $this->choiceImg = 'cursor_right.png';
        break;
      case 2:
        // ひだりを選択
        $this->choice = 'left';
        $this->choiceImg = 'cursor_left.png';
        break;
      case 3:
        // したを選択
        $this->choice = 'down';
        $this->choiceImg = 'cursor_down.png';
        break;
    }
  }
  public function reaction($str)
  {
    switch ($this->type) {
      case Type::ANIMAL:
        if ($str === 'heal') {
          $this->setTalk('かいふくだよ');
          $this->setCharImg($this->char . '_' . Expression::HEAL . '.png');
        } elseif ($str === 'win') {
          $this->setTalk(($_SESSION['resultFlg']) ? 'まけちゃった…' : 'しっぱい…');
          $this->setCharImg($this->char . '_' . Expression::LOSE . '.png');
        } elseif ($str === 'lose') {
          $this->setTalk(($_SESSION['resultFlg']) ? 'やったー' : 'よかった！');
          $this->setCharImg($this->char . '_' . Expression::WIN . '.png');
        } else {
          $this->setTalk('あいこ！');
          $this->setCharImg($this->char . '_' . Expression::DROW . '.png');
        }
        break;
      case Type::MAN:
        if ($str === 'heal') {
          $this->setTalk('かいふくだよ');
          $this->setCharImg($this->char . '_' . Expression::HEAL . '.png');
        } elseif ($str === 'win') {
          $this->setTalk(($_SESSION['resultFlg']) ? 'しまった…' : 'しっぱい！');
          $this->setCharImg($this->char . '_' . Expression::LOSE . '.png');
        } elseif ($str === 'lose') {
          $this->setTalk(($_SESSION['resultFlg']) ? 'やったね！' : 'よかった！');
          $this->setCharImg($this->char . '_' . Expression::WIN . '.png');
        } else {
          $this->setTalk('あいこ！');
          $this->setCharImg($this->char . '_' . Expression::DROW . '.png');
        }
        break;
      case Type::WOMAN:
        if ($str === 'heal') {
          $this->setTalk('かいふくだよ');
          $this->setCharImg($this->char . '_' . Expression::HEAL . '.png');
        } elseif ($str === 'win') {
          $this->setTalk(($_SESSION['resultFlg']) ? 'きゃ〜' : 'あららら〜');
          $this->setCharImg($this->char . '_' . Expression::LOSE . '.png');
        } elseif ($str === 'lose') {
          $this->setTalk(($_SESSION['resultFlg']) ? 'やったわ！' : 'いいわ〜！');
          $this->setCharImg($this->char . '_' . Expression::WIN . '.png');
        } else {
          $this->setTalk('あいこ！');
          $this->setCharImg($this->char . '_' . Expression::DROW . '.png');
        }
        break;
      case Type::OJIISAN:
        if ($str === 'heal') {
          $this->setTalk('かいふくだよ');
          $this->setCharImg($this->char . '_' . Expression::HEAL . '.png');
        } elseif ($str === 'win') {
          $this->setTalk(($_SESSION['resultFlg']) ? 'おろろろ' : 'おっとっと');
          $this->setCharImg($this->char . '_' . Expression::LOSE . '.png');
        } elseif ($str === 'lose') {
          $this->setTalk(($_SESSION['resultFlg']) ? 'よしよし' : 'ほっほっほー');
          $this->setCharImg($this->char . '_' . Expression::WIN . '.png');
        } else {
          $this->setTalk('あいこ！');
          $this->setCharImg($this->char . '_' . Expression::DROW . '.png');
        }
        break;
      case Type::OBAASAN:
        if ($str === 'heal') {
          $this->setTalk('かいふくだよ');
          $this->setCharImg($this->char . '_' . Expression::HEAL . '.png');
        } elseif ($str === 'win') {
          $this->setTalk(($_SESSION['resultFlg']) ? 'あらあら' : 'まあまあ');
          $this->setCharImg($this->char . '_' . Expression::LOSE . '.png');
        } elseif ($str === 'lose') {
          $this->setTalk(($_SESSION['resultFlg']) ? 'よしよし' : 'ほほほほ');
          $this->setCharImg($this->char . '_' . Expression::WIN . '.png');
        } else {
          $this->setTalk('あいこ！');
          $this->setCharImg($this->char . '_' . Expression::DROW . '.png');
        }
        break;
    }
  }
  // セッター
  public function setChar($str)
  {
    $this->char = $str;
  }
  public function setCharImg($str)
  {
    // セッターを使う事で、直接代入せずにバリデーションチェックをして代入が可能
    $this->charImg = $str;
  }
  public function setTalk($str)
  {
    $this->talk = $str;
  }
  public function setType($str)
  {
    $this->type = $str;
  }
  // ゲッター
  public function getChar()
  {
    return $this->char;
  }
  public function getCharImg()
  {
    return $this->charImg;
  }
  public function getTalk()
  {
    return $this->talk;
  }
  public function getType()
  {
    return $this->type;
  }
}
// プレイヤーのライフを回復できる対戦相手クラス
class HealerOpponent extends Opponent
{
  // コンストラクタ
  public function __construct($name, $char, $charImg, $type)
  {
    // 親クラスのコンストラクタで処理する内容を継承したい場合は、親クラスから呼び出す（下記）
    parent::__construct($name, $char, $charImg, $type);
  }
  // メソッド(オーバーライド)
  public function janken()
  {
    if (!mt_rand(0, 4)) { //5分の1の確率でライフを回復する
      // ライフを回復させる
      $this->choice = 'heal';
      $this->choiceImg = 'heart_plus_1.png';
    } else { //5分の4の確率で普通にじゃんけん
      // 親クラスのjankenメソッドを呼び出す
      parent::janken();
    }
  }
}

// インターフェイスを作ることで、クラスやメソッドの作成し忘れを防止
interface JudgeInterface
{
  public static function jankenJudge($str1, $str2);
}

// 各種システム関連用クラス
class Judge implements JudgeInterface
{
  // ジャンケンの勝敗判定メソッド(静的メソッド)
  public static function jankenJudge($str1, $str2)
  {
    if ($str2 === 'heal') {
      // ⓪ 回復(ボーナス)の場合
      if ($_SESSION['life'] < 3) {
        $_SESSION['life']++;
      }
      $_SESSION['result'] = 'かいふく';
      $_SESSION['myResult'] = 'かいふく';
      $_SESSION['myResultEn'] = 'heal';
      $_SESSION['changeFlg'] = '1';
      // changeFlg が true だと相手が交代になるので、1を入れてる
    } elseif ($str2 === $str1) {
      // ① あいこの場合
      $_SESSION['result'] = 'あいこ';
      $_SESSION['myResult'] = 'あいこ';
      $_SESSION['myResultEn'] = 'drow';
      $_SESSION['resultFlg'] = 0;  //あいこなので再度じゃんけん へ
    } elseif (
      (($str2  === 'g') && ($str1 === 'c')) ||
      (($str2 === 'c') && ($str1 === 'p')) ||
      (($str2  === 'p') && ($str1 === 'g'))
    ) {
      // ② プレイヤーが負けの場合
      $_SESSION['result'] = 'かち';
      $_SESSION['myResult'] = 'まけ';
      $_SESSION['myResultEn'] = 'lose';
      $_SESSION['resultFlg'] = 1;  //プレイヤーのまけ
      error_log('あっちむいてホイ（防衛）に移ります');
    } else {
      // ③ プレイヤーが勝ちの場合
      $_SESSION['result'] = 'まけ';
      $_SESSION['myResult'] = 'かち';
      $_SESSION['myResultEn'] = 'win';
      $_SESSION['resultFlg'] = 2; //プレイヤーのかち
      error_log('あっちむいてホイ（攻撃）に移ります');
    }
  }
}

// インスタンス生成
$opponents[] = new Opponent('おとこのこ', 'boy', 'boy_1.png', Type::MAN);
$opponents[] = new Opponent('おんなのこ', 'girl', 'girl_1.png', Type::WOMAN);
$opponents[] = new Opponent('おにいさん', 'man', 'man_1.png', Type::MAN);
$opponents[] = new Opponent('おねえさん', 'woman', 'woman_1.png', Type::WOMAN);
$opponents[] = new Opponent('おじいさん', 'ojiisan', 'ojiisan_1.png', Type::OJIISAN);
$opponents[] = new Opponent('おばあさん', 'obaasan', 'obaasan_1.png', Type::OBAASAN);
$opponents[] = new Opponent('にんじゃ', 'ninja', 'ninja_1.png', Type::MAN);
$opponents[] = new Opponent('くのいち', 'kunoichi', 'kunoichi_1.png', Type::WOMAN);
$opponents[] = new Opponent('ねこ', 'cat', 'cat_1.png', Type::ANIMAL);
$opponents[] = new Opponent('いぬ', 'dog', 'dog_1.png', Type::ANIMAL);
$opponents[] = new HealerOpponent('ぴょこ', 'pyoko', 'pyoko_1.png', Type::ANIMAL);

function createOpponent()
{
  error_log('対戦相手を生成します(createOpponent)');
  global $opponents;
  $viewOpponent = $opponents[mt_rand(0, 10)];
  // error_log('$viewOpponentのなかみ：' . print_r($viewOpponent, true));
  $_SESSION['opponent'] = $viewOpponent;
  $_SESSION['opponent']->setChoiceImg('question.png');
  $_SESSION['resultFlg'] = 0;
  $_SESSION['changeFlg'] = 0;
}

// 初期化 関数
function init()
{
  error_log('ゲームを初期化します(init)');
  $_SESSION = array();
  $_SESSION['life'] = 3;
  $_SESSION['winCount'] = 0;
  $_SESSION['battleCount'] = 1;
  $_SESSION['resultFlg'] = 0;
  $_SESSION['changeFlg'] = 0;
  createOpponent();
}
function gameOver()
{
  $_SESSION = array();
}


error_log('「「「「「「「「「「「「「「「「「「「「「「「「「');
error_log('「「「「「「「「画面表示開始「「「「「「「「「「「');
error_log('「「「「「「「「「「「「「「「「「「「「「「「「「');

// セッション初期化
if (!empty($_POST['start'])) {
  error_log('セッションを初期化します');
  $_SESSION = array();
}

// post送信されていた場合
if (!empty($_POST)) {
  $startFlg = (!empty($_POST['start'])) ? true : false;
  $choiceFlg = (!empty($_POST['myChoice'])) ? true : false;
  error_log('post送信があります');
  error_log('$_POSTのなかみ：' . print_r($_POST, true));

  if ($startFlg) {
    // 最初の「はじめる」を選択したとき
    init();
  } elseif (!empty($_POST['myChoice'])) {
    // じゃんけん or あっちむいてホイ で自分の手を選んだとき
    switch ($_SESSION['resultFlg']) {
      case 0:
        //じゃんけん、あいこ or 最初のじゃんけん
        error_log('じゃんけんを開始します');

        $_SESSION['opponent']->janken();
        // jankenメソッド呼び出し(相手の手が決定される)

        $myChoice = $_POST['myChoice'];
        $opponentChoice = $_SESSION['opponent']->getChoice();
        error_log('プレイヤの選んだ手：' . print_r($myChoice, true));
        error_log('対戦相手の選んだ手：' . print_r($opponentChoice, true));

        Judge::jankenJudge($myChoice, $opponentChoice);
        // Judgeクラスのジャンケン勝敗判定メソッド

        $_SESSION['opponent']->reaction($_SESSION['myResultEn']);
        // reactionメソッド呼び出し(相手の表情やセリフを変化させる)

        break;

      case 1:
        //じゃんけん、プレイヤーの負け の場合の処理
        error_log('あっちむいてホイ（防衛）を開始します');

        $_SESSION['opponent']->lookThisWay();
        //lookThisWayメソッド呼び出し(相手の選ぶ方向が決定される)

        $myChoice = $_POST['myChoice'];
        $opponentChoice = $_SESSION['opponent']->getChoice();
        error_log('プレイヤの選んだ手：' . print_r($myChoice, true));
        error_log('対戦相手の選んだ手：' . print_r($opponentChoice, true));

        // ここから あっちむいてホイのあたり判定
        if ($_SESSION['opponent']->getChoice() === $myChoice) {
          //当てられた場合（まけ・・・）
          $_SESSION['result'] = 'あたり';
          $_SESSION['myResult'] = 'やられた';
          $_SESSION['myResultEn'] = 'lose';
          $_SESSION['changeFlg'] = 1;  //対戦相手を変えるフラグ
        } else {
          //回避した場合
          $_SESSION['result'] = 'ハズレ';
          $_SESSION['myResult'] = 'セーフ';
          $_SESSION['myResultEn'] = 'win';
          $_SESSION['resultFlg'] = 0;
        }

        $_SESSION['opponent']->reaction($_SESSION['myResultEn']);
        // reactionメソッド呼び出し(相手の表情やセリフを変化させる)

        // ここまで あっちむいてホイのあたり判定
        break;

      case 2:
        //じゃんけん、プレイヤーの勝ち の場合の処理
        $myChoice = $_POST['myChoice'];
        error_log('あっちむいてホイ（攻撃）を開始します');
        error_log('プレイヤーの選んだ方向：' . print_r($myChoice, true));

        $_SESSION['opponent']->lookThisWay();
        //lookThisWayメソッド呼び出し(相手の選ぶ方向が決定される)

        // ここから あっちむいてホイのあたり判定
        if ($_SESSION['opponent']->getChoice() === $myChoice) {
          //当てた場合（かち！！！）
          $_SESSION['result'] = 'やられた';
          $_SESSION['myResult'] = 'あたり';
          $_SESSION['myResultEn'] = 'win';
          $_SESSION['changeFlg'] = 1;  //対戦相手を変えるフラグ
        } else {
          //回避された場合（はずした場合）
          $_SESSION['result'] = 'ハズレ';
          $_SESSION['myResult'] = 'しっぱい';
          $_SESSION['myResultEn'] = 'lose';
          $_SESSION['resultFlg'] = 0;
        }
        $_SESSION['opponent']->reaction($_SESSION['myResultEn']);
        // reactionメソッド呼び出し(相手の表情やセリフを変化させる)

        // ここまで あっちむいてホイのあたり判定
        break;
    }
  } elseif ((!empty($_POST['result'])) && (!empty($_SESSION['changeFlg']))) {
    // あっちむいてホイで勝っても負けてもここに入る(もしくは、「かいふく」の場合)
    error_log('対戦相手を変更する処理、ライフ減少処理、などします');
    if ($_SESSION['myResultEn'] !== 'lose') {
      // あっちむいてホイで買った場合(もしくは、「かいふく」の場合)
      error_log('あてたので相手交代');
      $_SESSION['winCount']++;  //勝ち数に+1
      $_SESSION['battleCount']++; //試合数に+1
      if ($_SESSION['opponent']->getChoice() === 'heal') {
        $_SESSION['winCount']--;  //「かいふく」だった場合、勝ち数を+1しないようにする
      }
    } else {
      // あっちむいてホイで負けた場合
      error_log('あてられたのでライフ−1 + 相手交代');
      $_SESSION['life'] = $_SESSION['life'] - 1;  //あてられたのでライフ−1
      $_SESSION['battleCount']++; //試合数に+1
    }
    createOpponent();  //相手交代
  } else {
    // じゃんけん終了時 or あっちむいてホイで勝敗が決まらない場合
    error_log('交代なし');
    // 表情、セリフ、選んだ手を初期化
    $_SESSION['opponent']->setCharImg($_SESSION['opponent']->getChar() . '_' . Expression::NORMAL . '.png');
    $_SESSION['opponent']->setChoiceImg('question.png');

    $_SESSION['opponent']->setTalk(($_SESSION['resultFlg']) ? 'あっちむいて！' : 'じゃんけん！');
  }
}
error_log('$_SESSIONのなかみ：' . print_r($_SESSION, true));

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>あっちむいてホイ！</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
  <link rel="stylesheet" type="text/css" href="reset.css" />
</head>

<body>
  <header class="header">
    <h1>あっちむいてホイ！</h1>
  </header>
  <div class="container">
    <?php if (empty($_POST)) { ?>
      <form method="post">
        <input type="submit" name="start" class="start" value="はじめる" />
      </form>
  </div>
<?php } elseif (empty($_SESSION['life'])) { ?>
  <div class="gameover">
    <h2>げーむおーばー</h2>
    <p>ぜんぶで <?php echo $_SESSION['winCount']; ?> にん に<br>かったよ ！！</p>
    <a href="index.php">もういちど あそぶ</a>
  </div>
<?php } else { ?>
  <div class="top">
    <h2><?php echo $_SESSION['battleCount']; ?>かいせん</h2>
    <div class="top_wrapper">
      <div class="top_left">
        <img src="images/balloon.png" />
        <p><?php echo $_SESSION['opponent']->getTalk(); ?></p>
      </div>
      <div class="top_center">
        <p><?php echo $_SESSION['opponent']->getName(); ?></p>
        <img src="images/<?php echo $_SESSION['opponent']->getCharImg(); ?>" />
      </div>
      <div class="top_right">
        <div class="choice">
          <img src="images/<?php echo $_SESSION['opponent']->getChoiceImg(); ?>" />
        </div>
      </div>
    </div>
  </div>
  <div class="bottom">
    <div class="bottom_left">
      <h3>あっちむいてホイ！</h3>
      <p>いま <?php echo $_SESSION['winCount']; ?> にんに<br />かったよ</p>
      <ul>
        <?php switch ($_SESSION['life']):
            // 残りライフに応じて表示を変える
          case 1: ?>
            <li><img src="images/heart.png" /></li>
            <li><img src="images/heart_lost.png" /></li>
            <li><img src="images/heart_lost.png" /></li>
          <?php break;
          case 2: ?>
            <li><img src="images/heart.png" /></li>
            <li><img src="images/heart.png" /></li>
            <li><img src="images/heart_lost.png" /></li>
          <?php break;
          case 3: ?>
            <li><img src="images/heart.png" /></li>
            <li><img src="images/heart.png" /></li>
            <li><img src="images/heart.png" /></li>
        <?php break;
        endswitch; ?>
      </ul>
      <form method="post">
        <input type="submit" name="start" value="はじめから" />
      </form>
    </div>
    <div class="bottom_right">
      <h3><?php echo (empty($_POST['myChoice'])) ? '▼ えらんでね ▼' : '▼ おしてね ▼'; ?></h3>
      <?php if (!empty($_POST['myChoice'])) { ?>
        <form method="post">
          <input type="submit" name="result" class="result result_<?php echo ($_SESSION['myResultEn'] === 'lose') ? 'lose' : 'win'; ?>" value="<?php echo $_SESSION['myResult']; ?>" />
        </form>
      <?php } else { ?>
        <div class="user_choice">
          <form method="post">
            <ul>
              <?php if (empty($_SESSION['resultFlg'])) { ?>
                <li class="gu janken">
                  <form method="post">
                    <input type="submit" name="myChoice" alt="グー" value="g">
                    <img src="images/janken_g.png" alt="">
                  </form>
                </li>
                <li class="cho janken">
                  <form method="post">
                    <input type="submit" name="myChoice" alt="チョキ" value="c">
                    <img src="images/janken_c.png" alt="">
                  </form>
                </li>
                <li class="pa janken">
                  <form method="post">
                    <input type="submit" name="myChoice" alt="パー" value="p">
                    <img src="images/janken_p.png" alt="">
                  </form>
                </li>
              <?php } else { ?>
                <li class="up lookthisway">
                  <form method="post">
                    <input type="submit" name="myChoice" alt="うえ" value="up">
                    <img src="images/cursor_up.png" alt="">
                  </form>
                </li>
                <li class="right lookthisway">
                  <form method="post">
                    <input type="submit" name="myChoice" alt="みぎ" value="right">
                    <img src="images/cursor_right.png" alt="">
                  </form>
                </li>
                <li class="down lookthisway">
                  <form method="post">
                    <input type="submit" name="myChoice" alt="した" value="down">
                    <img src="images/cursor_down.png" alt="">
                  </form>
                </li>
                <li class="left lookthisway">
                  <form method="post">
                    <input type="submit" name="myChoice" alt="ひだり" value="left">
                    <img src="images/cursor_left.png" alt="">
                  </form>
                </li>
              <?php } ?>
            </ul>
          </form>
        </div>
      <?php } ?>
    </div>
  </div>
<?php } ?>
</div>
</body>

</html>