// invalid フィールドがある場合の処理
(( ) => {
  'use strict';

  // Bootstrap の Validation を使用するフォームを取得する
  const forms = document.querySelectorAll('.needs-validation')

  // フィールドをチェックして、submit を中止する
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})( );

{
  // 勝率の計算
  'use strict';
  // 勝利数、敗戦数のタグを取得
  const wins    = document.getElementById("wins");
  const losses  = document.getElementById("losses");
  console.log(wins);
  console.log(losses);
  
  // 値が変更されたときに呼び出される関数
  wins.addEventListener("input", calcWinningPercentage());
  losses.addEventListener("input", calcWinningPercentage());

  // 勝率を計算
  function calcWinningPercentage( )
  {
    const winningPercentage = Number(wins.value) / (Number(wins.value) + Number(losses.value));
    document.getElementById("winning_percentage").value = winningPercentage.toFixed(3).slice(-4);
    console.log(winningPercentage);
  }
}

{
  // ピタゴラス勝率の計算
  'use strict';
  // 累乗の指数指定
  //const power = 2;    // Bill James
  const power = 1.83;   // BASEBALL-REFERENCE.COM

  // 得点、失点のタグを取得
  const runsScored  = document.getElementById("runs_scored");
  const runsAllowed = document.getElementById("runs_allowed");

  // 値が変更されたときに呼び出される関数
  runsScored.addEventListener("input", calcPythagoreanExpectation(power));
  runsAllowed.addEventListener("input", calcPythagoreanExpectation(power));

  // ピタゴラス勝率を計算
  function calcPythagoreanExpectation(power)
  {
    const pythagoreanExpectation = (Number(runsScored.value) ** power) / ((Number(runsScored.value) ** power) + (Number(runsAllowed.value) ** power));
    document.getElementById("pythagorean_expectation").value = pythagoreanExpectation.toFixed(3).slice(-4);
    console.log(pythagoreanExpectation);
  }
}

{
  // 勝率差分の計算
  'use strict';
  
  // 勝率、ピタゴラス勝率のタグを取得
  const winningPercentage       = document.getElementById("winning_percentage");
  const pythagoreanExpectation  = document.getElementById("pythagorean_expectation");

  // 値が変更されたときに呼び出される関数
  winningPercentage.addEventListener("input", calcWinningPercentageDiﬀerence());
  pythagoreanExpectation.addEventListener("input", calcWinningPercentageDiﬀerence());

  // 勝率差分の計算
  function calcWinningPercentageDiﬀerence( )
  {
    const winningPercentageDiﬀerence = Number(winningPercentage.value) - Number(pythagoreanExpectation.value);
    document.getElementById("winning_percentage_diﬀerence").value = winningPercentageDiﬀerence.toFixed(3).slice(-4);
    console.log(winningPercentageDiﬀerence);
  }
}

{
  // 期待勝敗数(x-W/L)の計算
  'use strict';

  // ピタゴラス勝率のタグを取得
  const pythagoreanExpectation  = document.getElementById("pythagorean_expectation");

  // 値が変更されたときに呼び出される関数
  pythagoreanExpectation.addEventListener("input", calcExpectedWinLoss());

  // x-W/L を計算
  function calcExpectedWinLoss( )
  {
    const expectedWins    = Math.round(162 * (Number(pythagoreanExpectation.value)));
    console
    const expectedLosses  = 162 - expectedWins;
    const expectedWinLoss = expectedWins + '-' + expectedLosses;
    document.getElementById("expected_win_loss").value = expectedWinLoss;
    console.log(expectedWinLoss);
  }
}
